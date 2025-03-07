<?php

header('Content-Type: application/json');
include '../lib/dnConnect.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "SELECT 
    r.restaurant_id, 
    r.restaurant_name, 
    r.restaurant_Price, 
    r.restaurant_open_time, 
    r.restaurant_close_time, 
    (SELECT ri.restaurant_image_url FROM restaurant_image_master ri WHERE ri.restaurant_id = r.restaurant_id LIMIT 1) AS restaurant_image_url, 
    (SELECT AVG(rt.rating) FROM rating_master rt WHERE rt.restaurant_id = r.restaurant_id) AS avg_rating
  FROM restaurant_master r
  WHERE r.isdelete = 0 AND r.restauarnt_approved_status = 1";

    $result = mysqli_query($conn, $query);
    $basepath = "http://192.168.4.212/Hotel_DineIn_API/Dine_in_php/API/Dine_in_php-sagar/uploads/restaurant/";

    $data = []; // This will hold the fetched data

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'restaurant_id' => $row['restaurant_id'],
                'restaurant_name' => $row['restaurant_name'],
                'restaurant_price' => number_format((float) $row['restaurant_Price'], 2, '.', ''),
                'restaurant_open_time' => $row['restaurant_open_time'],
                'restaurant_close_time' => $row['restaurant_close_time'],
                'restaurant_image_url' => $basepath . ($row['restaurant_image_url'] ?? ''),
                'avg_rating' => number_format((float) $row['avg_rating'], 1, '.', '')
            ];
        }
        $response['status'] = 200;
        $response['message'] = 'Data Fetched Successfully';
        $response['data'] = $data; // Assign the correct data array
    } else {
        $response['status'] = 201;
        $response['message'] = 'Database query failed';
    }
} else {
    $response['status'] = 201;
    $response['message'] = 'Only POST method is allowed';
}

echo json_encode($response);
mysqli_close($conn);

?>
