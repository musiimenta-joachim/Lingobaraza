<?php
header('Content-Type: application/json');

include '../shared/db_connect.php';

function validateName($name) {
    return preg_match('/^[A-Za-z\s]+$/', $name);
}

function validateContact($contact) {
    return preg_match('/^(\+?[0-9]{10}|[0-9]{10})$/', $contact);
}

function validateDateOfBirth($dob) {
    $selectedYear = date('Y', strtotime($dob));
    return $selectedYear <= 2007;
}

function validatePassword($password) {
    return strlen($password) >= 4;
}

function isEmailValid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isEmailUnique($email, $mysqli) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] == 0;
}

function isContactUnique($contact, $mysqli, $isMainContact = true) {
    if (empty($contact)) {
        return true;
    }
    
    $column = $isMainContact ? 'main_contact' : 'alt_contact';
    $stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM users WHERE $column = ?");
    $stmt->bind_param('s', $contact);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] == 0;
}

if (isset($_POST['submit'])) {
    $response = [
        'success' => false,
        'errors' => []
    ];

    $mysqli = Database::getInstance();

    $username = trim($_POST['user_name']);
    if (!validateName($username)) {
        $response['errors']['user_name'] = "Name should only contain letters A-Z and spaces";
    }

    $address = trim($_POST['user_address']);
    if (empty($address)) {
        $response['errors']['user_address'] = "Address is required";
    }

    $main_contact = trim($_POST['main_contact']);
    $alt_contact = trim($_POST['alt_contact']);

    if (!validateContact($main_contact)) {
        $response['errors']['main_contact'] = "Contact should be 10 digits or 13 digits with +";
    } elseif (!isContactUnique($main_contact, $mysqli)) {
        $response['errors']['main_contact'] = "This main contact is already registered";
    }

    if (!empty($alt_contact) && !validateContact($alt_contact)) {
        $response['errors']['alt_contact'] = "Contact should be 10 digits or 13 digits with +";
    } elseif (!empty($alt_contact) && !isContactUnique($alt_contact, $mysqli, false)) {
        $response['errors']['alt_contact'] = "This alternative contact is already registered";
    }

    if (!empty($alt_contact) && $main_contact === $alt_contact) {
        $response['errors']['alt_contact'] = "Alternative contact cannot be the same as main contact";
    }

    $age = isset($_POST['age']) ? $_POST['age'] : '';
    if (!empty($age) && !validateDateOfBirth($age)) {
        $response['errors']['age'] = "You must be 18 years and above";
    }

    $email = trim($_POST['email']);
    if (!isEmailValid($email)) {
        $response['errors']['email'] = "Please enter a valid email address";
    } elseif (!isEmailUnique($email, $mysqli)) {
        $response['errors']['email'] = "This email is already registered";
    }
    
    $password = $_POST['password'];
    if (!validatePassword($password)) {
        $response['errors']['password'] = "Password must be at least 4 characters long";
    }

    if (!isset($_POST['languages']) || !is_array($_POST['languages']) || count($_POST['languages']) == 0) {
        $response['errors']['general'] = "Please select at least one preferred language";
    }

    if (!isset($_POST['consent']) || $_POST['consent'] != '1') {
        $response['errors']['consent'] = "You must agree to the consent form";
    }

    if (!empty($response['errors'])) {
        echo json_encode($response);
        $mysqli->close();
        exit;
    }

    try {
        $selected_languages = $_POST['languages'];
        $languages_string = json_encode($selected_languages);
        
        $language_fluency_array = [];
        foreach ($selected_languages as $lang) {
            $level_key = '';
            if ($lang === 'English') $level_key = 'lang-1-level';
            else if ($lang === 'Luganda') $level_key = 'lang-2-level';
            else if ($lang === 'Runyankole') $level_key = 'lang-3-level';
            
            if (!empty($level_key) && isset($_POST[$level_key])) {
                $language_fluency_array[] = [
                    'label' => $lang,
                    'fluency' => $_POST[$level_key]
                ];
            }
        }
        
        $languages_fluency = json_encode($language_fluency_array);

        $gender = $_POST['gender'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user_type = 'contributor';
        $date = date('Y-m-d');
        $consent = isset($_POST['consent']) && $_POST['consent'] == '1' ? 1 : 0;

        $sql = "INSERT INTO users(user_name, main_contact, alt_contact, age, gender, email, `address`, preferred_languages, level_of_fluency, acc_type, `password`, reg_date, consent)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param(
            'ssssssssssssi',
            $username,
            $main_contact,
            $alt_contact,
            $age,
            $gender,
            $email,
            $address,
            $languages_string,
            $languages_fluency,
            $user_type,
            $password_hash,
            $date,
            $consent
        );

        $stmt->execute();

        if ($stmt->error) {
            $response['errors']['general'] = "Database error occurred: " . $stmt->error;
            echo json_encode($response);
            $stmt->close();
            $mysqli->close();
            exit;
        }

        $response['success'] = true;
        $response['redirect'] = './index.html';
        echo json_encode($response);

        $stmt->close();
        $mysqli->close();
    } catch (Exception $e) {
        $response['errors']['general'] = "An unexpected error occurred: " . $e->getMessage();
        echo json_encode($response);
    }
}
?>