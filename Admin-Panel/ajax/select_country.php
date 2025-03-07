<?php
include 'conn.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "SELECT * FROM country_master WHERE isdelete = 0";
$result = $conn->query($query);

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = array(
        "country_id" => $row['country_id'],
        "country_name" => htmlspecialchars($row['country_name']),
        "country_status" => '<div class="d-inline-block">
            <button class="btn '.($row['country_status'] == 0 ? 'btn-success' : 'btn-danger').' toggle-status px-2 py-1 rounded-pill shadow-sm fw-bold d-inline-flex align-items-center"
                data-id="' . $row['country_id'] . '" data-status="'.($row['country_status'] == 0 ? '0' : '1').'">
                <i class="fas '.($row['country_status'] == 0 ? 'fa-check-circle' : 'fa-times-circle').' me-1"></i> 
                '.($row['country_status'] == 0 ? 'Active' : 'Inactive').'
            </button>
        </div>',
        "country_addedby" => htmlspecialchars($row['country_addedby']),
        "country_addeddate" => $row['country_addeddate'],
        "country_updatedby" => !empty($row['country_updatedby']) ? htmlspecialchars($row['country_updatedby']) : 0,
        "country_updateddate" => !empty($row['country_updateddate']) ? $row['country_updateddate'] : 'N/A',
        "actions" => '<div class="d-inline-flex">
            <button class="btn btn-primary btn-sm edit-country me-1" data-id="' . $row['country_id'] . '">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-danger btn-sm delete-country" data-id="' . $row['country_id'] . '">
                <i class="fas fa-trash"></i>
            </button>
        </div>'
    );
}

echo json_encode(["data" => $data]);

$conn->close();
