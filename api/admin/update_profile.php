<?php
require '../shared/db_connect.php';
require '../shared/verify_status.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1); 

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
        $queries = [
            'users' => "SELECT preferred_languages, level_of_fluency, password FROM users WHERE user_id = ?",
            'about_admin' => "SELECT details FROM about_admin WHERE admin_id = ?",
            'about_project' => "SELECT project_details FROM about_project LIMIT 1"
        ];

        $this->existing_data = [];

        foreach ($queries as $table => $query) {
            $stmt = $this->mysqli->prepare($query);
            
            if ($table !== 'about_project') {
                $stmt->bind_param('i', $this->user_id);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $this->existing_data[$table] = $result->fetch_assoc();
            $stmt->close();
        }
    }

    private function getPreferredLanguages() {
        $languages = array();
        $possible_languages = ['English', 'Luganda', 'Runyankole'];
        
        foreach ($possible_languages as $lang) {
            if (isset($_POST["lang-$lang"]) && $_POST["lang-$lang"] === 'true') {
                $languages[] = $lang;
            }
        }
        
        if (empty($languages) && !empty($this->existing_data['users']['preferred_languages'])) {
            return $this->existing_data['users']['preferred_languages'];
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
                $fluency_levels[] = [
                    'label' => $lang,
                    'fluency' => $level
                ];
            }
        }
        
        if (empty($fluency_levels) && !empty($this->existing_data['users']['level_of_fluency'])) {
            return $this->existing_data['users']['level_of_fluency'];
        }
        
        return json_encode($fluency_levels);
    }

    private function updateUsers($name, $address, $main_contact, $alt_contact, $email, $preferred_languages_json, $fluency_levels_json, $password = null) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "UPDATE users 
                     SET user_name = ?,
                         `address` = ?,
                         main_contact = ?,
                         alt_contact = ?,
                         email = ?,
                         preferred_languages = ?,
                         level_of_fluency = ?,
                         password = ?
                     WHERE user_id = ?";

            $stmt = $this->mysqli->prepare($query);
            if ($stmt === false) {
                throw new Exception('Failed to prepare users update statement: ' . $this->mysqli->error);
            }

            $stmt->bind_param('ssssssssi',
                $name,
                $address,
                $main_contact,
                $alt_contact,
                $email,
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
                         preferred_languages = ?,
                         level_of_fluency = ?
                     WHERE user_id = ?";

            $stmt = $this->mysqli->prepare($query);
            if ($stmt === false) {
                throw new Exception('Failed to prepare users update statement: ' . $this->mysqli->error);
            }

            $stmt->bind_param('sssssssi',
                $name,
                $address,
                $main_contact,
                $alt_contact,
                $email,
                $preferred_languages_json,
                $fluency_levels_json,
                $this->user_id
            );
        }

        if (!$stmt->execute()) {
            throw new Exception('Failed to update users table: ' . $stmt->error);
        }
        $stmt->close();
    }

    private function updateAboutAdmin($about_admin) {
        $check_query = "SELECT COUNT(*) as count FROM about_admin WHERE admin_id = ?";
        $stmt = $this->mysqli->prepare($check_query);
        $stmt->bind_param('i', $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] > 0) {
            $update_query = "UPDATE about_admin SET details = ? WHERE admin_id = ?";
            $stmt = $this->mysqli->prepare($update_query);
            $stmt->bind_param('si', $about_admin, $this->user_id);
        } else {
            $update_query = "INSERT INTO about_admin (admin_id, details) VALUES (?, ?)";
            $stmt = $this->mysqli->prepare($update_query);
            $stmt->bind_param('is', $this->user_id, $about_admin);
        }

        if (!$stmt->execute()) {
            throw new Exception('Failed to update about_admin table: ' . $stmt->error);
        }
        $stmt->close();
    }

    private function updateAboutProject($about_project) {
        $check_query = "SELECT COUNT(*) as count FROM about_project";
        $stmt = $this->mysqli->prepare($check_query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] > 0) {
            $update_query = "UPDATE about_project SET project_details = ?";
            $stmt = $this->mysqli->prepare($update_query);
            $stmt->bind_param('s', $about_project);
        } else {
            $update_query = "INSERT INTO about_project (project_details) VALUES (?)";
            $stmt = $this->mysqli->prepare($update_query);
            $stmt->bind_param('s', $about_project);
        }

        if (!$stmt->execute()) {
            throw new Exception('Failed to update about_project table: ' . $stmt->error);
        }
        $stmt->close();
    }

    public function handleUpdate() {
        $this->mysqli->begin_transaction();

        try {
            $name = filter_input(INPUT_POST, 'name');
            $address = filter_input(INPUT_POST, 'address');
            $main_contact = filter_input(INPUT_POST, 'contact-1');
            $alt_contact = filter_input(INPUT_POST, 'contact-2');
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $about_admin = filter_input(INPUT_POST, 'about-admin');
            $about_project = filter_input(INPUT_POST, 'about-project');
            $password = filter_input(INPUT_POST, 'password');

            $preferred_languages_json = $this->getPreferredLanguages();
            $fluency_levels_json = $this->getFluencyLevels();

            $this->updateUsers(
                $name, 
                $address, 
                $main_contact, 
                $alt_contact, 
                $email, 
                $preferred_languages_json, 
                $fluency_levels_json,
                $password
            );

            $this->updateAboutAdmin($about_admin);
            $this->updateAboutProject($about_project);

            $this->mysqli->commit();

            $this->response = [
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'name' => $name,
                    'address' => $address,
                    'main_contact' => $main_contact,
                    'alt_contact' => $alt_contact,
                    'email' => $email,
                    'about_admin' => $about_admin,
                    'about_project' => $about_project,
                    'preferred_languages' => json_decode($preferred_languages_json),
                    'fluency_levels' => json_decode($fluency_levels_json)
                ]
            ];

            error_log('POST Data: ' . print_r($_POST, true));
            
        } catch (Exception $e) {
            $this->mysqli->rollback();
            error_log('Profile Update Error: ' . $e->getMessage());
            error_log('Error Trace: ' . $e->getTraceAsString());

            return [
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error_details' => 'Internal server error'
            ];
        }

        return $this->response;
    }
}

$handler = new ProfileUpdateHandler();
header('Content-Type: application/json');
echo json_encode($handler->handleUpdate());