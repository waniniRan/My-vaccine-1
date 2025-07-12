<?php
require '../db.php';
// this is what the System Admin will use to read all facility admins
$sql = "
SELECT 
    fa.id AS admin_id,
    u.id AS user_id,
    u.username,
    u.email AS user_email,
    fa.fullname,
    fa.email AS admin_email,
    fa.temporary_password,
    fa.password_changed,
    hf.id AS facility_id,
    hf.name AS facility_name,
    hf.location
FROM 
    facility_admins fa
JOIN 
    users u ON fa.user_id = u.id
JOIN 
    health_facilities hf ON fa.facility_id = hf.id
";

$result = $conn->query($sql);

$admins = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $admins
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'data' => [],
        'message' => 'No facility admins found'
    ]);
}

$conn->close();
?>
