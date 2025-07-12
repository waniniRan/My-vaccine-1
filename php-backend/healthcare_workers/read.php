<?php
require '../db.php';

$sql = "
SELECT 
    hw.id AS record_id,
    u.id AS user_id,
    u.username AS worker_username,     -- Used for login
    u.email AS user_email,
    hw.worker_id,                      -- e.g., 'W001'
    hw.fullname,
    hw.phone_number,
    hw.position,
    hw.status,
    hw.date_joined,
    hw.password_changed,
    hw.temporary_password,
    hf.id AS facility_id,
    hf.name AS facility_name,
    hf.location AS facility_location,
    fa.fullname AS facility_admin_name
FROM 
    healthcare_workers hw
JOIN 
    users u ON hw.user_id = u.id
JOIN 
    health_facilities hf ON hw.facility_id = hf.id
LEFT JOIN 
    facility_admins fa ON hw.facility_admin_id = fa.id
";

$result = $conn->query($sql);

$workers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $workers[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $workers
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'data' => [],
        'message' => 'No healthcare workers found'
    ]);
}

$conn->close();
?>
