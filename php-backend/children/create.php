<?php
require '../db.php';

// 1. Get POST data
$guardian_id = $_POST['guardian_id'] ?? null;
$fullname = $_POST['fullname'] ?? '';
$dob = $_POST['dob'] ?? '';
$gender = $_POST['gender'] ?? '';
$birth_weight = $_POST['birth_weight'] ?? null;
$birth_height = $_POST['birth_height'] ?? null;
$registered_by = $_POST['registered_by'] ?? null;
$facility_id = $_POST['facility_id'] ?? null;

// 2. Validate required fields
if (!$guardian_id || !$fullname || !$dob || !$gender || !$registered_by || !$facility_id) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

// 3. Insert child
$stmt = $conn->prepare("
    INSERT INTO children (guardian_id, fullname, dob, gender, birth_weight, birth_height, registered_by, facility_id, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("isssddii", $guardian_id, $fullname, $dob, $gender, $birth_weight, $birth_height, $registered_by, $facility_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Child registered']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
