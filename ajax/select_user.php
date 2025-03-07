
<?php
include 'conn.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "SELECT * FROM usermaster where user_isdelete = 0";
$result = $conn->query($query);

$data = array();

while ($row = $result->fetch_assoc()) {
  $default_image_url = "./assets/images/default.jpg"; 

  $user_image = !empty($row['user_image']) 
      ? '<img src="http://192.168.4.212/Hotel_DineIn_API/Dine_in_php/API/Dine_in_php-sagar/uploads/profile/' . htmlspecialchars($row['user_image']) . '" class="user-image" width="50">' 
      : '<img src="'.$default_image_url.'" class="user-image" width="50">';
  

    $data[] = array(
        "user_id" => $row['user_id'],
        "user_image" => $user_image,
        "user_first_name" => ($row['user_first_name']),
        "user_middle_name" => ($row['user_middle_name']),
        "user_last_name" => ($row['user_last_name']),
        "user_phone_number" => ($row['user_phone_number']),
        "user_email" => ($row['user_email']),
        // "user_password" => $row['user_password'], 
        "user_gender" => ($row['user_gender']),
        "isblock" => '<label class="switch">
                <input type="checkbox" class="toggle-status" data-id="' . $row['user_id'] . '" ' . ($row['isblock'] == 1 ? 'checked' : '') . '>
                <span class="slider round"></span>
              </label>',
        "user_block_timing" => !empty($row['user_block_timing']) ? $row['user_block_timing'] : 'N/A',
        "user_addeddate" => $row['user_addeddate'],
        "user_updateddate" => !empty($row['user_updateddate']) ? $row['user_updateddate'] : 'N/A',
        "actions" => '<button class="btn btn-danger btn-sm delete-user" data-id="' . $row['user_id'] . '">
                <i class="fas fa-trash"></i> 
              </button>',

    );
}

echo json_encode(["data" => $data]);

$conn->close();
?>
