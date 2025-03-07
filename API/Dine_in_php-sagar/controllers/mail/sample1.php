<?php

include '../lib/dnConnect.php';
$token = bin2hex(random_bytes(32));
$expires_at = time() + 3600; // 1 hour from now

$stmt = $conn->prepare("SELECT user_id FROM usermaster WHERE user_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows >0)
{
    $user = $result->fetch_assoc();
    $user_id = $user['user_id']; // Get user ID

    $token = bin2hex(random_bytes(32)); 
    $expires_at = time() + 3600;

    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $token);
    $stmt->execute();

    $reset_link = "http://192.168.4.212/hotel_DineIn_API/Dine_in_php/API/Dine_in_php-sagar/controllers/reset-pass.php?token=".$token;
    $message = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Your Password | BERRY</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: auto;
            border: 2px solid #007bff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .header h2 {
            color: #007bff;
            margin: 10px 0 0;
        }
        .content {
            font-size: 16px;
            color: #333;
        }
        .content p {
            margin-bottom: 10px;
        }
        .password-box {
            background: #007bff;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background: #28a745;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://cdn.pixabay.com/photo/2017/04/05/01/12/food-2203671_1280.jpg" alt="BERRY">
            <h2>Change Your Password</h2>
        </div>
        <div class="content">
            <p><strong>Subject:</strong>Berry password reset</p>
            <p>Dear User,</p>
            <p>We heard that you lost your GitHub password. Sorry about that! </p>
            <p>But don’t worry! You can use the following button to reset your password:</p>
            <a href="'. $reset_link .'" class="button">Reset Your Password</a>
            <p>If you did not request a password reset, please ignore this email or reply to let us know. This password reset is only valid for the next 1 hour.</p>
    
            <p>You have requested to change your password. Please use the temporary password below and update it immediately.<a href='. $reset_link.'>Reset Your Password</p>
            <p>If you did not request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p><strong>BERRY Support Team</strong><br>
                <a href="mailto:">berry00789654@gmail.com</a><br>
            </p>

        </div>
    </div>
</body>
</html>';

}

?>