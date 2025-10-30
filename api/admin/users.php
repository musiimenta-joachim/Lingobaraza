<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

$mysqli = Database::getInstance();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total_count_query = "SELECT COUNT(*) as total FROM users";
$total_count_result = $mysqli->query($total_count_query);
$total_count = $total_count_result->fetch_assoc()['total'];
$total_pages = ceil($total_count / $limit);

$users_query = "SELECT user_id, user_name, main_contact, alt_contact, age, gender, email, `address`, acc_type, reg_date 
                FROM users LIMIT ? OFFSET ?";

$stmt = $mysqli->prepare($users_query);
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$users_result = $stmt->get_result();

$users_data = [];

while ($user_row = $users_result->fetch_assoc()) {
    $users_data[] = $user_row;
}

$response = [
    'users' => $users_data,
    'total_pages' => $total_pages,
    'current_page' => $page,
    'total_users' => $total_count
];

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>