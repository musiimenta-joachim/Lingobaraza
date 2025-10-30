<?php
header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

include '../shared/db_connect.php';
require '../shared/verify_status.php';

function sendJsonResponse($success, $message, $code = 200) {
    http_response_code($code);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Method Not Allowed', 405);
}

$errorHandler = function($errno, $errstr, $errfile, $errline) {
    sendJsonResponse(false, "PHP Error: $errstr in $errfile on line $errline", 500);
};
set_error_handler($errorHandler);

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        sendJsonResponse(false, 'Invalid JSON input', 400);
    }

    if (!isset($data['receivers']) || !isset($data['mail_subject']) || !isset($data['mail_message'])) {
        sendJsonResponse(false, 'Missing required fields', 400);
    }

    $mysqli = Database::getInstance();
    if (!$mysqli) {
        sendJsonResponse(false, 'Database connection failed', 500);
    }

    switch ($data['receivers']) {
        case 'all':
            $query = "SELECT email FROM users WHERE acc_type IN ('validator', 'contributor')";
            break;
        case 'validator':
            $query = "SELECT email FROM users WHERE acc_type = 'validator'";
            break;
        case 'contributor':
            $query = "SELECT email FROM users WHERE acc_type = 'contributor'";
            break;
        default:
            sendJsonResponse(false, 'Invalid receiver type', 400);
    }

    $result = $mysqli->query($query);
    if (!$result) {
        sendJsonResponse(false, 'Failed to retrieve email addresses', 500);
    }
    $recipients = [];
    while ($row = $result->fetch_assoc()) {
        $recipients[] = $row['email'];
    }

    if (empty($recipients)) {
        sendJsonResponse(false, 'No recipients found', 404);
    }

    $successCount = 0;
    $failureCount = 0;
    $from = "From: admin@lingobaraza.com\r\nReply-To: admin@lingobaraza.com\r\n";
    $headers = $from . "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    foreach ($recipients as $recipient) {
        $mailBody = nl2br(htmlspecialchars($data['mail_message']));
        
        if (mail($recipient, $data['mail_subject'], $mailBody, $headers)) {
            $successCount++;
        } else {
            $failureCount++;
        }
    }

    $sender = 'Lingobaraza Admin';

    $logSql = "INSERT INTO email_logs (sender, `subject`, `message`, recipient_type, success_count, failure_count, sent_date)
               VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $logStmt = $mysqli->prepare($logSql);
    $logStmt->bind_param(
        'ssssii',
        $sender,
        $data['mail_subject'],
        $data['mail_message'],
        $data['receivers'],
        $successCount,
        $failureCount
    );
    $logStmt->execute();

    sendJsonResponse(true, "Emails sent successfully. Total: $successCount, Failed: $failureCount");

} catch (Exception $e) {
    sendJsonResponse(false, 'An unexpected error occurred: ' . $e->getMessage(), 500);
} finally {
    if (isset($mysqli)) {
        $mysqli->close();
    }
}