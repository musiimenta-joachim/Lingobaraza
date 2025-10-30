<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../shared/db_connect.php'; 
require '../shared/verify_status.php';  

$voice_note_id = isset($_GET['id']) ? $_GET['id'] : 0;  

try {
    $mysqli = Database::getInstance();
    
    $query = "SELECT vn.voice_note_id, vn.voice_note_path, vn.status, vn.recording_date AS recording_date, 
              u.user_id AS submitter_id, u.user_name AS submitter_name, 
              s.sentence_id, s.sentence AS original_sentence, 
              vn.validation_date AS validated_date, vn.validator, 
              expert.user_name AS validator_name 
              FROM voice_notes vn
              JOIN users u ON vn.user_id = u.user_id 
              JOIN sentences s ON vn.sentence_id = s.sentence_id 
              LEFT JOIN validated_sentences vs ON s.sentence_id = vs.sentence_id     
              LEFT JOIN users expert ON vs.expert_id = expert.user_id 
              WHERE vn.voice_note_id = ?";
    
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }
    
    $stmt->bind_param('s', $voice_note_id);
    $stmt->execute();
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($recording = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode($recording);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Recording not found', 'voice_note_id' => $voice_note_id]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($mysqli)) $mysqli->close();
}
?>