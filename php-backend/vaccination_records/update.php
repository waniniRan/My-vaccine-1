<?php
require '../db.php';

$record_id = $_POST['record_id'] ?? null;
$date_given = $_POST['date_given'] ?? null;
$dose_number = $_POST['dose_number'] ?? null;
$remarks = $_POST['remarks'] ?? null;

if (!$record_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing record_id']);
    exit;
}

$fields = [];
$params = [];
$types = '';

// Only allow safe updates
if ($date_given !== null) {
    $fields[] = 'date_given = ?';
    $params[] = $date_given;
    $types .= 's';
}
if ($dose_number !== null) {
    $fields[] = 'dose_number = ?';
    $params[] = $dose_number;
    $types .= 'i';
}
if ($remarks !== null) {
    $fields[] = 'remarks = ?';
    $params[] = $remarks;
    $types .= 's';
}

if (empty($fields)) {
    echo json_encode(['status' => 'error', 'message' => 'No valid fields to update']);
    exit;
}

$params[] = $record_id;
$types .= 'i';

$sql = "UPDATE vaccination_records SET " . implode(', ', $fields) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Vaccination record updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
