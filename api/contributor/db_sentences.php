<?php
header('Content-Type: application/json');

try {
    require '../shared/db_connect.php';
    require '../shared/verify_status.php';

    if (!isset($_SESSION["user_id"])) {
        throw new Exception('User not authenticated');
    }

    $user_id = $_SESSION["user_id"];
    $mysqli = Database::getInstance();

    $query = "SELECT DISTINCT vs.*, s.sentence 
    FROM validated_sentences vs 
    JOIN sentences s ON vs.sentence_id = s.sentence_id 
    LEFT JOIN voice_notes vn ON s.sentence_id = vn.sentence_id AND vn.user_id = ?
    JOIN users u ON u.user_id = ? 
    WHERE vs.status = 'approved' 
    AND (
        vn.sentence_id IS NULL 
        OR vn.status = 'rejected' 
        OR (vn.status = 'approved' AND vn.user_id != ?)
    )
    AND JSON_CONTAINS(
        u.preferred_languages,
        JSON_QUOTE(
            CASE SUBSTRING_INDEX(SUBSTRING_INDEX(s.sentence_id, '_', 2), '_', -1)
                WHEN '1' THEN 'English'
                WHEN '2' THEN 'Luganda'
                WHEN '3' THEN 'Runyankole'
            END
        )
    )
    ORDER BY vs.validation_id DESC 
    LIMIT 5";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception('Query preparation failed: ' . $mysqli->error);
    }

    $stmt->bind_param('iii', $user_id, $user_id, $user_id);

    if (!$stmt->execute()) {
        throw new Exception('Query execution failed: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception('No results from query: ' . $mysqli->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'sentence_id' => $row['sentence_id'],
            'sentence' => $row['sentence']
        ];
    }

    if (empty($data)) {
        echo json_encode([
            'status' => 'empty',
            'message' => 'No sentences found for your language preferences and fluency levels',
            'data' => []
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}