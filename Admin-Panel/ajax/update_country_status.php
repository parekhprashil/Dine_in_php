<?php
include 'conn.php'; 

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['country_id']) && isset($_POST['country_status'])) {
    $country_id = intval($_POST['country_id']);
    $country_status = intval($_POST['country_status']);

    $query = "UPDATE country_master SET country_status = $country_status WHERE country_id = $country_id";

    if ($conn->query($query)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}
?>
