<?php
require_once '../shared/db_connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);

session_start();
$user_id = $_SESSION['user_id'];

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
    
    $response = json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    
    if ($response === false) {
        logDebug("JSON encode error", [
            'error' => json_last_error_msg(),
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'JSON encoding failed']);
        exit;
    }
    
    echo $response;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = date('Y-m-d H:i:s');
    try {
        $mysqli = Database::getInstance();
        if (!$mysqli->ping()) {
            throw new Exception("Database connection failed");
        }
        
        logDebug("Received POST request", [
            'POST' => $_POST,
            'FILES' => isset($_FILES) ? $_FILES : 'No files'
        ]);

        $uploadDir = "audio/";
        
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception("Failed to create directory: $uploadDir");
            }
        }
        if (!is_writable($uploadDir)) {
            throw new Exception("Directory not writable: $uploadDir");
        }

        $mysqli->begin_transaction();

        try {
            foreach ($_FILES as $key => $audioFile) {
                if (!preg_match('/^audio_(\d+)$/', $key, $matches)) {
                    continue;
                }
                $index = $matches[1];
                $sentenceId = $_POST["sentence_id_$index"];

                if ($audioFile['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("Error uploading file $key");
                }

                if ($audioFile['size'] === 0) {
                    throw new Exception("Empty file uploaded for $key");
                }

                $voiceNoteId = str_replace('sent', 'voice', $sentenceId) . '_' . date('Ymd') . date('His');
                
                $audioFileName = "{$voiceNoteId}.ogg";
                $audioFilePath = $uploadDir . $audioFileName;
                
                if (!move_uploaded_file($audioFile['tmp_name'], $audioFilePath)) {
                    throw new Exception("Failed to move uploaded file $key");
                }

                $checkStmt = $mysqli->prepare("SELECT COUNT(*) as count FROM voice_notes WHERE voice_note_id = ?");
                $checkStmt->bind_param("s", $voiceNoteId);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                $row = $result->fetch_assoc();
                $checkStmt->close();

                $status = 'pending';

                if ($row['count'] > 0) {
                    $stmt = $mysqli->prepare("UPDATE voice_notes SET voice_note_path = ?, `status` = ?, recording_date = ?, user_id = ? WHERE voice_note_id = ?");
                    $stmt->bind_param("sssis", $audioFilePath, $status, $date, $user_id, $voiceNoteId);
                } else {
                    $stmt = $mysqli->prepare("INSERT INTO voice_notes (voice_note_id, voice_note_path, `status`, recording_date, user_id, sentence_id) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssis", $voiceNoteId, $audioFilePath, $status, $date, $user_id, $sentenceId);
                }

                if (!$stmt->execute()) {
                    throw new Exception('Error saving voice note: ' . $stmt->error);
                }
                $stmt->close();
            }

            $mysqli->commit();
            jsonResponse('success', 'Voice recordings processed successfully');
            
        } catch (Exception $e) {
            $mysqli->rollback();
            throw $e;
        }
    } catch (Exception $e) {
        jsonResponse('error', $e->getMessage());
    }
}
?>