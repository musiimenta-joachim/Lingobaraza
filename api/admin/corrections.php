<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

$user_id = $_SESSION["user_id"];
$mysqli = Database::getInstance();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total_count_query = "SELECT COUNT(*) as total FROM corrections";
$total_count_result = $mysqli->query($total_count_query);
$total_count = $total_count_result->fetch_assoc()['total'];
$total_pages = ceil($total_count / $limit);

$corrections_query = "SELECT c.correction_id,c.correction,c.date,c.expert_id,u.user_name AS expert_username,c.sentence_id,s.sentence AS original_sentence,s.language_id
                    FROM corrections c JOIN sentences s ON c.sentence_id = s.sentence_id LEFT JOIN users u ON c.expert_id = u.user_id
                    LIMIT ? OFFSET ?";

$stmt = $mysqli->prepare($corrections_query);
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$corrections_result = $stmt->get_result();

$corrections_data = [];

while ($correction_row = $corrections_result->fetch_assoc()) {
    $vote_count_query = "SELECT `status`, COUNT(*) as count FROM votes WHERE correction_id = ? GROUP BY status";
    
    $vote_stmt = $mysqli->prepare($vote_count_query);
    $vote_stmt->bind_param('i', $correction_row['correction_id']);
    $vote_stmt->execute();
    $vote_result = $vote_stmt->get_result();
    
    $vote_counts = [
        'supporting' => 0,
        'not_sure' => 0,
        'not_supporting' => 0
    ];
    
    while ($vote_row = $vote_result->fetch_assoc()) {
        $vote_counts[$vote_row['status']] = intval($vote_row['count']);
    }
    
    $correction_row['vote_counts'] = $vote_counts;
    
    $corrections_data[] = $correction_row;
}

$response = [
    'corrections' => $corrections_data,
    'total_pages' => $total_pages,
    'current_page' => $page,
    'total_corrections' => $total_count
];

header('Content-Type: application/json');
echo json_encode($response);
?>