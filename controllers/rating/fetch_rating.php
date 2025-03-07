
<?php

// fetch restaurant rating 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../lib/dnConnect.php';
$response = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST['tag']) && $_POST['tag'] === 'fetch_rating')
        {
            $rating_status = 0;
            if (
                isset($_POST['restaurant_id']) && !empty($_POST['restaurant_id'])
                )
            {
                $restaurant_id = $_POST['restaurant_id'];

                

                $all_rating_query = "SELECT u.user_first_name, u.user_last_name, r.rating, r.rating_description, r.rating_image_url FROM `rating_master` AS r
                INNER JOIN `usermaster` AS u
                ON r.user_id = u.user_id
                WHERE r.restaurant_id = ? AND r.rating_status = ?
                ORDER BY r.rating DESC";
                        
                $all_rating_query_stmt = mysqli_prepare($conn, $all_rating_query);

                mysqli_stmt_bind_param($all_rating_query_stmt, "ii", $restaurant_id, $rating_status);

                mysqli_stmt_execute($all_rating_query_stmt);

                $all_rating_query_result = mysqli_stmt_get_result($all_rating_query_stmt);

                if (mysqli_num_rows($all_rating_query_result) > 0)
                {
                    $users = [];
                    while ($rating_data = mysqli_stmt_get_result($all_rating_query_stmt))
                    {
                        $users[] = [
                            "first name" => $rating_data['user_first_name'],
                            "last name" => $rating_data['user_last_name'],
                            "rating" => $rating_data['rating'],
                            "feedBack" => $rating_data['rating_description'],
                            "rating image" => $rating_data['rating_image_url']
                        ];                    
                    }
                    $response['users'] = $users;
                    $response['message'] = "Rating fetched successfully";
                    $response['status'] = 200;
                }
                else
                {
                    $response['message'] = "No data found";
                    $response['status'] = 201;
                }
            }
            // for rating filter
            else if
                (
                isset($_POST['restaurant_id']) && !empty($_POST['restaurant_id'])
                && isset($_POST['rating']) && !empty($_POST['rating'])                
                )
            {
                $rating = $_POST['rating'];

                // rating validation
                if (!preg_match("/^[1-5]{1}$/", $rating)) {
                    $response['message'] = "Rating must contain only 1 number (between 1 to 5)";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                $rating_filter = "SELECT u.user_first_name, u.user_last_name, r.rating, r.rating_description, r.rating_image_url FROM `rating_master` AS r
                INNER JOIN `usermaster` AS u
                ON r.user_id = u.user_id
                WHERE r.restaurant_id = ? AND r.rating_status = ? AND r.rating = ?
                ORDER BY r.rating DESC";

                $rating_filter_stmt = mysqli_prepare($conn, $rating_filter);

                mysqli_stmt_bind_param($rating_filter_stmt, "iii", $restaurant_id, $rating_status, $rating);

                mysqli_stmt_execute($rating_filter_stmt);

                $rating_filter_result = mysqli_stmt_get_result($rating_filter_stmt);

                if (mysqli_num_rows($rating_filter_result) > 0)
                {
                    while ($rating_data = $rating_filter_result)
                    {
                        $users[] = [
                            "first name" => $rating_data['user_first_name'],
                            "last name" => $rating_data['user_last_name'],
                            "rating" => $rating_data['rating'],
                            "feedBack" => $rating_data['rating_description'],
                            "rating image" => $rating_data['rating_image_url']
                        ];   
                    }              
                    $response['Rating for'] = $rating;
                    $response['users'] = $users;
                    $response['message'] = "Rating fetched successfull with filter";
                    $response['status'] = 200;   
                }
                else
                {
                    $response['message'] = "No data fonud";
                    $response['status'] = 201;
                }
    
            }
            else
            {
                $response['message'] = "Invalid restaurant";
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


?>