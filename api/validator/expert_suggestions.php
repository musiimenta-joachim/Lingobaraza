<?php
header('Content-Type: application/json');

try {
    require_once '../shared/db_connect.php';
    require_once '../shared/verify_status.php';
} catch (Exception $e) {
    error_log("Configuration Error: " . $e->getMessage());
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

    $mysqli = Database::getInstance();
    $user_id = $_SESSION['user_id'];
    $languageQuery = "SELECT preferred_languages FROM users WHERE user_id = ?";
    $languageStmt = $mysqli->prepare($languageQuery);
    $languageStmt->bind_param('i', $user_id);
    $languageStmt->execute();
    $languageResult = $languageStmt->get_result();
    $rawLanguages = $languageResult->fetch_assoc()['preferred_languages'];

    error_log("Raw Preferred Languages (full dump): " . print_r($rawLanguages, true));

    $userLanguages = json_decode($rawLanguages, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON Decode Error: " . json_last_error_msg());
    }

    $languageMap = [
        'English' => 1,
        'Luganda' => 2,
        'Runyankole' => 3
    ];

    $languageIDs = array_map(function ($lang) use ($languageMap) {
        $cleanLang = trim($lang, '"\'');
        return $languageMap[$cleanLang] ?? null;
    }, $userLanguages);

    $languageIDs = array_filter($languageIDs);

    if (empty($languageIDs)) {
        echo json_encode(['error' => 'No valid preferred languages found']);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($languageIDs), '?'));
    $query = "SELECT 
                c.correction_id,
                c.correction,
                c.date,
                c.expert_id,
                c.sentence_id,
                s.sentence
              FROM 
                corrections c
              JOIN
                sentences s ON c.sentence_id = s.sentence_id
              LEFT JOIN 
                votes v ON c.correction_id = v.correction_id AND v.user_id = ?
              WHERE 
                s.language_id IN ($placeholders)
                AND v.correction_id IS NULL
              LIMIT 10";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $mysqli->error);
    }

    $paramTypes = 'i' . str_repeat('i', count($languageIDs));
    $params = array_merge([$user_id], $languageIDs);
    $stmt->bind_param($paramTypes, ...$params);

    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'correction_id' => $row['correction_id'],
            'correction' => $row['correction'],
            'sentence' => $row['sentence']
        ];
    }

    echo json_encode($data);
} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error']);
}
?>