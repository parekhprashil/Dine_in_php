<?php

// update user address

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../lib/dnConnect.php';
$response = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST['tag']) && $_POST['tag'] === 'update_user_address')
        {
            if (
                (!empty($_POST['house_number']))
                && (!empty($_POST['residency_name']))
                && (!empty($_POST['area']))
                && (!empty($_POST['street']))
                && (!empty($_POST['pincode']))
                && (!empty($_POST['country_id']))
                && (!empty($_POST['state_id']))
                && (!empty($_POST['city_id']))
                && (!empty($_POST['user_id']))
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


                // check user is present in database or not
                $check_user = "SELECT * FROM `user_address_master` WHERE user_id = ?";
                $check_user_stmt = mysqli_prepare($conn, $check_user);

                mysqli_stmt_bind_param($check_user_stmt, "i", $user_id);
  
                mysqli_stmt_execute($check_user_stmt);
  
                $check_user_result = mysqli_stmt_get_result($check_user_stmt);
  
                if (!mysqli_num_rows($check_user_result) > 0)
                {
                    $response['message'] = "User not found";
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();   
                }

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
                    $response['status'] = 201;
                    echo json_encode($response);
                    exit();
                }    

                // update user address
                $update_user_address_query = "UPDATE `user_address_master`
                                            SET user_address_house_number = ?,
                                            user_address_flat_name = ?,
                                            user_address_area = ?,
                                            user_address_street = ?,
                                            user_address_landmark = ?,
                                            user_address_pincode = ?,
                                            country_id = ?,
                                            state_id = ?,
                                            city_id = ?,
                                            user_address_updatedAt = now()
                                            WHERE user_id = ?";

                $update_user_address_query_stmt = mysqli_prepare($conn, $update_user_address_query);

                mysqli_stmt_bind_param($update_user_address_query_stmt, 'ssssssiiii', $house_number, $residency_name, $area, $street, $landmark, $pincode, $country_id, $state_id, $city_id, $user_id);
                
                // echo $update_user_address_query;
                $update_user_address_query_result = mysqli_stmt_execute($update_user_address_query_stmt);
                
                if ($update_user_address_query_result)
                {
                    $response['message'] = "Data updated successfully";
                    $response['status'] = 200;
                }
                else
                {
                    $response['message'] = "Error while updating user address";
                    $response['status'] = 201;
                }
            }
            else
            {
                $response['message'] = "No Empty value allow";
                $response['status'] = 201;
            }
        }
        else
        {
            $response['message'] = "Invalid tag or tag not found";
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