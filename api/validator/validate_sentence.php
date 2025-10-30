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
        'success' => $status === 'success',
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
        echo json_encode(['success' => false, 'message' => 'JSON encoding failed']);
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
        
        logDebug("Received validation request", [
            'POST' => $_POST
        ]);

        $sentenceId = isset($_POST['sentence_id']) ? trim($_POST['sentence_id']) : '';
        $sentenceStatus = isset($_POST['sentence_status']) ? trim($_POST['sentence_status']) : '';
        $expertSuggestion = isset($_POST['expert_suggestion']) ? trim($_POST['expert_suggestion']) : null;

        if (empty($sentenceId)) {
            jsonResponse('error', 'Missing sentence ID');
        }
        
        if (empty($sentenceStatus)) {
            jsonResponse('error', 'Missing sentence status');
        }

        $mysqli->begin_transaction();

        try {
            if ($sentenceStatus === 'approved' || $sentenceStatus === 'rejected') {
                $stmt = $mysqli->prepare("INSERT INTO validated_sentences (`status`, `date`, expert_id, sentence_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $sentenceStatus,$date, $user_id, $sentenceId);
                
                if (!$stmt->execute()) {
                    throw new Exception('Error inserting validated sentence: ' . $stmt->error);
                }
                $stmt->close();
            } 
            elseif ($sentenceStatus === 'suggestion') {
                $stmt = $mysqli->prepare("INSERT INTO corrections (correction_id, correction, `date`, expert_id, sentence_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $sentenceId, $expertSuggestion, $date, $user_id, $sentenceId);
                
                if (!$stmt->execute()) {
                    throw new Exception('Error inserting sentence correction: ' . $stmt->error);
                }
                $stmt->close();
            } else {
                throw new Exception('Invalid sentence status');
            }

            $mysqli->commit();

            jsonResponse('success', 'Sentence validation processed successfully');

        } catch (Exception $e) {
            $mysqli->rollback();
            throw $e;
        }

    } catch (Exception $e) {
        logDebug('Validation Error', ['message' => $e->getMessage()]);
        jsonResponse('error', $e->getMessage());
    }
}
?>