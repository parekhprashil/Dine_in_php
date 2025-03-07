<?php
include '../lib/dnConnect.php';
include '../lib/common.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        $response = [ "data" => [],"status" => 200,"message" => "Data Fetch Successfully"];

        // Fetch user details
        $user_query = "SELECT user_id,user_first_name,user_middle_name,user_last_name,user_phone_number,user_email,user_gender,user_image,user_addeddate FROM usermaster WHERE user_id = $user_id";
        $user_result = mysqli_query($conn, $user_query);
        // $basepath = "";
        if ($user = mysqli_fetch_assoc($user_result)) {
            $response["data"] = $user;
            $imageURL = $base_url."uploads/profile/" . $user['user_image'];
            $response["data"]["user_image"] = $imageURL;
        } else {
            $response["status"] = 404;
            $response["message"] = "User not found";
        }
    } else {
        $response["status"] = 400;
        $response["message"] = "User ID not provided";
    }

} else {
    $response["status"] = 405;
    $response["message"] = "Only POST method allowed";
}

echo json_encode($response);
?>