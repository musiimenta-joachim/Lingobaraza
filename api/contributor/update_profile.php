<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

class ProfileUpdateHandler {
    private $mysqli;
    private $user_id;
    private $response;
    private $existing_data;

    public function __construct() {
        $this->mysqli = Database::getInstance();
        $this->user_id = $_SESSION["user_id"];
        $this->response = array();
        $this->loadExistingData();
    }

    private function loadExistingData() {
        $query = "SELECT preferred_languages, level_of_fluency, password FROM users WHERE user_id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->existing_data = $result->fetch_assoc();
        $stmt->close();
    }

    private function getPreferredLanguages() {
        $languages = array();
        
        if (isset($_POST['languages']) && is_array($_POST['languages'])) {
            $languages = $_POST['languages'];
        } else {
            $possible_languages = ['English', 'Luganda', 'Runyankole'];
            foreach ($possible_languages as $lang) {
                if (isset($_POST["lang-".strtolower($lang)]) && $_POST["lang-".strtolower($lang)] === 'true') {
                    $languages[] = $lang;
                }
            }
        }
        
        if (empty($languages) && !empty($this->existing_data['preferred_languages'])) {
            return $this->existing_data['preferred_languages'];
        }
        
        return json_encode($languages);
    }

    private function getFluencyLevels() {
        $fluency_levels = array();
        
        for ($i = 1; $i <= 3; $i++) {
            $lang_key = "lang-$i";
            $level_key = "lang-$i-level";
            
            if (!empty($_POST[$lang_key]) && !empty($_POST[$level_key])) {
                $lang = filter_input(INPUT_POST, $lang_key);
                $level = filter_input(INPUT_POST, $level_key);
                $fluency_levels[$lang] = $level;
            }
        }
        
        if (empty($fluency_levels) && !empty($this->existing_data['level_of_fluency'])) {
            return $this->existing_data['level_of_fluency'];
        }
        
        return json_encode($fluency_levels);
    }

    public function handleUpdate() {
        try {
            $name = filter_input(INPUT_POST, 'name');
            $address = filter_input(INPUT_POST, 'address');
            $main_contact = filter_input(INPUT_POST, 'contact-1');
            $alt_contact = filter_input(INPUT_POST, 'contact-2');
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');
            $gender = filter_input(INPUT_POST, 'gender');
            $age = filter_input(INPUT_POST, 'age');

            $preferred_languages_json = $this->getPreferredLanguages();
            $fluency_levels_json = $this->getFluencyLevels();

            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $query = "UPDATE users 
                         SET user_name = ?,
                             `address` = ?,
                             main_contact = ?,
                             alt_contact = ?,
                             email = ?,
                             gender = ?,
                             age = ?,
                             preferred_languages = ?,
                             level_of_fluency = ?,
                             password = ?
                         WHERE user_id = ?";

                $stmt = $this->mysqli->prepare($query);
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . $this->mysqli->error);
                }

                $stmt->bind_param('ssssssssssi',
                    $name,
                    $address,
                    $main_contact,
                    $alt_contact,
                    $email,
                    $gender,
                    $age,
                    $preferred_languages_json,
                    $fluency_levels_json,
                    $hashed_password,
                    $this->user_id
                );
            } else {
                $query = "UPDATE users 
                         SET user_name = ?,
                             `address` = ?,
                             main_contact = ?,
                             alt_contact = ?,
                             email = ?,
                             gender = ?,
                             age = ?,
                             preferred_languages = ?,
                             level_of_fluency = ?
                         WHERE user_id = ?";

                $stmt = $this->mysqli->prepare($query);
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . $this->mysqli->error);
                }

                $stmt->bind_param('sssssssssi',
                    $name,
                    $address,
                    $main_contact,
                    $alt_contact,
                    $email,
                    $gender,
                    $age,
                    $preferred_languages_json,
                    $fluency_levels_json,
                    $this->user_id
                );
            }

            if ($stmt->execute()) {
                $this->response = [
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => [
                        'name' => $name,
                        'address' => $address,
                        'main_contact' => $main_contact,
                        'alt_contact' => $alt_contact,
                        'email' => $email,
                        'gender' => $gender,
                        'age' => $age,
                        'preferred_languages' => json_decode($preferred_languages_json),
                        'fluency_levels' => json_decode($fluency_levels_json)
                    ]
                ];
            } else {
                throw new Exception('Failed to update profile: ' . $stmt->error);
            }

            $stmt->close();

        } catch (Exception $e) {
            $this->response = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }

        return $this->response;
    }
}

$handler = new ProfileUpdateHandler();
header('Content-Type: application/json');
echo json_encode($handler->handleUpdate());