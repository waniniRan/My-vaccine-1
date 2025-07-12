<?php
require '../db.php';

// Input
$guardian_id = $_POST['guardian_id'] ?? null;
$email = $_POST['email'] ?? null;
$phone_number = $_POST['phone_number'] ?? null;

if (!$guardian_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing guardian_id']);
    exit;
}

$fields = [];
$params = [];
$types = '';

// Check which fields to update
if ($email !== null) {
    $fields[] = 'email = ?';
    $params[] = $email;
    $types .= 's';
}
if ($phone_number !== null) {
    $fields[] = 'phone_number = ?';
    $params[] = $phone_number;
    $types .= 's';
}

if (empty($fields)) {
    echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
    exit;
}

// Build query
$query = 'UPDATE guardians SET ' . implode(', ', $fields) . ' WHERE id = ?';
$params[] = $guardian_id;
$types .= 'i';

// Execute
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Guardian info updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
