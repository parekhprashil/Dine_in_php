<?php

$localHost = 'localhost';
$userName = 'root';
$password = '';
$dbName = 'dine_in_system';
// $port = '';

    $conn = mysqli_connect($localHost, $userName, $password, $dbName);

    if ($conn)
    {
        // echo "Database connected successfully";
    }
    else
    {
        echo "error while connecting Database" . mysqli_connect_error();
    }
?>
