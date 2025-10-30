<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

function logError($message) {
    error_log('[Validation API] ' . $message);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$input = file_get_contents('php://input');
logError('Received input: ' . $input);

$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    logError('JSON decode error: ' . json_last_error_msg());
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid JSON: ' . json_last_error_msg(),
        'received_data' => $input
    ]);
    exit;
}

if (!isset($data['validation_results']) || !is_array($data['validation_results'])) {
    http_response_code(400);
    logError('Invalid input format');
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid input format',
        'received_data' => $data
    ]);
    exit;
}

$user_id = $_SESSION["user_id"];
$upload_directory = '../contributor/audio/';

$mysqli = Database::getInstance();

try {
    $mysqli->begin_transaction();

    $stmt = $mysqli->prepare("UPDATE voice_notes SET `status` = ?, validator = ?, validation_date = NOW() WHERE voice_note_id = ?");

    $successful_validations = 0;
    $failed_validations = 0;
    $deleted_files = 0;

    foreach ($data['validation_results'] as $result) {
        if (!isset($result['id']) || !isset($result['status'])) {
            logError('Skipping invalid result: ' . json_encode($result));
            continue;
        }

        $stmt->bind_param('sis', 
            $result['status'], 
            $user_id, 
            $result['id']
        );

        if ($stmt->execute()) {
            $successful_validations++;

            if ($result['status'] === 'rejected') {
                $file_path = $upload_directory . $result['id'] . '.wav';
                if (file_exists($file_path)) {
                    if (unlink($file_path)) {
                        $deleted_files++;
                    } else {
                        logError('Failed to delete file: ' . $file_path);
                    }
                }
            }
        } else {
            $failed_validations++;
            logError('Failed to validate voice note: ' . $result['id']);
        }
    }

    $mysqli->commit();

    $response = [
        'success' => true,
        'message' => "Validation completed",
        'successful_validations' => $successful_validations,
        'failed_validations' => $failed_validations,
        'deleted_files' => $deleted_files
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    $mysqli->rollback();

    http_response_code(500);
    logError('Database error: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $mysqli->close();
}