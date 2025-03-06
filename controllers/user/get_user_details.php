<?php

// for edit profile view
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/dnConnect.php';
$response = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST['tag']) && $_POST['tag'] === 'tag')
        {
            if (isset($_POST['user_id']) && !empty($_POST['user_id']))
            {
                $user_id =$_POST['user_id'];
                $show_user_data = "SELECT user_first_name, user_middle_name, user_last_name, user_phone_number, user_email, user_gender FROM `usermaster` WHERE user_id = ?";
                
                $show_user_data_stmt = mysqli_prepare($conn, $show_user_data);

                mysqli_stmt_bind_param($show_user_data_stmt, "i", $user_id);

                mysqli_stmt_execute($show_user_data_stmt);

                $show_user_data_result = mysqli_stmt_get_result($show_user_data_stmt);

                if ($show_user_data_result)
                {
                    $user_details = mysqli_fetch_assoc($show_user_data_result);
                    echo "$user_details";

                    $response['user'] = $user_details;
                    $response['success'] = true;
                    
                }
                else
                {
                    $response['message'] = "no user found";
                    $response['satus'] = 201;   
                }
            }
            else
            {
                $responsep['message'] = "All fields required";
                $response['status'] = 201;
            }
        }
        else
        {
            $responsep['message'] = "Invalid Tag";
            $response['status'] = 201;
        }
    }
    else
    {
        $responsep['message'] = "Only post method allow";
        $response['status'] = 201;
    }
    echo json_encode($response);
?>