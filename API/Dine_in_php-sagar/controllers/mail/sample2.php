<?php
$message='<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
            color: #333333;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #666666;
            border-radius: 0 0 8px 8px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        @media (max-width: 600px) {
            .email-container {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Welcome to Our Service</h1>
        </div>
        <div class="content">
            <p>Hi there,</p>
            <p>Thank you for subscribing to our service. We are excited to have you on board!</p>
            <p>Click the buttons below to visit your favorite sites:</p>
            <a href="https://www.google.com" class="btn" target="_blank">Google</a>
            <a href="https://www.youtube.com" class="btn" target="_blank">YouTube</a>
            <a href="https://www.facebook.com" class="btn" target="_blank">Facebook</a>
            <a href="https://www.github.com" class="btn" target="_blank">GitHub</a>

            <hr>

            <h2>Confirmation Email</h2>
            <p>Dear User,</p>
            <p>We have received your request. Please confirm your email address by clicking the button below:</p>
            <a href="https://www.yourwebsite.com/confirm" class="btn" target="_blank">Confirm Email</a>
            <p>If you did not request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
';
?>