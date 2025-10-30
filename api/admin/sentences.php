<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

$user_id = $_SESSION["user_id"];
$mysqli = Database::getInstance();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

$total_count_query = "SELECT COUNT(*) as total FROM sentences WHERE language_id IN (1, 2, 3) LIMIT 60";
$total_count_result = $mysqli->query($total_count_query);
$total_count = $total_count_result->fetch_assoc()['total'];
$total_count = min($total_count, 60);
$total_pages = ceil($total_count / $limit);

$sentences_query = "SELECT * FROM sentences WHERE language_id IN (1, 2, 3) ORDER BY 
                    CAST(SUBSTRING_INDEX(sentence_id, '_', -1) AS UNSIGNED), language_id LIMIT ? OFFSET ?";

$stmt = $mysqli->prepare($sentences_query);
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$sentences_result = $stmt->get_result();

$sentences_data = [];

while ($row = mysqli_fetch_assoc($sentences_result)) {
    $validation_query = "SELECT status FROM validated_sentences WHERE sentence_id = ?";
    $validation_stmt = $mysqli->prepare($validation_query);
    $validation_stmt->bind_param('s', $row['sentence_id']);
    $validation_stmt->execute();
    $validation_result = $validation_stmt->get_result();

    if ($validation_result->num_rows > 0) {
        $validation_row = $validation_result->fetch_assoc();
        $row['validation_status'] = $validation_row['status'];
    } else {
        $row['validation_status'] = 'not validated';
    }

    $sentences_data[] = $row;
}

$response = [
    'sentences' => $sentences_data,
    'total_pages' => $total_pages,
    'current_page' => $page,
    'total_sentences' => $total_count
];

header('Content-Type: application/json');
echo json_encode($response);
?>