<?php
require_once '../shared/db_connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    jsonResponse('error', 'User not logged in');
}

function logDebug($message, $data = null, $backtrace = false) {
    $logMessage = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $logMessage .= " - Data: " . json_encode($data, JSON_PRETTY_PRINT);
    }
    if ($backtrace) {
        $logMessage .= "\nBacktrace: " . print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
    }
    error_log($logMessage);
}

function jsonResponse($status, $message, $data = []) {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];
    
    echo json_encode($response);
    exit;
}

function generateIncrementalSentenceId($mysqli, $language_id) {
    $stmt = $mysqli->prepare("SELECT sentence_id FROM sentences WHERE language_id = ? AND sentence_id REGEXP ? ORDER BY CAST(SUBSTRING_INDEX(sentence_id, '_', -1) AS UNSIGNED) DESC LIMIT 1");
    
    $pattern = "^sent_" . $language_id . "_[0-9]+$";
    $stmt->bind_param("is", $language_id, $pattern);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $parts = explode('_', $row['sentence_id']);
        $lastNumber = intval(end($parts));
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '00001';
    }
    
    $stmt->close();
    
    $sentenceId = "sent_" . $language_id . "_" . $newNumber;
    return array($sentenceId, $newNumber);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Invalid request method');
}

try {
    $mysqli = Database::getInstance();
    if (!$mysqli->ping()) {
        throw new Exception("Database connection failed");
    }

    logDebug("Received POST request", [
        'POST' => $_POST,
        'FILES' => isset($_FILES) ? $_FILES : 'No files'
    ]);

    $required_fields = ['sentence', 'language'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            jsonResponse('error', "Missing required field: {$field}");
        }
    }

    $sentence = trim($_POST['sentence']);
    $language = trim($_POST['language']);

    $language_map = [
        'english' => 1,
        'luganda' => 2,
        'runyankole' => 3
    ];
    
    $language_id = $language_map[$language] ?? null;
    if ($language_id === null) {
        jsonResponse('error', 'Invalid language selection');
    }

    list($original_sentence_id, $incrementalNumber) = generateIncrementalSentenceId($mysqli, $language_id);

    if (!isset($_FILES['audio_file'])) {
        jsonResponse('error', 'No audio file uploaded');
    }

    $audioFile = $_FILES['audio_file'];
    if ($audioFile['error'] !== UPLOAD_ERR_OK) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'Partial upload',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temp directory',
            UPLOAD_ERR_CANT_WRITE => 'Write failed',
            UPLOAD_ERR_EXTENSION => 'Extension stopped upload'
        ];
        jsonResponse('error', $uploadErrors[$audioFile['error']] ?? 'Unknown upload error');
    }

    if ($audioFile['size'] === 0) {
        jsonResponse('error', 'Empty file uploaded');
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "audio/";
    
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception("Failed to create directory: $uploadDir");
        }
    }
    if (!is_writable($uploadDir)) {
        throw new Exception("Directory not writable: $uploadDir");
    }

    $voice_note_id = "voice_{$language_id}_{$incrementalNumber}_" . date('Ymd') . date('His');
    $audioFileName = "{$voice_note_id}.ogg";
    $audioFilePath = $uploadDir . $audioFileName;

    if (!move_uploaded_file($audioFile['tmp_name'], $audioFilePath)) {
        throw new Exception('Failed to move uploaded file');
    }

    $mysqli->begin_transaction();

    try {
        $date = date('Y-m-d H:i:s');
        $status = 'pending';
        $duration = 0;

        logDebug("Preparing to insert sentence", [
            'sentence_id' => $original_sentence_id,
            'sentence' => $sentence,
            'date' => $date,
            'language_id' => $language_id,
            'user_id' => $user_id
        ]);

        $stmt1 = $mysqli->prepare("INSERT INTO sentences (sentence_id, sentence, `date`, language_id, user_id) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt1) {
            throw new Exception('Database error preparing sentence insert: ' . $mysqli->error);
        }
        
        $stmt1->bind_param("sssii", $original_sentence_id, $sentence, $date, $language_id, $user_id);
        if (!$stmt1->execute()) {
            throw new Exception('Error inserting translation: ' . $stmt1->error);
        }
        $stmt1->close();

        $stmt2 = $mysqli->prepare("INSERT INTO voice_notes (voice_note_id, voice_note_path, note_duration, `status`, recording_date, user_id, sentence_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt2) {
            throw new Exception('Database error preparing voice note insert: ' . $mysqli->error);
        }
        
        $relativeAudioPath = "audio/{$audioFileName}";
        $stmt2->bind_param("ssissis", $voice_note_id, $relativeAudioPath, $duration, $status, $date, $user_id, $original_sentence_id);
        if (!$stmt2->execute()) {
            throw new Exception('Error inserting voice note: ' . $stmt2->error);
        }
        $stmt2->close();

        $mysqli->commit();

        jsonResponse('success', 'Translation and audio uploaded successfully', [
            'sentence_id' => $original_sentence_id,
            'voice_note_id' => $voice_note_id
        ]);
    } catch (Exception $e) {
        $mysqli->rollback();
        throw $e;
    }
} catch (Exception $e) {
    logDebug("Error in translation upload", [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    jsonResponse('error', $e->getMessage());
}
?>