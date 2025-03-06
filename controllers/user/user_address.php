<?php

use function PHPSTORM_META\map;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/dnConnect.php';
$response = [];


    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST['tag']) && $_POST['tag'] === 'user_address')
        {
            if (
                (isset($_POST['house_number']) && !empty($_POST['house_number']))
                && (isset($_POST['residency_name']) && !empty($_POST['residency_name']))
                && (isset($_POST['area']) && !empty($_POST['area']))
                && (isset($_POST['street']) && !empty($_POST['street']))
                && (isset($_POST['pincode']) && !empty($_POST['pincode']))
                && (isset($_POST['country_id']) && !empty($_POST['country_id']))
                && (isset($_POST['state_id']) && !empty($_POST['state_id']))
                && (isset($_POST['city_id']) && !empty($_POST['city_id']))
                && (isset($_POST['user_id']) && !empty($_POST['user_id']))
            )
            {
                $house_number = $_POST['house_number'];
                $residency_name = $_POST['residency_name'];
                $area = $_POST['area'];
                $street = $_POST['street'];
                $landmark = isset($_POST['landmark']) ? $_POST['landmark'] : '';
                $pincode = $_POST['pincode'];
                $country_id = $_POST['country_id'];
                $state_id = $_POST['state_id'];
                $city_id = $_POST['city_id'];
                $user_id = $_POST['user_id'];

                // validation



                // check for selected country, state, city
                $find_place_query = "SELECT country_name, state_name, city_name FROM `country_master` AS c
                                    INNER JOIN `state_master` AS s
                                    ON s.country_id = c.country_id
                                    INNER JOIN `city_master` AS city
                                    ON city.state_id = s.state_id
                                    WHERE c.country_id = ? AND s.state_id = ? AND  city.city_id = ?";
                
                $find_place_query_stmt = mysqli_prepare($conn, $find_place_query);

                mysqli_stmt_bind_param($find_place_query_stmt, "iii", $country_id, $state_id, $city_id);

                mysqli_stmt_execute($find_place_query_stmt);

                $find_place_query_result = mysqli_stmt_get_result($find_place_query_stmt);

                if (!mysqli_num_rows($find_place_query_result) > 0)
                {
                    $response['message'] = "Invalid country or state or city";
                    $response['status'] = 201;
                    echo json_encode($response);
                    // mysqli_stmt_close($find_place_query_stmt);
                    exit();
                }

                // check user belongs to that pincode
                $check_pincode = "SELECT * FROM `city_pincode` WHERE city_id = ? AND city_pincode = ?";

                $check_pincode_stmt = mysqli_prepare($conn, $check_pincode);

                mysqli_stmt_bind_param($check_pincode_stmt, "is", $city_id, $pincode);

                mysqli_stmt_execute($check_pincode_stmt);

                $check_pincode_result = mysqli_stmt_get_result($check_pincode_stmt);

                if (!mysqli_num_rows($check_pincode_result) > 0)
                {
                    $response['message'] = "Invalid Pincode";
                    $responsep['status'] = 201;
                    echo json_encode($response);
                    exit();
                }

                // check user is present in database or not
                $check_user = "SELECT * FROM `usermaster` WHERE user_id = ?";

                $check_user_stmt = mysqli_prepare($conn, $check_user);

                mysqli_stmt_bind_param($check_user_stmt, "i", $user_id);

                mysqli_stmt_execute($check_user_stmt);

                $check_user_result = mysqli_stmt_get_result($check_user_stmt);

                if (mysqli_num_rows($check_user_result) > 0)
                {
                    // insert data into database
                    $insert_user_address_query = "INSERT INTO `user_address_master`(user_address_house_number, user_address_flat_name, user_address_area, user_address_street, user_address_landmark, user_address_pincode, country_id, state_id, city_id, user_id)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $insert_user_address_query_stmt = mysqli_prepare($conn, $insert_user_address_query);

                    mysqli_stmt_bind_param($insert_user_address_query_stmt, 'sssssiiiii', $house_number, $residency_name, $area, $street, $landmark, $pincode, $country_id, $state_id, $city_id, $user_id);

                    $check_user_address_result = mysqli_stmt_execute($insert_user_address_query_stmt);

                    if ($check_user_address_result)
                    {
                        $response['message'] = "User Address inserted successfully";
                        $response['status'] = 200;
                    }
                    else
                    {
                        $response['message'] = "Error while inserting user address";
                        $response['status'] = 201;
                    }
                }
                else
                {
                    $response['message'] = "User not found";
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