<?php
ini_set('display_errors', 0);
error_reporting(0);

require '../shared/db_connect.php';
require '../shared/verify_status.php';

function handleDatabaseError($mysqli) {
    return json_encode([
        'error' => 'Database error: ' . $mysqli->error,
        'status' => 'error'
    ]);
}

try {
    $user_id = $_SESSION["user_id"] ?? null;
    
    if (!$user_id) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'User not authenticated', 'status' => 'error']);
        exit;
    }

    $mysqli = Database::getInstance();

    $stats_query = "SELECT 
            COALESCE((SELECT COUNT(voice_note_id) FROM voice_notes WHERE validator = ? AND status = 'approved'), 0) as user_validated_recordings,
            COALESCE((SELECT COUNT(voice_note_id) FROM voice_notes WHERE status = 'approved'), 0) as total_validated_recordings,

            COALESCE((SELECT COUNT(validation_id) FROM validated_sentences WHERE expert_id = ?), 0) as expert_validated_sentences,
            COALESCE((SELECT COUNT(validation_id) FROM validated_sentences), 0) as all_sentences";

    $stmt = $mysqli->prepare($stats_query);
    if (!$stmt) {
        header('Content-Type: application/json');
        echo handleDatabaseError($mysqli);
        exit;
    }

    $stmt->bind_param('ii', $user_id, $user_id);
    $stmt->execute();
    $stats_result = $stmt->get_result();
    $stats = $stats_result->fetch_assoc();

    $all_sentences = max($stats['all_sentences'], 0);
    $total_validated_recordings = $stats['total_validated_recordings'];
    $user_validated_recordings = $stats['user_validated_recordings'];
    $expert_validated_sentences = $stats['expert_validated_sentences'];

    $project_query = "SELECT * FROM about_project";
    $project_result = $mysqli->query($project_query);
    if (!$project_result) {
        header('Content-Type: application/json');
        echo handleDatabaseError($mysqli);
        exit;
    }
    $project_details = $project_result->fetch_assoc();

    $admin_query = "SELECT u.user_id, u.email, u.user_name, ap.details, u.profile_picture 
        FROM users u JOIN about_admin ap ON u.user_id = ap.admin_id WHERE u.acc_type = 'admin' AND u.user_id != 3";
    $admin_result = $mysqli->query($admin_query);
    if (!$admin_result) {
        header('Content-Type: application/json');
        echo handleDatabaseError($mysqli);
        exit;
    }
    
    $admins = [];
    while ($row = $admin_result->fetch_assoc()) {
        $admins[] = $row;
    }

    $response = [
        'status' => 'success',
        'statistics' => [
            'expert_validated_sentences' => round($stats['expert_validated_sentences'], 2),
            'user_validated_recordings' => round($stats['user_validated_recordings'], 2),
            'total_validated_recordings' => round($stats['total_validated_recordings'], 2),
            'all_sentences' => round($all_sentences, 2),
        ],
        'project_details' => $project_details ?? null,
        'admins' => $admins
    ];

    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Server error: ' . $e->getMessage(),
        'status' => 'error'
    ]);
}