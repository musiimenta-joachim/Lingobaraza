<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../shared/db_connect.php';
require_once '../shared/verify_status.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

try {
    $user_id = $_SESSION["user_id"];
    $mysqli = Database::getInstance();
    
    $query = "SELECT s.sentence_id, s.sentence, vs.validation_id, vs.status AS validation_status, 
                     vn.voice_note_id, vn.voice_note_path, vn.status AS voice_note_status
              FROM sentences s
              LEFT JOIN validated_sentences vs 
                  ON s.sentence_id = vs.sentence_id
              LEFT JOIN voice_notes vn 
                  ON s.sentence_id = vn.sentence_id AND vn.user_id = ?
              WHERE s.user_id = ? OR vn.user_id = ?";
    
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $mysqli->error);
    }
    
    $stmt->bind_param('iii', $user_id, $user_id, $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $data = [];
    
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['voice_note_path'])) {
            $filename = basename($row['voice_note_path']);
            $row['voice_note_path'] = $filename;
            error_log("Audio file path set to: " . $filename);
        }
        $data[] = $row;
    }
    
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    echo json_encode($data, JSON_THROW_ON_ERROR);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode([
        "error" => "Internal server error",
        "message" => $e->getMessage()
    ]);
}
