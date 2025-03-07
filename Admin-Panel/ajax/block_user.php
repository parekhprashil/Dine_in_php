<?php
include 'conn.php';  // Include your DB connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'] ?? null;
    $isblock = $_POST['isblock'] ?? null;

    if ($user_id === null || $isblock === null) {
        echo json_encode(["success" => false, "message" => "Invalid request data"]);
        exit;
    }

    // Convert isblock value explicitly to integer (prevents issues)
    $isblock = (int)$isblock;

    // Update the isblock status in the database
    $stmt = $conn->prepare("UPDATE usermaster SET isblock = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $isblock, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User status updated successfully", "isblock" => $isblock]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update status"]);
    }

    $stmt->close();
    $conn->close();
}
?>
