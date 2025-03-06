<?php

use function PHPSTORM_META\map;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../owner/owner_registration.php';
$response = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if ((isset($_POST['tag'])) && ($_POST['tag'] === 'owner_registration'))
        {
            if 
            (
                (isset($_POST['owner_first_name']) && !empty($_POST['owner_first_name']))
                && (isset($_POST['owner_last_name']) && !empty($_POST['owner_last_name']))
                && (isset($_POST['owner_phone_number']) && !empty($_POST['owner_phone_number']))
                && (isset($_POST['owner_email']) && !empty($_POST['owner_email']))
                && (isset($_POST['owner_gender']) && !empty($_POST['owner_gender']))
                // && (isset($_POST['owner_image']) && !empty($_POST['owner_image']))
            )
            {
                $owner_first_name = $_POST['owner_first_name'];
                $owner_last_name = $_POST['owner_last_name'];
                $owner_phone_number = $_POST['owner_phone_number'];
                // $owner_alternative_phone_number	= isset($_POST['owner_alternative_phone_number']) ? 
                // $_POST['owner_alternative_phone_number'] : '';
                $owner_email = $_POST['owner_email'];
                // $owner_alternate_email_address = isset($_POST['owner_alternate_email_address']) ? 
                // $_POST['owner_alternate_email_address'] : '';
                $owner_gender = $_POST['owner_gender'];


                // validation

                // first name
                if (!preg_match("/^[a-zA-Z]+$/", $owner_first_name)) {
                    $response['message'] = "First name must contain only letters and no space.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }
                if (strlen($owner_first_name) < 3) {
                    $response['message'] = "First name must be at-least 3 character.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                // last name
                if (!preg_match("/^[a-zA-Z]+$/", $owner_last_name)) {
                    $response['message'] = "Last name must contain only letters and no space.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }
                if (strlen($owner_last_name) < 3) {
                    $response['message'] = "Last name must be at-least 3 character.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                // phone number
                if (!preg_match("/^[0-9]{10}$/", $owner_phone_number)) {
                    $response['message'] = "Phone number must contain only 10 numbers";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                // for another number
                if (isset($_POST['owner_alternative_phone_number']) && !empty($_POST['owner_alternative_phone_number']))
                {
                    // phone number
                    if (!preg_match("/^[0-9]{10}$/", $owner_phone_number)) {
                        $response['message'] = "Phone number must contain only 10 numbers";
                        $response['status'] = 201;
                        echo json_encode($response);
                        exit();
                    }

                    $owner_alternative_phone_number = $_POST['owner_alternative_phone_number'];
                }
                else
                {
                    $owner_alternate_email_address = NULL;
                }

                // for another email
                if (isset($_POST['owner_alternate_email_address']) && !empty($_POST['owner_alternate_email_address']))
                {
                    $owner_alternate_email_address = $_POST['owner_alternate_email_address'];
                }
                else
                {
                    $owner_alternate_email_address = NULL;
                }

                $check_owner =  "SELECT * FROM `owner_master` 
                                WHERE owner_email_address = ? OR owner_phone_number = ?";

                $check_owner_stmt = mysqli_prepare($conn, $check_owner);

                mysqli_stmt_bind_param($check_owner_stmt, "si", $owner_email, $owner_phone_number);

                mysqli_execute($check_owner_stmt);

                $check_owner_result = mysqli_stmt_get_result($check_owner_stmt);                

                if (mysqli_num_rows($check_owner_result) > 0)
                {
                    $response['message'] = "Email or Password alread present";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                // insert user data
                $owner_registration = "INSERT INTO `owner_master`(owner_first_name, owner_last_name, owner_phone_number, owner_alternative_phone_number, owner_email_address, owner_alternate_email_address, owner_gender)
                VALUES(?, ?, ?, ?, ?, ?, ?)";

                $owner_registration_stmt = mysqli_prepare($conn, $owner_registration);

                mysqli_stmt_bind_param($owner_registration_stmt, "ssiisss", $owner_first_name, $owner_last_name, $owner_phone_number, $owner_alternative_phone_number, $owner_email, $owner_alternate_email_address, $owner_gender);

                $owner_registration_result = mysqli_stmt_execute($owner_registration_stmt);

                if ($owner_registration_result)
                {
                    $response['message'] = "Owner data inserted successfull";
                    $response['status'] = 200;
                }
                else
                {
                    $response['message'] = "Error while inserting owner details";
                    $response['status'] = 201;
                }
                
            }
            else
            {
                $response['message'] = "All fields required";
                $response['status'] = 201;
            }
        }
        else
        {
            $response['message'] = "Invalid Tag";
            $response['status'] = 201;
        }
    }
    else
    {
        $response['message'] = "Only post method allow";
        $response['status'] = 201;
    }

    echo json_encode($response);

?>