<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/dnConnect.php';
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hotel_dine_in']) && $_POST['hotel_dine_in'] === 'update') {
        if (
            !empty($_POST['first_name']) &&
            !empty($_POST['last_name']) &&
            !empty($_POST['email']) &&
            !empty($_POST['phone_number']) &&
            !empty($_POST['gender']) &&
            isset($_POST['user_id']) && !empty($_POST['user_id'])
        ) {
            $user_id = $_POST['user_id'];
            $first_name = $_POST['first_name'];
            $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : ' ';
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
            $phone_number = $_POST['phone_number'];

            // Image Upload Handling
            $image_name = null;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['profile_image'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                
                if (!in_array($image['type'], $allowed_types)) {
                    $response['message'] = "Invalid image type. Only JPG, JPEG, and PNG are allowed.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                if ($image['size'] > 10 * 1024 * 1024) {
                    $response['message'] = "Image size must be less than 2MB.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                $upload_dir = '../uploads/profile/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $random_number = rand(1000, 9999);
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $formatted_first_name = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($first_name));
                $image_name = $formatted_first_name . "_" . $random_number . "." . $ext;
                $image_path = $upload_dir . $image_name;

                if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                    $response['message'] = "Failed to upload image.";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }
            }

            // Update user profile query
            $update_profile_query = "UPDATE usermaster 
                                     SET user_first_name = ?, 
                                         user_middle_name = ?, 
                                         user_last_name = ?, 
                                         user_phone_number = ?, 
                                         user_email = ?, 
                                         user_gender = ?, 
                                         user_updateddate = NOW()";

            if ($image_name) {
                $update_profile_query .= ", user_image = ?";
            }
            $update_profile_query .= " WHERE user_id = ?";
            
            $update_profile_query_stmt = mysqli_prepare($conn, $update_profile_query);

            if ($image_name) {
                mysqli_stmt_bind_param($update_profile_query_stmt, "sssisssi", $first_name, $middle_name, $last_name, $phone_number, $email, $gender, $image_name, $user_id);
            } else {
                mysqli_stmt_bind_param($update_profile_query_stmt, "sssissi", $first_name, $middle_name, $last_name, $phone_number, $email, $gender, $user_id);
            }

            $update_profile_query_result = mysqli_stmt_execute($update_profile_query_stmt);

            if ($update_profile_query_result) {
                $response['message'] = "User profile updated successfully.";
                $response['status'] = 200;
                if ($image_name) {
                    $response['image_filename'] = $image_name;
                }
            } else {
                $response['message'] = "Error while updating user profile.";
                $response['status'] = 201;
            }
        } else {
            $response['message'] = "No empty values allowed.";
            $response['status'] = 201;
        }
    } else {
        $response['message'] = "Invalid tag.";
        $response['status'] = 201;
    }
} else {
    $response['message'] = "Only POST method is allowed.";
    $response['status'] = 201;
}

echo json_encode($response);
?>
