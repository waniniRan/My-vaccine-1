<?php
require '../db.php';

// Optional filters
$role = $_GET['role'] ?? null;
$action_type = $_GET['action_type'] ?? null;
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

$sql = "
    SELECT 
        l.id,
        l.user_id,
        u.username,
        u.role,
        l.action_type,
        l.action_details,
        l.created_at
    FROM system_logs l
    JOIN users u ON l.user_id = u.id
    WHERE 1 = 1
";

$params = [];
$types = "";

// Add filters dynamically
if ($role) {
    $sql .= " AND u.role = ?";
    $types .= "s";
    $params[] = $role;
}
if ($action_type) {
    $sql .= " AND l.action_type = ?";
    $types .= "s";
    $params[] = $action_type;
}
if ($start_date && $end_date) {
    $sql .= " AND DATE(l.created_at) BETWEEN ? AND ?";
    $types .= "ss";
    $params[] = $start_date;
    $params[] = $end_date;
}

$sql .= " ORDER BY l.created_at DESC LIMIT 100";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$logs = [];

while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode($logs);
$stmt->close();
$conn->close();
?>
