<?php
require '../db.php';

$record_id = $_POST['record_id'] ?? null;
$weight = $_POST['weight'] ?? null;
$height = $_POST['height'] ?? null;
$head_circumference = $_POST['head_circumference'] ?? null;
$visit_date = $_POST['visit_date'] ?? null;
$notes = $_POST['notes'] ?? null;

if (!$record_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing record_id']);
    exit;
}

$fields = [];
$params = [];
$types = '';

// Append fields to update if provided
if ($weight !== null) {
    $fields[] = 'weight = ?';
    $params[] = $weight;
    $types .= 'd';
}
if ($height !== null) {
    $fields[] = 'height = ?';
    $params[] = $height;
    $types .= 'd';
}
if ($head_circumference !== null) {
    $fields[] = 'head_circumference = ?';
    $params[] = $head_circumference;
    $types .= 'd';
}
if ($visit_date !== null) {
    $fields[] = 'visit_date = ?';
    $params[] = $visit_date;
    $types .= 's';
}
if ($notes !== null) {
    $fields[] = 'notes = ?';
    $params[] = $notes;
    $types .= 's';
}

if (empty($fields)) {
    echo json_encode(['status' => 'error', 'message' => 'No valid fields provided']);
    exit;
}

$params[] = $record_id;
$types .= 'i';

$sql = 'UPDATE growth_records SET ' . implode(', ', $fields) . ' WHERE id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Growth record updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
