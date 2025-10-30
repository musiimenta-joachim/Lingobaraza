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
    $mysqli = Database::getInstance();

    $system_users_query = "SELECT 
            (SELECT COUNT(*) FROM users WHERE acc_type = 'contributor') AS total_contributors,
            (SELECT COUNT(*) FROM users 
             WHERE acc_type = 'contributor' 
             AND MONTH(last_logged_in) = MONTH(CURRENT_DATE()) 
             AND YEAR(last_logged_in) = YEAR(CURRENT_DATE())) AS active_contributors
    ";
    $system_users_result = $mysqli->query($system_users_query);
    $system_users = $system_users_result ? $system_users_result->fetch_assoc() : null;

    $experts_query = "SELECT 
            (SELECT COUNT(*) FROM users WHERE acc_type = 'validator') AS total_experts,
            (SELECT COUNT(*) FROM users 
             WHERE acc_type = 'validator' 
             AND MONTH(last_logged_in) = MONTH(CURRENT_DATE()) 
             AND YEAR(last_logged_in) = YEAR(CURRENT_DATE())) AS active_experts
    ";
    $experts_result = $mysqli->query($experts_query);
    $experts = $experts_result ? $experts_result->fetch_assoc() : null;

    $sentences_query = "SELECT 
            (SELECT COUNT(*) FROM sentences) AS total_sentences,
            (SELECT COUNT(*) FROM sentences WHERE sentence_id LIKE 'sent_1_%') AS english_sentences
    ";
    $sentences_result = $mysqli->query($sentences_query);
    $sentences = $sentences_result ? $sentences_result->fetch_assoc() : null;

    $verified_sentences_query = "SELECT 
            (SELECT COUNT(*) FROM validated_sentences) AS total_validated,
            (SELECT (COUNT(*) / (SELECT COUNT(*) FROM validated_sentences) * 100) 
             FROM validated_sentences 
             WHERE status = 'rejected') AS rejection_percentage";

    $verified_sentences_result = $mysqli->query($verified_sentences_query);
    $verified_sentences = $verified_sentences_result ? $verified_sentences_result->fetch_assoc() : null;

    $recordings_validation_query = "WITH months AS (
        SELECT 1 AS month UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 
        UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12)
        SELECT 
        months.month,
        COALESCE(COUNT(DISTINCT CASE WHEN MONTH(recording_date) = months.month THEN voice_note_id END), 0) AS recorded_notes,
        COALESCE(COUNT(DISTINCT CASE WHEN MONTH(validation_date) = months.month THEN voice_note_id END), 0) AS validated_notes
        FROM months
        LEFT JOIN voice_notes ON MONTH(recording_date) = months.month OR MONTH(validation_date) = months.month
        GROUP BY months.month ORDER BY months.month";
    $recordings_validation_result = $mysqli->query($recordings_validation_query);
    $recordings_validation = [];
    while ($row = $recordings_validation_result->fetch_assoc()) {
        $recordings_validation[] = $row;
    }

    $most_verified_language_query = "SELECT 
            (SELECT COUNT(*) FROM validated_sentences WHERE sentence_id LIKE 'sent_1_%' AND status = 'approved') AS sen_english,
            (SELECT COUNT(*) FROM voice_notes WHERE sentence_id LIKE 'sent_1_%' AND status = 'approved') AS rec_english,
            (SELECT COUNT(*) FROM validated_sentences WHERE sentence_id LIKE 'sent_2_%' AND status = 'approved') AS sen_luganda,
            (SELECT COUNT(*) FROM voice_notes WHERE sentence_id LIKE 'sent_2_%' AND status = 'approved') AS rec_luganda,
            (SELECT COUNT(*) FROM validated_sentences WHERE sentence_id LIKE 'sent_3_%' AND status = 'approved') AS sen_runyankole,
            (SELECT COUNT(*) FROM voice_notes WHERE sentence_id LIKE 'sent_3_%' AND status = 'approved') AS rec_runyankole
    ";
    $most_verified_language_result = $mysqli->query($most_verified_language_query);
    $most_verified_language = $most_verified_language_result ? $most_verified_language_result->fetch_assoc() : null;

    $sentence_validation_query = "SELECT 
            (SELECT COUNT(*) FROM validated_sentences WHERE status = 'approved') +
            (SELECT COUNT(*) FROM corrections WHERE correction_id NOT IN (SELECT sentence_id FROM validated_sentences)) AS verified_sentences,
            (SELECT COUNT(*) FROM sentences WHERE sentence_id NOT IN (SELECT sentence_id FROM validated_sentences)) +
            (SELECT COUNT(*) FROM validated_sentences WHERE status = 'rejected') AS unverified_sentences
    ";

    
    $sentence_validation_result = $mysqli->query($sentence_validation_query);
    $sentence_validation = $sentence_validation_result ? $sentence_validation_result->fetch_assoc() : null;
    
    
    $user_stats_query = "SELECT u.user_name,u.email,u.acc_type,
    COUNT(DISTINCT CASE 
        WHEN LOWER(TRIM(vs.status)) = 'approved' THEN vs.sentence_id 
    END) AS approved_sentences_count,
    COUNT(DISTINCT CASE 
        WHEN LOWER(TRIM(vs.status)) = 'rejected' THEN vs.sentence_id 
    END) AS rejected_sentences_count,
    COUNT(DISTINCT CASE 
        WHEN LOWER(TRIM(vn.status)) = 'approved' THEN vn.voice_note_id 
    END) AS approved_voice_notes_count,
    COUNT(DISTINCT CASE 
        WHEN LOWER(TRIM(vn.status)) = 'rejected' THEN vn.voice_note_id 
    END) AS rejected_voice_notes_count
    FROM 
        users u
    LEFT JOIN 
        sentences s ON u.user_id = s.user_id
    LEFT JOIN 
        validated_sentences vs ON (
            (u.acc_type = 'contributor' AND s.sentence_id = vs.sentence_id) OR 
            (u.acc_type = 'validator' AND u.user_id = vs.expert_id)
        )
    LEFT JOIN 
        voice_notes vn ON (
            (u.acc_type = 'contributor' AND u.user_id = vn.user_id) OR 
            (u.acc_type = 'validator' AND u.user_id = vn.validator)
        )
    WHERE 
        u.acc_type IN ('contributor', 'validator')
    GROUP BY 
        u.user_name, u.email, u.acc_type
    ORDER BY 
        approved_sentences_count DESC";

    $user_stats_result = $mysqli->query($user_stats_query);
    $user_stats = $user_stats_result ? $user_stats_result->fetch_all(MYSQLI_ASSOC) : null;

    
    $response = [
        'status' => 'success',
        'system_users' => $system_users,
        'experts' => $experts,
        'sentences' => $sentences,
        'verified_sentences' => $verified_sentences,
        'recordings_validation' => $recordings_validation,
        'most_verified_language' => $most_verified_language,
        'sentence_validation' => $sentence_validation,
        'user_stats' => $user_stats
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