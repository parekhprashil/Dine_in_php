<?php
include 'conn.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['country_id'])) {
    $country_id = intval($_POST['country_id']);
    $query = "SELECT country_name FROM country_master WHERE country_id = $country_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $row]);
    } else {
        echo json_encode(["success" => false, "error" => "Country not found."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}

$conn->close();
?>
