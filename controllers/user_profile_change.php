<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/dnConnect.php';
$response = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST['tag']) && $_POST['tag'] === 'tag')
        {
            if 
            (
                (!empty($_POST['first_name']))
                && (!empty($_POST['last_name']))
                && (!empty($_POST['email']))
                && (!empty($_POST['phone_number']))
                && (!empty($_POST['gender']))
                &&(isset($_POST['user_id']) && (!empty($_POST['user_id'])))

                // && (!empty($_POST['old_password']))
                // && (!empty($_POST['updated_password']))
            )
            {
                $user_id = $_POST['user_id'];
                $first_name = $_POST['first_name'];
                $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : ' ';
                $last_name = $_POST['last_name'];
                $email = $_POST['email'];
                $gender = $_POST['gender'];
                $phone_number = $_POST['phone_number'];


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
                if (!preg_match("/^[a-zA-Z]+$/", $middle_name)) {
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

                // check for email and phone number is not in other user data 
                $check_duplicate_query = "SELECT user_id FROM `usermaster` WHERE (user_phone_number = ? OR user_email = ?) AND user_id != ?";
                
                $check_duplicate_stmt = mysqli_prepare($conn, $check_duplicate_query);
                
                mysqli_stmt_bind_param($check_duplicate_stmt, "isi", $phone_number, $email, $user_id);
                
                mysqli_stmt_execute($check_duplicate_stmt);
                
                $result = mysqli_stmt_get_result($check_duplicate_stmt);

                if (mysqli_num_rows($result) > 0) {
                    $response['message'] = "This phone number and email combination already exists for another user.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }


                // check for old password and updated password
                if 
                (
                    (isset($_POST['old_password']) && !empty($_POST['old_password'])) 
                    && (isset($_POST['updated_password']) && !empty($_POST['updated_password'])) 

                )
                {
                    $old_password = $_POST['old_password'];
                    $updated_password = $_POST['updated_password'];
                    
                     // old password
                    if (!preg_match("/^[a-zA-Z0-9#@!$%^&*]{8,30}$/", $old_password)) {
                        $response['message'] = "Password must be between 8 and 30 characters long, contain only alphanumeric characters or allowed symbols (#, @, !, $, %, ^, &, *, etc.), and no spaces.";
                        $response['status'] = 201;
                        echo json_encode($response);
                        exit();
                    }

                    // updated password
                    if (!preg_match("/^[a-zA-Z0-9#@!$%^&*]{8,30}$/", $updated_password)) {
                        $response['message'] = "Password must be between 8 and 30 characters long, contain only alphanumeric characters or allowed symbols (#, @, !, $, %, ^, &, *, etc.), and no spaces.";
                        $response['status'] = 201;
                        echo json_encode($response);
                        exit();
                    }

                    $get_password = "SELECT user_password FROM `usermaster` 
                                    WHERE user_id = ?";

                    $get_password_stmt = mysqli_prepare($conn, $get_password);

                    mysqli_stmt_bind_param($get_password_stmt, "i", $user_id);
                    
                    mysqli_stmt_execute($get_password_stmt);

                    $get_password_result = mysqli_stmt_get_result($get_password_stmt);

                    if (mysqli_num_rows($get_password_result) > 0)
                    {
                        // fetch database password
                        $user_data = mysqli_fetch_assoc($get_password_result);
                        $stored_password = $user_data['user_password'];

                        $checkPassword = password_verify($old_password, $stored_password);

                        if ($checkPassword)
                        {
                                $hash_upadated_password = password_hash($updated_password, PASSWORD_DEFAULT);
                                $update_profile_query = "UPDATE `usermaster` 
                                                        SET user_first_name = ?,
                                                        user_middle_name = ?,
                                                        user_last_name = ?,
                                                        user_phone_number = ?,
                                                        user_email = ?,
                                                        user_gender = ?,
                                                        user_password = ?,
                                                        user_updateddate = NOW() 
                                                        WHERE user_id = ?";
                                
                                $update_profile_query_stmt = mysqli_prepare($conn, $update_profile_query);

                                mysqli_stmt_bind_param($update_profile_query_stmt, "sssisssi", $first_name, $middle_name, $last_name, $phone_number, $email, $gender, $hash_upadated_password, $user_id);

                                $update_profile_query_result = mysqli_stmt_execute($update_profile_query_stmt);

                                if ($update_profile_query_result)
                                {
                                    $response['message'] = "User Profile updated successfully with password";
                                    $response['status'] = 200;
                                }
                                else
                                {
                                    $response['message'] = "Error while updating user profile";
                                    $response['status'] = 201;
                                }

                          
                        }
                        else
                        {
                            $response['message'] = "Old password not matched";
                            $response['status'] = 201;
                        }
                    }
                    else
                    {
                        $response['message'] = "No User Found";
                        $response['status'] = 201;
                    }
                }
                else 
                {
                    $update_profile_query = "UPDATE `usermaster` 
                                            SET user_first_name = ?,
                                            user_middle_name = ?,
                                            user_last_name = ?,
                                            user_phone_number = ?,
                                            user_email = ?,
                                            user_gender = ?,
                                            user_updateddate = NOW() 
                                            WHERE user_id = ?";
                    
                    $update_profile_query_stmt = mysqli_prepare($conn, $update_profile_query);

                    mysqli_stmt_bind_param($update_profile_query_stmt, "sssissi", $first_name, $middle_name, $last_name, $phone_number, $email, $gender, $user_id);
                    
                    echo "before";

                    $update_profile_query_result = mysqli_stmt_execute($update_profile_query_stmt);

                    if (!$update_profile_query_result) {
                        // This will print any error if the query fails to execute
                        echo "Error executing query: " . mysqli_stmt_error($update_profile_query_stmt);
                        exit();  // Exit to prevent further execution
                    }

                    echo "after";


                    if ($update_profile_query_result)
                    {
                        $response['message'] = "User profile updated successfully";
                        $response['status'] = 200;
                    }
                    else
                    {
                        $response['message'] = "Error while Updating user data";
                        $response['status'] = 201;
                    }
                }

            }
            else
            {
                $response['message'] = "No empty value accept";
                $response['status'] = 201;
            }
        }
        else
        {
            $response['message'] = "Invalid tag";
            $response['status'] = 201;
        }
    }
    else
    {
        $response['message'] = 'Only post method allow';
        $response['status'] = 201;
    }

    echo json_encode($response);
?>