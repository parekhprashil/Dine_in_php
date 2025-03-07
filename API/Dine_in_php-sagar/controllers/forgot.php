<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/dnConnect.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email'])) {
        $response['message'] = "Email is required.";
        $response['status'] = 201;
        echo json_encode($response);
        exit();
    }

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    // Check if the email exists in user_master
    $emailQuery = "SELECT * FROM usermaster WHERE user_email = '$email'";
    $emailResult = mysqli_query($conn, $emailQuery);

    if ($emailResult && mysqli_num_rows($emailResult) > 0) {
        // Email exists, redirect to mail.php
        header("Location: send.php?email=" . urlencode($email));
        exit();
    } else {
        $response['message'] = "Email not found.";
        $response['status'] = 201;
        echo json_encode($response);
        exit();
    }
} else {
    $response['message'] = "Only POST method is allowed.";
    $response['status'] = 201;
    echo json_encode($response);
}

?>
