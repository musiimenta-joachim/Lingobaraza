<?php
include '../shared/db_connect.php';
require '../shared/verify_status.php';

if (isset($_POST['submit'])) {
    $username = $_POST['name'];
    $address = $_POST['address'];
    $main_contact = $_POST['main_contact']; 
    $alt_contact = $_POST['alt_contact'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $selected_languages = $_POST['languages'];
    $languages_string = json_encode($selected_languages);
    $languages_fluency = json_encode([
        ['label' => $_POST['lang-1'], 'fluency' => $_POST['lang-1-level']],
        ['label' => $_POST['lang-2'], 'fluency' => $_POST['lang-2-level']],
        ['label' => $_POST['lang-3'], 'fluency' => $_POST['lang-3-level']]
    ]); 
    
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];
    $date = date('Y-m-d');
    $consent = 1;
    
    $sql = "INSERT INTO users(user_name,main_contact,alt_contact,age,gender,email,`address`,preferred_languages,level_of_fluency,acc_type,`password`,reg_date,consent) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $mysqli = Database::getInstance();
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
        $password,
        $date,
        $consent
    );
    
    $stmt->execute();

    if ($stmt->error) {
        $string = $stmt->error;
        $search = "Duplicate";
    
        if (strpos($string, $search) !== false) {
            $message = 'Username is already taken.';
            echo $message;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Success!";
        header("Location: ../../admin/adduser.php");
    }    

    $stmt->close();
    mysqli_close($mysqli);
}