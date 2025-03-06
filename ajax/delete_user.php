<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $sql = "UPDATE usermaster SET user_isdelete = 1 WHERE user_id = $user_id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true, "message" => "User soft deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete user"]);
    }
}

mysqli_close($conn);
?>
