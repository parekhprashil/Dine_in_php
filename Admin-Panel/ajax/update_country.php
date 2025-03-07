<?php
include 'conn.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['country_id']) && isset($_POST['country_name'])) {
    $country_id = intval($_POST['country_id']);
    $country_name = mysqli_real_escape_string($conn, trim($_POST['country_name'])); 


    $query = "UPDATE country_master SET country_name = '$country_name' , country_updatedby = 1 WHERE country_id = $country_id";
    $result = $conn->query($query);

    if ($conn->affected_rows > 0) { 
        echo json_encode(["success" => true, "message" => "Country updated successfully"]);
    } else {
        echo json_encode(["success" => false, "error" => "No changes made or update failed."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}

$conn->close();
?>