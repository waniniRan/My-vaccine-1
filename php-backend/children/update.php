<?php
require '../db.php';

// Get POST data
$child_id = $_POST['child_id'] ?? null;
$fullname = $_POST['fullname'] ?? null;
$dob = $_POST['dob'] ?? null;
$gender = $_POST['gender'] ?? null;
$birth_weight = $_POST['birth_weight'] ?? null;
$birth_height = $_POST['birth_height'] ?? null;

if (!$child_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing child_id']);
    exit;
}

// Build dynamic update query
$fields = [];
$params = [];
$types = '';

if ($fullname !== null) {
    $fields[] = 'fullname = ?';
    $params[] = $fullname;
    $types .= 's';
}
if ($dob !== null) {
    $fields[] = 'dob = ?';
    $params[] = $dob;
    $types .= 's';
}
if ($gender !== null) {
    $fields[] = 'gender = ?';
    $params[] = $gender;
    $types .= 's';
}
if ($birth_weight !== null) {
    $fields[] = 'birth_weight = ?';
    $params[] = $birth_weight;
    $types .= 'd';
}
if ($birth_height !== null) {
    $fields[] = 'birth_height = ?';
    $params[] = $birth_height;
    $types .= 'd';
}

if (empty($fields)) {
    echo json_encode(['status' => 'error', 'message' => 'No valid fields to update']);
    exit;
}

$query = 'UPDATE children SET ' . implode(', ', $fields) . ' WHERE child_id = ?';
$params[] = $child_id;
$types .= 's';

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Child updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
