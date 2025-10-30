<?php 
require '../shared/db_connect.php'; 
require '../shared/verify_status.php';  

$user_id = $_SESSION["user_id"]; 
$mysqli = Database::getInstance();  

$page = isset($_GET['page']) ? intval($_GET['page']) : 1; 
$limit = 10; 
$offset = ($page - 1) * $limit;  

$total_count_query = "SELECT COUNT(*) as total FROM voice_notes WHERE status = 'approved'"; 
$total_count_result = $mysqli->query($total_count_query); 
$total_count = $total_count_result->fetch_assoc()['total']; 
$total_pages = ceil($total_count / $limit);  

$voice_notes_query = "SELECT  vn.voice_note_id, vn.voice_note_path, vn.note_duration, vn.status, vn.validation_date, vn.user_id, vn.sentence_id, s.sentence AS original_sentence, u.user_name AS username
                    FROM voice_notes vn JOIN sentences s ON vn.sentence_id = s.sentence_id JOIN users u ON vn.user_id = u.user_id 
                    WHERE vn.status = 'approved'LIMIT ? OFFSET ?";  

$stmt = $mysqli->prepare($voice_notes_query); 
$stmt->bind_param('ii', $limit, $offset); 
$stmt->execute(); 
$voice_notes_result = $stmt->get_result();  

$voice_notes_data = [];  

while ($voice_note_row = $voice_notes_result->fetch_assoc()) {
    $voice_notes_data[] = $voice_note_row; 
}  

$response = [     
    'voice_notes' => $voice_notes_data,     
    'total_pages' => $total_pages,     
    'current_page' => $page,     
    'total_voice_notes' => $total_count 
];  

header('Content-Type: application/json'); 
echo json_encode($response); 
?>