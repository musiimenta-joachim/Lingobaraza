<?php
header('Content-Type: application/json');
require_once '../shared/db_connect.php';
require '../shared/verify_status.php';
ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');

ob_start();
$response = ['success' => false, 'error' => 'Unknown error'];

try {
    if (!isset($_FILES['sentence_data'])) {
        throw new Exception('No file uploaded.');
    }

    $file = $_FILES['sentence_data'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $file['error']);
    }

    $MAX_OBJECTS = 2000;
    $languages = [
        'English' => 1,
        'Luganda' => 2,
        'Runyankole' => 3
    ];

    function fileGenerator($filename, $maxObjects) {
        $handle = fopen($filename, 'r');
        if (!$handle) {
            throw new Exception('Unable to open file');
        }

        $objectCount = 0;
        while (($line = fgets($handle)) !== false && $objectCount < $maxObjects) {
            $line = trim($line);
            if (!empty($line)) {
                yield $line;
                $objectCount++;
            }
        }
        fclose($handle);
    }

    $mysqli = Database::getInstance();

    $last_id_stmt = $mysqli->prepare("SELECT language_id, MAX(CAST(SUBSTRING_INDEX(sentence_id, '_', -1) AS UNSIGNED)) AS last_number FROM sentences WHERE language_id = ? GROUP BY language_id");
    
    $check_stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM sentences WHERE sentence = ? AND language_id = ?");
    
    $insert_stmt = $mysqli->prepare("INSERT INTO sentences (sentence_id, sentence, `date`, language_id, user_id) VALUES (?, ?, NOW(), ?, ?)");

    $user_id = $_SESSION['user_id'];
    $successful_inserts = 0;
    $total_sentences = 0;
    $skipped_duplicates = 0;

    $id_counters = [1 => 0, 2 => 0, 3 => 0];

    foreach ($languages as $language => $language_id) {
        $last_id_stmt->bind_param("i", $language_id);
        $last_id_stmt->execute();
        $result = $last_id_stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row && $row['last_number'] !== null) {
            $id_counters[$language_id] = $row['last_number'];
        }
    }

    foreach (fileGenerator($file['tmp_name'], $MAX_OBJECTS) as $line) {
        $parsedLine = json_decode($line, true);
        if ($parsedLine !== null) {
            foreach ($parsedLine as $language => $sentence) {
                $total_sentences++;
                if (array_key_exists($language, $languages)) {
                    $language_id = $languages[$language];
                    
                    $check_stmt->bind_param("si", $sentence, $language_id);
                    $check_stmt->execute();
                    $result = $check_stmt->get_result();
                    $existing_count = $result->fetch_assoc()['count'];

                    if ($existing_count > 0) {
                        $skipped_duplicates++;
                        continue;
                    }

                    $id_counters[$language_id]++;

                    $sentence_id = sprintf('sent_%d_%05d', $language_id, $id_counters[$language_id]);

                    if ($insert_stmt->bind_param("ssii", $sentence_id, $sentence, $language_id, $user_id)) {
                        if ($insert_stmt->execute()) {
                            $successful_inserts++;
                        }
                    }
                }
            }
        }
    }

    $last_id_stmt->close();
    $check_stmt->close();
    $insert_stmt->close();
    mysqli_close($mysqli);

    $response = [
        'success' => true,
        'message' => "Processed $MAX_OBJECTS objects. Successfully inserted $successful_inserts sentences. Skipped $skipped_duplicates duplicate sentences.",
        'total_sentences' => $total_sentences,
        'successful_inserts' => $successful_inserts,
        'skipped_duplicates' => $skipped_duplicates
    ];
} catch (Exception $e) {
    error_log($e->getMessage());
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

ob_end_clean();
echo json_encode($response);
exit;
?>