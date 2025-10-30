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

    $user_id = $_SESSION['user_id'];
    $mysqli = Database::getInstance();

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
                vn.voice_note_id,
                vn.voice_note_path,
                vn.status,
                s.sentence_id,
                s.sentence,
                s.language_id
              FROM 
                voice_notes vn
              JOIN 
                sentences s ON vn.sentence_id = s.sentence_id
              JOIN 
                validated_sentences vs ON s.sentence_id = vs.sentence_id
              WHERE 
                s.language_id IN ($placeholders)
                AND vs.status = 'approved'
                AND vn.status = 'pending'
              LIMIT 10";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $mysqli->error);
    }

    $paramTypes = str_repeat('i', count($languageIDs));
    $stmt->bind_param($paramTypes, ...$languageIDs);

    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $detailedData = [];

    while ($row = $result->fetch_assoc()) {
        $detailedData[] = $row;
        error_log("Found Voice Note: " . print_r($row, true));
    }

    echo json_encode([
        'voice_notes' => $detailedData,
        'language_ids' => $languageIDs,
        'raw_languages' => $rawLanguages,
        'processed_languages' => $userLanguages
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    error_log("Unhandled Exception: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());

    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>