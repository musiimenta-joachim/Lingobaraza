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
    $mysqli = Database::getInstance();

    $total_duration_stmt = $mysqli->prepare("SELECT COALESCE(COUNT(voice_note_id), 0) as total_user_notes FROM voice_notes WHERE user_id = ?");
    $accepted_duration_stmt = $mysqli->prepare("SELECT COALESCE(COUNT(voice_note_id), 0) as accepted_notes FROM voice_notes WHERE user_id = ? AND status = 'approved'");
    $rejected_duration_stmt = $mysqli->prepare("SELECT COALESCE(COUNT(voice_note_id), 0) as rejected_notes FROM voice_notes WHERE user_id = ? AND status = 'rejected'");
    $all_users_total_stmt = $mysqli->prepare("SELECT COALESCE(COUNT(voice_note_id), 0) as all_user_notes FROM voice_notes");

    $total_duration_stmt->bind_param('i', $user_id);
    $total_duration_stmt->execute();
    $total_duration_result = $total_duration_stmt->get_result();
    $total_duration = $total_duration_result->fetch_assoc()['total_user_notes'];

    $accepted_duration_stmt->bind_param('i', $user_id);
    $accepted_duration_stmt->execute();
    $accepted_duration_result = $accepted_duration_stmt->get_result();
    $accepted_duration = $accepted_duration_result->fetch_assoc()['accepted_notes'];

    $rejected_duration_stmt->bind_param('i', $user_id);
    $rejected_duration_stmt->execute();
    $rejected_duration_result = $rejected_duration_stmt->get_result();
    $rejected_duration = $rejected_duration_result->fetch_assoc()['rejected_notes'];

    $all_users_total_stmt->execute();
    $all_users_total_result = $all_users_total_stmt->get_result();
    $all_users_total = $all_users_total_result->fetch_assoc()['all_user_notes'];

    $total_percentage = ($total_duration / max($all_users_total, 1)) * 100;
    $accepted_percentage = ($accepted_duration / max($all_users_total, 1)) * 100;
    $rejected_percentage = ($rejected_duration / max($all_users_total, 1)) * 100;

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
            'total_duration' => round($total_duration, 2),
            'accepted_duration' => round($accepted_duration, 2),
            'rejected_duration' => round($rejected_duration, 2),
            'total_percentage' => round($total_percentage, 2),
            'accepted_percentage' => round($accepted_percentage, 2),
            'rejected_percentage' => round($rejected_percentage, 2)
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