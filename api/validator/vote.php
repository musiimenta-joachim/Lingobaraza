<?php
header('Content-Type: application/json');

try {
    require_once '../shared/db_connect.php';
    require_once '../shared/verify_status.php';
} catch (Exception $e) {
    error_log("Configuration Error: " . $e->getMessage());
    echo json_encode(['error' => 'Configuration error']);
    exit;
}

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $mysqli = Database::getInstance();

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['correction_id']) || !isset($input['vote'])) {
        throw new Exception("Missing required vote parameters");
    }

    $correction_id = $input['correction_id'];
    $vote_type = $input['vote'];

    $insertQuery = "INSERT INTO votes (`status`, correction_id, user_id) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($insertQuery);

    $stmt->bind_param('ssi', $vote_type, $correction_id, $user_id);

    if (!$stmt->execute()) {
        if ($mysqli->errno == 1062) {
            echo json_encode(['error' => 'You have already voted on this correction']);
            exit;
        }
        throw new Exception("Vote insertion failed: " . $stmt->error);
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Vote recorded successfully',
        'vote_type' => $vote_type
    ]);

} catch (Exception $e) {
    error_log("Vote Error: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());

    echo json_encode([
        'error' => 'Vote processing error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>