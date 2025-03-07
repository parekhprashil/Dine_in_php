<?php
include '../lib/dnConnect.php'; // Ensure $conn is properly initialized
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hotel_dine_in']) && $_POST['hotel_dine_in'] === 'single_restaurant') {
        if (isset($_POST["restaurant_id"]) && !empty($_POST["restaurant_id"])) {
            $restaurant_id = $_POST["restaurant_id"];

            $query = "SELECT 
                r.restaurant_id, 
                r.restaurant_name, 
                r.restaurant_email,
                r.restaurant_phone_number,
                r.restaurant_licence_no,
                r.restaurant_user_name,
                r.restaurant_website_link,
                r.restaurant_Price,
                r.restaurant_description,
                r.restaurant_food_type,
                r.restaurant_open_time, 
                r.restaurant_close_time, 
                r.restaurant_active_status,
                r.isdelete,
                r.restaurant_added_date,
                r.restaurant_updated_date,
                r.restaurant_updatedby,
                r.restaurant_approved_date,
                (SELECT GROUP_CONCAT(ri.restaurant_image_url SEPARATOR ',') 
                 FROM restaurant_image_master ri 
                 WHERE ri.restaurant_id = r.restaurant_id) AS restaurant_images,
                (SELECT AVG(rt.rating) FROM rating_master rt WHERE rt.restaurant_id = r.restaurant_id) AS avg_rating
              FROM restaurant_master r
              WHERE r.restaurant_id = '$restaurant_id' AND r.isdelete = 0 AND r.restauarnt_approved_status = 1";

            $result = mysqli_query($conn, $query);

            if ($row = mysqli_fetch_assoc($result)) {
                // Map food type flag to readable text
                $food_type_map = [
                    0 => "Veg",
                    1 => "Non-Veg",
                    2 => "Both"
                ];
                $food_type_label = isset($food_type_map[$row['restaurant_food_type']]) ? $food_type_map[$row['restaurant_food_type']] : "Unknown";

                // Fetch Food Categories Available in the Restaurant
                $food_query = "SELECT fm.food_id, fm.food_name 
                               FROM food_category_master fm
                               JOIN restaurant_food_category_master rfc ON fm.food_id = rfc.food_id
                               WHERE rfc.restaurant_id = '$restaurant_id' 
                               AND fm.isdelete = 0 
                               AND fm.food_status = 0 
                               AND rfc.isdelete = 0 
                               AND rfc.showing_status = 0";

                $food_result = mysqli_query($conn, $food_query);
                $food_categories = [];

                while ($food_row = mysqli_fetch_assoc($food_result)) {
                    $food_categories[] = $food_row['food_name']; // Store only food category names
                }

                $data = [
                    'restaurant_id' => $row['restaurant_id'],
                    'restaurant_name' => $row['restaurant_name'],
                    'restaurant_email' => $row['restaurant_email'],
                    'restaurant_phone_number' => $row['restaurant_phone_number'],
                    'restaurant_licence_no' => $row['restaurant_licence_no'],
                    'restaurant_user_name' => $row['restaurant_user_name'],
                    'restaurant_website_link' => $row['restaurant_website_link'],
                    'restaurant_price' => number_format((float)$row['restaurant_Price'], 2, '.', ''),
                    'restaurant_description' => $row['restaurant_description'],
                    'restaurant_food_type' => $food_type_label, // Display mapped food type
                    'restaurant_open_time' => $row['restaurant_open_time'],
                    'restaurant_close_time' => $row['restaurant_close_time'],
                    'restaurant_active_status' => $row['restaurant_active_status'],
                    'isdelete' => $row['isdelete'],
                    'restaurant_added_date' => $row['restaurant_added_date'],
                    'restaurant_updated_date' => $row['restaurant_updated_date'],
                    'restaurant_updatedby' => $row['restaurant_updatedby'],
                    'restaurant_approved_date' => $row['restaurant_approved_date'],
                    'restaurant_images' => $row['restaurant_images'] ? explode(',', $row['restaurant_images']) : [],
                    'avg_rating' => number_format((float)$row['avg_rating'], 1, '.', ''),
                    'food_categories' => $food_categories // Add food category list in response
                ];

                $response["status"] = 200;
                $response["message"] = "Data fetched successfully";
                $response["data"] = $data;
            } else {
                $response["status"] = 201;
                $response["message"] = "No restaurant found";
            }
        } else {
            $response["status"] = 201;
            $response["message"] = "Please provide a Restaurant ID";
        }
    } else {
        $response["status"] = 201;
        $response["message"] = "Invalid Tag";
    }
} else {
    $response["status"] = 201;
    $response["message"] = "Only POST method allowed";
}

echo json_encode($response); // Make sure to echo the response
?>
