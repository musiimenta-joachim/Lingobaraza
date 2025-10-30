<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

$user_id = $_SESSION["user_id"];
$mysqli = Database::getInstance();

$user_query = "SELECT user_id, user_name, main_contact, alt_contact, age, gender, email, `address`, preferred_languages, level_of_fluency, acc_type, reg_date FROM users WHERE user_id=?";
$stmt = $mysqli->prepare($user_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['error' => 'User data query failed: ' . $mysqli->error]);
    exit;
}

$user_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $user_data[] = $row;
}

if (empty($user_data)) {
    echo json_encode([]);
    exit;
}

$about_admin_query = "SELECT * FROM about_admin WHERE admin_id = ?";
$stmt = $mysqli->prepare($about_admin_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$admin_result = $stmt->get_result();

$about_admin_data = [];
while ($row = mysqli_fetch_assoc($admin_result)) {
    $about_admin_data[] = $row;
}

$about_project_query = "SELECT * FROM about_project";
$project_result = $mysqli->query($about_project_query);

$about_project_data = [];
while ($row = mysqli_fetch_assoc($project_result)) {
    $about_project_data[] = $row;
}

$data = [
    'user_data' => $user_data,
    'about_admin_data' => $about_admin_data,
    'about_project_data' => $about_project_data
];

header('Content-Type: application/json');

echo json_encode($data);
?>