<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

$user_id = $_SESSION["user_id"];

$mysqli = Database::getInstance();

$query = "SELECT user_id, user_name, main_contact, alt_contact, age, gender, email, `address`, preferred_languages, level_of_fluency, acc_type, reg_date FROM users WHERE user_id=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['error' => 'Database query failed: ' . $mysqli->error]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

if (empty($data)) {
    echo json_encode([]);
    exit;
}

header('Content-Type: application/json');

echo json_encode($data);
