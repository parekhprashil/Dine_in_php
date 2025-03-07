<?php
require 'conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["country_name"])) {
    $countryName = trim($_POST["country_name"]);
    $countryStatus = $_POST["country_status"] ?? 0;
    $isDelete = $_POST["isdelete"] ?? 0;
    $countryAddedBy = $_POST["country_addedby"] ?? 1;
    $countryAddedDate = $_POST["country_addeddate"] ?? date("Y-m-d H:i:s");
    // $countryUpdatedBy = $_POST["country_updatedby"] ?? $countryAddedBy;
    // $countryUpdatedDate = $_POST["country_updateddate"] ?? $countryAddedDate;

    $checkQuery = "SELECT COUNT(*) AS count FROM country_master WHERE country_name = '$countryName'";
    $result = mysqli_query($conn, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo json_encode(["error" => "This country already exists."]);
        exit;
    }

    $insertQuery = "INSERT INTO country_master (country_name, country_status, isdelete, country_addedby, country_addeddate) 
                    VALUES ('$countryName', $countryStatus, $isDelete, $countryAddedBy, '$countryAddedDate')";

    if (mysqli_query($conn, $insertQuery)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to insert country."]);
    }
}
?>
