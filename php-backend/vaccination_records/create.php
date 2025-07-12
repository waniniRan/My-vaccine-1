<?php
require '../db.php';

// Get POST data
$child_id = $_POST['child_id'] ?? null;
$vaccine_id = $_POST['vaccine_id'] ?? null;
$date_given = $_POST['date_given'] ?? null;
$dose_number = $_POST['dose_number'] ?? null;
$administered_by = $_POST['administered_by'] ?? null;
$facility_id = $_POST['facility_id'] ?? null;
$remarks = $_POST['remarks'] ?? null;

// Validate required fields
if (!$child_id || !$vaccine_id || !$date_given || !$dose_number || !$administered_by || !$facility_id) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

// Insert vaccination record
$stmt = $conn->prepare("
    INSERT INTO vaccination_records (
        child_id, vaccine_id, date_given, dose_number,
        administered_by, facility_id, remarks, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("iisiiis", $child_id, $vaccine_id, $date_given, $dose_number, $administered_by, $facility_id, $remarks);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Vaccination recorded']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
