<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$user_id = intval($_GET['user_id']);
$mysqli = Database::getInstance();

$user_query = "SELECT user_id, user_name, main_contact, alt_contact, age, gender, `address`, acc_type, reg_date FROM users WHERE user_id = ?";

$stmt = $mysqli->prepare($user_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'User data query failed: ' . $mysqli->error]);
    exit;
}

$user_data = $result->fetch_assoc();

if (!$user_data) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit;
}

header('Content-Type: application/json');
echo json_encode($user_data);
?>