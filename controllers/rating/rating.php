<?php

// restaurant rating

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../lib/dnConnect.php';
$response = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST['tag']) && $_POST['tag'] === 'rating')
        {
            if (
                (isset($_POST['restaurant_id']) && !empty($_POST['restaurant_id']))
                && (isset($_POST['user_id']) && !empty($_POST['user_id']))
                && (isset($_POST['rating']) && !empty($_POST['rating']))
            )
            {
                $restaurant_id = $_POST['restaurant_id'];
                $user_id = $_POST['user_id'];
                $rating = $_POST['rating'];
                $rating_feedback = isset($_POST['rating_feedback']) ? trim($_POST['rating_feedback']) : '';
                $rating_image = isset($_FILES['image']) ? $_FILES['image'] : '';

                // validation

                // find userId and restaurantId is present into user and restaurent table
                $find_user_and_restaurent_query = "SELECT user_id, restaurant_id FROM `usermaster`, `restaurant_master` WHERE user_id = ? AND restaurant_id = ?";

                $find_user_and_restaurent_query_stmt = mysqli_prepare($conn, $find_user_and_restaurent_query);

                mysqli_stmt_bind_param($find_user_and_restaurent_query_stmt, 'ii', $user_id, $restaurant_id);

                mysqli_stmt_execute($find_user_and_restaurent_query_stmt);

                $find_user_and_restaurent_query_result = mysqli_stmt_get_result($find_user_and_restaurent_query_stmt);

                if (!mysqli_num_rows($find_user_and_restaurent_query_result) > 0)
                {
                    $response['message'] = "User not found or restaurent not found";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                // rating validation
                if (!preg_match("/^[1-5]{1}$/", $rating)) {
                    $response['message'] = "Rating must contain only 1 number (between 1 to 5)";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                // rating feedback validation



                // file upload
                $image_name = "";
                if (!empty($_FILES['image']))
                {
                    $target_folder = __DIR__ . "/../../uploads/";

                    // if (!file_exists($target_folder)) {
                    //     mkdir($target_folder, 0777, true);  
                    // }

                    $random_number = rand(1000, 9999);
                    $image_name = $random_number.'_'.basename($rating_image['name']);
                    $target_file = $target_folder . $image_name;
                    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    // validate image formate
                    $allowed_extension = array("jpg", "jpeg", "png");

                    if (!in_array($image_type, $allowed_extension))
                    {
                        $response['message'] = "Invalid file extention";
                        $response['status'] = 201;
                        echo json_encode($response);
                        exit();
                    }

                    // validate image size
                    if ($rating_image['size'] > 5000000)
                    {
                        $response['message'] = "Image size is large only 5mb allow";
                        $response['status'] = 201;
                        echo json_encode($response);
                        exit();
                    }
                    // move file to uploads/rating_images folder
                    // echo "Target file: " . $target_file;  

                    if (!move_uploaded_file($rating_image['tmp_name'], $target_file))
                    {
                        $response['message'] = "Error while uploading image";
                        $response['status'] = 201;
                        echo json_encode($response);
                        exit();
                    }
                }

                $insert_rating_query = "INSERT INTO `rating_master`(restaurant_id, user_id, rating, rating_description, rating_image_url)
                VALUES(?, ?, ?, ?, ?)";

                $insert_rating_query_stmt = mysqli_prepare($conn, $insert_rating_query);

                mysqli_stmt_bind_param($insert_rating_query_stmt, "iiiss", $restaurant_id, $user_id, $rating, $rating_feedback, $image_name);

                $insert_rating_query_result = mysqli_stmt_execute($insert_rating_query_stmt);

                if ($insert_rating_query_result)
                {
                    $response['message'] = "Rating inserted successfully";
                    $response['status'] = 200;
                }
                else
                {
                    $response['message'] = "Error while inserting raing";
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
            $response['message'] = "Invalid tag or tag missing";
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