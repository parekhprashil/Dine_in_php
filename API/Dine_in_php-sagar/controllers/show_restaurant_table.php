<?php
include '../lib/dnConnect.php';
header("Content-Type: application/json");

$response = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['hotel_dine_in']) && $_POST['hotel_dine_in'] === "featch_table") {
        if (isset($_POST['restaurant_id']) && !empty($_POST['restaurant_id'])) {
            $restaurant_id = intval($_POST['restaurant_id']); // Convert to integer for safety

            // Query to fetch tables
            $query = "SELECT table_number, table_capacity 
                      FROM table_master 
                      WHERE restaurant_id = '$restaurant_id' AND isdelete = 0";

            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $tables = [];

                while ($row = mysqli_fetch_assoc($result)) {
                    $tables[] = [
                        'table_number' => $row['table_number'],
                        'table_capacity' => $row['table_capacity']
                    ];
                }

                $response["status"] = 200;
                $response["message"] = "Data fetched successfully";
                $response["data"] = $tables;
            } else {
                $response["status"] = 201;
                $response["message"] = "No tables found";
            }
        } else {
            $response["status"] = 201;
            $response["message"] = "Please provide a valid restaurant ID";
        }
    } else {
        $response["status"] = 201;
        $response["message"] = "Invalid Tag";
    }
} else {
    $response["status"] = 201;
    $response["message"] = "Only POST method allowed";
}

// Echo the JSON response
echo json_encode($response);
mysqli_close($conn);

?>
