<?php
require '../shared/db_connect.php';

$mysqli = Database::getInstance();

function logDebug($message, $data = null, $backtrace = false) {
    $logMessage = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $logMessage .= " - Data: " . json_encode($data, JSON_PRETTY_PRINT);
    }
    if ($backtrace) {
        $logMessage .= "\nBacktrace: " . print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
    }
    error_log($logMessage);
}

function getEligibleValidatorCount($sentence_id, $mysqli) {
    $query = "SELECT COUNT(*) AS eligible_count
              FROM users u
              INNER JOIN sentences s ON JSON_CONTAINS(u.preferred_languages, CAST(s.language_id AS CHAR))
              WHERE s.sentence_id = ? AND u.acc_type = 'validator'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $sentence_id);

    try {
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['eligible_count'];
    } catch (mysqli_sql_exception $e) {
        logDebug("Database error in getEligibleValidatorCount", [
            'sentence_id' => $sentence_id,
            'error' => $e->getMessage()
        ], true);
        return 0;
    } catch (\Exception $e) {
        logDebug("Error in getEligibleValidatorCount", [
            'sentence_id' => $sentence_id,
            'error' => $e->getMessage()
        ], true);
        return 0;
    }
}

function processSentences($mysqli, $page = 1, $per_page = 100, $start_time) {
    $offset = ($page - 1) * $per_page;
    $query = "SELECT s.sentence_id, s.sentence, c.correction_id, c.correction, c.expert_id,
                     (SELECT COUNT(*) FROM votes WHERE correction_id = c.correction_id) AS vote_count
              FROM sentences s
              LEFT JOIN corrections c ON s.sentence_id = c.sentence_id
              LEFT JOIN validated_sentences v ON s.sentence_id = v.sentence_id
              WHERE v.sentence_id IS NULL
              LIMIT ? OFFSET ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $per_page, $offset);

    try {
        $stmt->execute();
        $result = $stmt->get_result();

        $processed_count = 0;
        while ($row = $result->fetch_assoc()) {
            $sentence_id = $row['sentence_id'];
            $correction_id = $row['correction_id'];
            $expert_id = $row['expert_id'];
            $vote_count = $row['vote_count'];

            $eligible_validator_count = getEligibleValidatorCount($sentence_id, $mysqli);

            if ($vote_count > 0.9 * $eligible_validator_count) {
                $vote_status_query = "SELECT status, COUNT(*) AS count 
                                      FROM votes WHERE correction_id = ?
                                      GROUP BY status
                                      ORDER BY count DESC
                                      LIMIT 1";
                $vote_status_stmt = $mysqli->prepare($vote_status_query);
                $vote_status_stmt->bind_param("s", $correction_id);

                try {
                    $vote_status_stmt->execute();
                    $vote_status_result = $vote_status_stmt->get_result();
                    $vote_status_row = $vote_status_result->fetch_assoc();
                    $highest_vote_status = $vote_status_row['status'];

                    $current_time = time();
                    $log_activities = ($current_time >= $start_time);

                    if ($highest_vote_status === 'supporting') {
                        $update_sentence_query = "UPDATE sentences SET sentence = ? WHERE sentence_id = ?";
                        $update_sentence_stmt = $mysqli->prepare($update_sentence_query);
                        $update_sentence_stmt->bind_param("ss", $row['correction'], $row['sentence_id']);

                        try {
                            $update_sentence_stmt->execute();

                            $insert_validated_query = "INSERT INTO validated_sentences (status, date, expert_id, sentence_id) VALUES ('approved', NOW(), ?, ?)";
                            $insert_validated_stmt = $mysqli->prepare($insert_validated_query);
                            $insert_validated_stmt->bind_param("is", $row['expert_id'], $row['sentence_id']);
                            $insert_validated_stmt->execute();

                            if ($log_activities) {
                                logDebug("Sentence validated", [
                                    'sentence_id' => $row['sentence_id'],
                                    'correction' => $row['correction']
                                ]);
                            }
                        } catch (mysqli_sql_exception $e) {
                            if ($log_activities) {
                                logDebug("Database error in update_sentence_query or insert_validated_query", [
                                    'error' => $e->getMessage(),
                                    'sentence_id' => $row['sentence_id']
                                ], true);
                            }
                        }
                    } else {
                        $delete_voice_notes_query = "DELETE FROM voice_notes WHERE sentence_id = ?";
                        $delete_voice_notes_stmt = $mysqli->prepare($delete_voice_notes_query);
                        $delete_voice_notes_stmt->bind_param("s", $row['sentence_id']);

                        try {
                            $delete_voice_notes_stmt->execute();

                            $delete_corrections_query = "DELETE FROM corrections WHERE sentence_id = ?";
                            $delete_corrections_stmt = $mysqli->prepare($delete_corrections_query);
                            $delete_corrections_stmt->bind_param("s", $row['sentence_id']);
                            $delete_corrections_stmt->execute();

                            $delete_votes_query = "DELETE FROM votes WHERE correction_id IN (SELECT correction_id FROM corrections WHERE sentence_id = ?)";
                            $delete_votes_stmt = $mysqli->prepare($delete_votes_query);
                            $delete_votes_stmt->bind_param("s", $row['sentence_id']);
                            $delete_votes_stmt->execute();

                            $delete_sentence_query = "DELETE FROM sentences WHERE sentence_id = ?";
                            $delete_sentence_stmt = $mysqli->prepare($delete_sentence_query);
                            $delete_sentence_stmt->bind_param("s", $row['sentence_id']);
                            $delete_sentence_stmt->execute();

                            if ($log_activities) {
                                logDebug("Sentence and related records deleted", [
                                    'sentence_id' => $row['sentence_id']
                                ]);
                            }
                        } catch (mysqli_sql_exception $e) {
                            if ($log_activities) {
                                logDebug("Database error in deletion process", [
                                    'error' => $e->getMessage(),
                                    'sentence_id' => $row['sentence_id']
                                ], true);
                            }
                        }
                    }
                } catch (mysqli_sql_exception $e) {
                    if ($log_activities) {
                        logDebug("Database error in vote_status_query", [
                            'error' => $e->getMessage(),
                            'correction_id' => $correction_id
                        ], true);
                    }
                } catch (\Exception $e) {
                    if ($log_activities) {
                        logDebug("Error in vote_status_query", [
                            'error' => $e->getMessage(),
                            'correction_id' => $correction_id
                        ], true);
                    }
                }

                $processed_count++;
            }
        }

        if ($log_activities) {
            logDebug("Processed sentences", [
                'page' => $page,
                'processed_count' => $processed_count
            ]);
        }
        return $processed_count;
    } catch (mysqli_sql_exception $e) {
        if ($log_activities) {
            logDebug("Database error in processSentences", [
                'error' => $e->getMessage(),
                'page' => $page
            ], true);
        }
        return 0;
    } catch (\Exception $e) {
        if ($log_activities) {
            logDebug("Error in processSentences", [
                'error' => $e->getMessage(),
                'page' => $page
            ], true);
        }
        return 0;
    }
}

$start_time = time() + 3600;

$page = 1;
$processed_total = 0;
while (true) {
    $current_time = time();
    $log_activities = ($current_time >= $start_time);

    if ($log_activities) {
        logDebug("Starting sentence processing", [
            'page' => $page
        ]);
    }

    $processed_count = processSentences($mysqli, $page, 100, $start_time);
    $processed_total += $processed_count;

    if ($log_activities) {
        logDebug("Sentence processing summary", [
            'page' => $page,
            'processed_count' => $processed_count,
            'total_processed' => $processed_total
        ]);
    }

    $page++;
    sleep(3600);
}