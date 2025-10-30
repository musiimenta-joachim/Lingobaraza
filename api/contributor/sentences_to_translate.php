<?php
header('Content-Type: application/json');

try {
    require_once '../shared/db_connect.php';
    require_once '../shared/verify_status.php';
} catch (Exception $e) {
    echo json_encode(['error' => 'Configuration error']);
    exit;
}

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $mysqli = Database::getInstance();

    $languageQuery = "SELECT preferred_languages FROM users WHERE user_id = ?";
    $languageStmt = $mysqli->prepare($languageQuery);
    $languageStmt->bind_param('i', $user_id);
    $languageStmt->execute();
    $languageResult = $languageStmt->get_result();

    if ($languageResult->num_rows === 0) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $userRow = $languageResult->fetch_assoc();
    $preferredLanguagesJson = $userRow['preferred_languages'];
    $preferredLanguages = json_decode($preferredLanguagesJson, true);

    if (!is_array($preferredLanguages)) {
        echo json_encode(['error' => 'Invalid preferred_languages format']);
        exit;
    }

    if (count($preferredLanguages) <= 1) {
        echo json_encode([
            'preferred_languages' => $preferredLanguages,
            'base_sentences' => []
        ]);
        exit;
    }

    $languageMap = [
        'English' => 1,
        'Luganda' => 2,
        'Runyankole' => 3
    ];

    $languageNumbers = array_map(function($lang) use ($languageMap) {
        $normalizedLang = ucfirst(strtolower(trim($lang)));
        if (!isset($languageMap[$normalizedLang])) {
            throw new Exception("Invalid language key: $normalizedLang");
        }
        return $languageMap[$normalizedLang];
    }, $preferredLanguages);

    $languageConditions = [];
    $languageParams = [];
    $paramTypes = '';

    if (in_array(1, $languageNumbers)) {
        $languageConditions[] = "english = '*'";
        $languageParams[] = 1;
        $paramTypes .= 'i';
    }
    if (in_array(2, $languageNumbers)) {
        $languageConditions[] = "luganda = '*'";
        $languageParams[] = 2;
        $paramTypes .= 'i';
    }
    if (in_array(3, $languageNumbers)) {
        $languageConditions[] = "runyankole = '*'";
        $languageParams[] = 3;
        $paramTypes .= 'i';
    }

    if (empty($languageConditions)) {
        $responseData['base_sentences'] = [];
        echo json_encode($responseData);
        exit;
    }

    $baseSentencesQuery = "WITH all_versions AS (SELECT 
            CONCAT('sent_1_', SUBSTRING_INDEX(s.sentence_id, '_', -1)) AS english_id,
            CONCAT('sent_2_', SUBSTRING_INDEX(s.sentence_id, '_', -1)) AS luganda_id,
            CONCAT('sent_3_', SUBSTRING_INDEX(s.sentence_id, '_', -1)) AS runyankole_id,
            SUBSTRING_INDEX(s.sentence_id, '_', -1) AS base_id
        FROM sentences s
        GROUP BY base_id
    ),
    missing_combinations AS (
        SELECT 
            all_versions.base_id,
            CASE 
                WHEN NOT EXISTS (SELECT 1 FROM sentences WHERE sentence_id = all_versions.english_id) THEN '*' 
                ELSE all_versions.english_id 
            END AS english,
            CASE 
                WHEN NOT EXISTS (SELECT 1 FROM sentences WHERE sentence_id = all_versions.luganda_id) THEN '*' 
                ELSE all_versions.luganda_id 
            END AS luganda,
            CASE 
                WHEN NOT EXISTS (SELECT 1 FROM sentences WHERE sentence_id = all_versions.runyankole_id) THEN '*' 
                ELSE all_versions.runyankole_id 
            END AS runyankole
        FROM all_versions
    )
    SELECT 
        mc.*,
        se.sentence AS english_sentence,
        sl.sentence AS luganda_sentence,
        sr.sentence AS runyankole_sentence
    FROM missing_combinations mc
    LEFT JOIN sentences se ON mc.english = se.sentence_id
    LEFT JOIN sentences sl ON mc.luganda = sl.sentence_id
    LEFT JOIN sentences sr ON mc.runyankole = sr.sentence_id
    WHERE " . implode(' OR ', $languageConditions) . ";
    ";

    $baseSentencesResult = $mysqli->query($baseSentencesQuery);
    if (!$baseSentencesResult) {
        throw new Exception($mysqli->error);
    }

    $baseSentences = $baseSentencesResult->fetch_all(MYSQLI_ASSOC);

    $languageKeyMap = [
        'English' => 'english',
        'Luganda' => 'luganda',
        'Runyankole' => 'runyankole'
    ];

    $sentenceKeyMap = [
        'English' => 'english_sentence',
        'Luganda' => 'luganda_sentence',
        'Runyankole' => 'runyankole_sentence'
    ];

    $filteredBaseSentences = [];

    foreach ($baseSentences as $sentence) {
        $missingLanguages = [];
        $availableLanguages = [];

        foreach ($preferredLanguages as $lang) {
            $key = $languageKeyMap[$lang];
            $sentenceKey = $sentenceKeyMap[$lang];
            if ($sentence[$key] === '*') {
                $missingLanguages[] = $lang;
            } else {
                $availableLanguages[$lang] = [
                    'id' => $sentence[$key],
                    'sentence' => $sentence[$sentenceKey]
                ];
            }
        }

        if (count($missingLanguages) === count($preferredLanguages)) {
            continue;
        }
        if (count($availableLanguages) === 1 && !empty($missingLanguages)) {
            $availableLang = key($availableLanguages);
            $firstMissingLang = $missingLanguages[0];

            $filteredSentence = [
                'base_id' => $sentence['base_id']
            ];
            $filteredSentence[$languageKeyMap[$availableLang]] = $availableLanguages[$availableLang]['id'];
            $filteredSentence[$languageKeyMap[$availableLang] . '_sentence'] = $availableLanguages[$availableLang]['sentence'];
            $filteredSentence[$languageKeyMap[$firstMissingLang]] = '*';
            $filteredSentence[$languageKeyMap[$firstMissingLang] . '_sentence'] = '*';

            $filteredBaseSentences[] = $filteredSentence;
        }
        elseif (count($availableLanguages) > 1) {
            $filteredSentence = ['base_id' => $sentence['base_id']];
            foreach ($availableLanguages as $lang => $langData) {
                $filteredSentence[$languageKeyMap[$lang]] = $langData['id'];
                $filteredSentence[$languageKeyMap[$lang] . '_sentence'] = $langData['sentence'];
            }
            foreach ($missingLanguages as $missingLang) {
                $filteredSentence[$languageKeyMap[$missingLang]] = '*';
                $filteredSentence[$languageKeyMap[$missingLang] . '_sentence'] = '*';
            }
            $filteredBaseSentences[] = $filteredSentence;
        }
    }

    $responseData = [
        'preferred_languages' => $preferredLanguages,
        'base_sentences' => $filteredBaseSentences
    ];

    echo json_encode($responseData, JSON_THROW_ON_ERROR);

} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}