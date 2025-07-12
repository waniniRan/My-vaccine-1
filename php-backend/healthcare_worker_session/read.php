<?php
require '../db.php';

// Optional filters
$facility_id = $_GET['facility_id'] ?? null;
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;
$activity_type = $_GET['activity_type'] ?? null;

$sql = "
    SELECT 
        s.id,
        s.healthcare_worker_id,
        w.fullname AS worker_name,
        s.login_time,
        s.logout_time,
        TIMESTAMPDIFF(MINUTE, s.login_time, s.logout_time) AS duration_minutes,
        s.activity_type,
        s.notes
    FROM healthcare_worker_sessions s
    JOIN healthcare_workers w ON s.healthcare_worker_id = w.id
    WHERE 1 = 1
";

$params = [];
$types = "";

// Add filters if provided
if ($facility_id) {
    $sql .= " AND w.facility_id = ?";
    $types .= "i";
    $params[] = $facility_id;
}
if ($activity_type) {
    $sql .= " AND s.activity_type = ?";
    $types .= "s";
    $params[] = $activity_type;
}
if ($start_date && $end_date) {
    $sql .= " AND DATE(s.login_time) BETWEEN ? AND ?";
    $types .= "ss";
    $params[] = $start_date;
    $params[] = $end_date;
}

$sql .= " ORDER BY s.login_time DESC LIMIT 100";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$sessions = [];

while ($row = $result->fetch_assoc()) {
    $sessions[] = $row;
}

echo json_encode($sessions);
$stmt->close();
$conn->close();
?>
