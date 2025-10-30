<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'error_type' => ''
];

try {
    include '../shared/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        throw new Exception('Email and password are required', 400);
    }

    $umail = $_POST['email'];
    $passcode = $_POST['password'];

    if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format', 422);
    }

    $mysqli = Database::getInstance();

    $umail = $mysqli->real_escape_string($umail);

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $umail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        throw new Exception('Email not found', 404);
    }

    if (!password_verify($passcode, $user["password"])) {
        throw new Exception('Incorrect password', 401);
    }

    $update_stmt = $mysqli->prepare("UPDATE users SET last_logged_in = NOW() WHERE user_id = ?");
    $update_stmt->bind_param("i", $user["user_id"]);
    $update_stmt->execute();

    session_start();
    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["loggedin"] = true;
    $_SESSION["name"] = $user["name"];
    $_SESSION["user_type"] = $user["acc_type"];

    $response['success'] = true;
    $response['user_type'] = $user["acc_type"];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    
    switch ($e->getMessage()) {
        case 'Email not found':
            $response['error_type'] = 'email_not_found';
            break;
        case 'Incorrect password':
            $response['error_type'] = 'wrong_password';
            break;
        case 'Your account is currently suspended':
            $response['error_type'] = 'account_suspended';
            break;
        case 'Invalid email format':
            $response['error_type'] = 'invalid_email';
            break;
        case 'Invalid request method':
            $response['error_type'] = 'invalid_method';
            break;
        default:
            $response['error_type'] = 'unknown_error';
    }
} finally {
    ob_clean();
    echo json_encode($response);
    exit;
}