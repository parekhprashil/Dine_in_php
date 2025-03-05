<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/dnConnect.php';
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tag']) && $_POST['tag'] === 'tag') {
        if (
            (isset($_POST['first_name']) && !empty($_POST['first_name']))
            && (isset($_POST['last_name']) && !empty($_POST['last_name']))
            && (isset($_POST['email']) && !empty($_POST['email']))
            && (isset($_POST['phone_number']) && !empty($_POST['phone_number']))
            && (isset($_POST['gender']) && !empty($_POST['gender']))
            && (isset($_POST['password']) && !empty($_POST['password']))
        ) {
            $first_name = $_POST['first_name'];
            $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : ' ';
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $gender = $_POST['gender'];
            $password = $_POST['password'];
            $user_isdelete = 0;
            // $user_image = isset($_POST['user_image']) && !empty($_POST['user_image']) ? $_POST['user_image'] : NULL;

            // first name
            if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
                $response['message'] = "First name must contain only letters and no space.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }
            if (strlen($first_name) < 3) {
                $response['message'] = "First name must be at-least 3 character.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // middle name
            if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
                $response['message'] = "middle name must contain only letters and no space.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // last name
            if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
                $response['message'] = "Last name must contain only letters and no space.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }
            if (strlen($last_name) < 3) {
                $response['message'] = "Last name must be at least 3 character.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['message'] = "Invalid email formate";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // phone number
            if (!preg_match("/^[0-9]{10}$/", $phone_number)) {
                $response['message'] = "Phone number must contain only 10 numbers";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // gender
            $valid_genders = ["Male", "Female", "Other"];
            if (!in_array($gender, $valid_genders)) {
                $response['message'] = "Gender must be 'Male', 'Female', or 'Other'.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // password
            if (!preg_match("/^[a-zA-Z0-9#@!$%^&*]{8,30}$/", $password)) {
                $response['message'] = "Password must be between 8 and 30 characters long, contain only alphanumeric characters or allowed symbols (#, @, !, $, %, ^, &, *, etc.), and no spaces.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // check for user email or phone number already there
            $check_user =   "SELECT * FROM `usermaster` 
                            WHERE user_email = ? OR user_phone_number = ?";

            $check_stmt = mysqli_prepare($conn, $check_user);

            mysqli_stmt_bind_param($check_stmt, "si", $email, $phone_number);
            mysqli_stmt_execute($check_stmt);
            $check_user_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_user_result) > 0) {
                $response['message'] = "This email or phone number already present";
                $response['status'] = 201;
                echo json_encode($response);    
                exit();
            }

            // if user not in database then enter data int database
            $user_registration = "INSERT INTO `usermaster`(user_first_name, user_middle_name, user_last_name, user_phone_number, user_email, user_gender, user_password, user_isdelete) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $user_registration_stmt = mysqli_prepare($conn, $user_registration);

            mysqli_stmt_bind_param($user_registration_stmt, "sssisssi", $first_name, $middle_name, $last_name, $phone_number, $email, $gender, $hashed_password, $user_isdelete);

            $user_registration_result = mysqli_stmt_execute($user_registration_stmt);

            if ($user_registration_result) {
                $response['message'] = "Data insrted successfully";
                $response['status'] = 200;
            } else {
                $response['message'] = "Error while inserting data" . mysqli_error($conn);
                $response['status'] = 201;
            }
        } else {
            $response['message'] = "All fields required";
            $response['status'] = 201;
        }
    } else {
        $response['mesaage'] = 'Invalid Tag';
        $response['status'] = 201;
    }
} else {
    $response['message'] = 'Only Post method allow';
    $response['status'] = 201;
}

echo json_encode($response);
// mysqli_stmt_close($stmt);



?>