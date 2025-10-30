<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

if (!isset($_POST['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$mysqli = Database::getInstance();
$user_id = intval($_POST['user_id']);

$update_fields = [];
$param_types = '';
$param_values = [];

$allowed_fields = [
    'user_name' => 's',
    'acc_type' => 's',
    'address' => 's',
    'main_contact' => 's',
    'alt_contact' => 's',
    'gender' => 's',
    'age' => 's'
];

foreach ($allowed_fields as $field => $type) {
    if (isset($_POST[$field])) {
        $update_fields[] = "`$field` = ?";
        $param_types .= $type;
        $param_values[] = $_POST[$field];
    }
}

if (!empty($_POST['password'])) {
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $update_fields[] = "`password` = ?";
    $param_types .= 's';
    $param_values[] = $hashed_password;
}

if (empty($update_fields)) {
    echo json_encode(['message' => 'No fields to update']);
    exit;
}

$param_types .= 'i';
$param_values[] = $user_id;

$query = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE user_id = ?";

$stmt = $mysqli->prepare($query);

if ($stmt) {
    $refs = [];
    foreach ($param_values as $key => $value) {
        $refs[$key] = &$param_values[$key];
    }
    
    array_unshift($refs, $param_types);
    call_user_func_array([$stmt, 'bind_param'], $refs);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'User details updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update user: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare statement: ' . $mysqli->error]);
}
?>