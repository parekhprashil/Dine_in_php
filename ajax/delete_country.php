<?php
include 'conn.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['country_id'])) {
    $country_id = intval($_POST['country_id']); 

    $sql = "UPDATE country_master SET isdelete = 1 WHERE country_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $country_id);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(["success" => true, "message" => "Country soft deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete country"]);
    }

    $stmt->close();
}

$conn->close();
?>
