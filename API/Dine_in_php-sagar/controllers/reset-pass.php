<?php
require 'conn.php'; // Database connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists and is not expired
    $stmt = $conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $expires_at = $row['expires_at'];

        if (time() > $expires_at) {
            echo "<div class='error-msg'>This link has expired.</div>";
            exit();
        }
    } else {
        echo "<div class='error-msg'>Invalid reset link.</div>";
        exit();
    }
} else {
    echo "<div class='error-msg'>No reset token provided.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url("https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.vecteezy.com%2Ffree-svg%2Ffood-background&psig=AOvVaw0rr9jNXNdFA4lyS0w0NPqA&ust=1741437069807000&source=images&cd=vfe&opi=89978449&ved=0CBQQjRxqFwoTCIDrjeL894sDFQAAAAAdAAAAABAR");
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        .container h2 {
            margin-bottom: 15px;
            color: #6e8efb;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #6e8efb;
            box-shadow: 0 0 8px rgba(110, 142, 251, 0.5);
        }

        .error-text {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .btn {
            background: #6e8efb;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #5a7ae0;
        }

        .error-msg {
            background: #ff5c5c;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="update-password.php" method="POST" onsubmit="return validatePassword()">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="form-group">
                <label>New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <div class="error-text" id="error-message">Passwords do not match</div>
            </div>

            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>

    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var errorMessage = document.getElementById("error-message");

            if (password !== confirmPassword) {
                errorMessage.style.display = "block";
                return false;
            } else {
                errorMessage.style.display = "none";
                return true;
            }
        }
    </script>
</body>
</html>
