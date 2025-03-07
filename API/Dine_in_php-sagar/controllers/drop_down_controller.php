<?php
include '../lib/dnConnect.php';
header("Content-Type: application/json");



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['hotel_dine_in']) && $_POST['hotel_dine_in'] === 'dropdown'){

        $response = ["status" => 200,"message" => "Data Featch Successfully", "data" => []];
    // Fetch all countries
    $country_query = "SELECT country_id,country_name FROM country_master";
    $country_result = mysqli_query($conn, $country_query);

    while ($country = mysqli_fetch_assoc($country_result)) {
        $country_id = $country['country_id'];
        $country["states"] = [];

        // Fetch states for each country
        $state_query = "SELECT state_id,state_name FROM state_master WHERE country_id = $country_id";
        $state_result = mysqli_query($conn, $state_query);

        while ($state = mysqli_fetch_assoc($state_result)) {
            $state_id = $state['state_id'];
            $state["cities"] = [];

            // Fetch cities for each state
            $city_query = "SELECT city_id,city_name FROM city_master WHERE state_id = $state_id";
            $city_result = mysqli_query($conn, $city_query);

            while ($city = mysqli_fetch_assoc($city_result)) {
                $state["cities"][] = $city;
            }

            $country["states"][] = $state;
        }

        $response["data"]["countries"][] = $country;
    }
}
else{
    $response["status"] = 201;
    $response["message"] = "Invalid Tag";
}

} else {
    $response["status"] = 201;
    $response["message"] = "Only POST method allowed";
}

echo json_encode($response);
?>
