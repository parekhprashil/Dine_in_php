<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/dnConnect.php';

if (!isset($_GET['email']) || empty($_GET['email'])) {
    die("Email parameter is missing.");
}

$email = mysqli_real_escape_string($conn, trim($_GET['email']));

// Check if the email exists
$query = "SELECT user_id FROM usermaster WHERE user_email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];

    // ✅ Generate a new password
    $new_password = bin2hex(random_bytes(4)); // Example: "f3a9c1d2"
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // ✅ Update the database with the hashed password
    $update_query = "UPDATE usermaster SET user_password = '$hashed_password' WHERE user_id = '$user_id'";
    mysqli_query($conn, $update_query);

    // ✅ Store the new password in a variable for sending in email
    $password = $new_password;

    // ✅ Email subject
    $subject = "Reset Your Password | BERRY";
    $to = $email;

    // ✅ Include email templates
    include "mail/sample1.php";
    include "mail.php";

    // echo "A new password has been sent to your email.";
} else {
    // echo "Email not found in our system.";
}
?>
