<?php
require '../db.php';

$child_id = $_POST['child_id'] ?? null;
$weight = $_POST['weight'] ?? null;
$height = $_POST['height'] ?? null;
$head_circumference = $_POST['head_circumference'] ?? null;
$recorded_by = $_POST['recorded_by'] ?? null;
$facility_id = $_POST['facility_id'] ?? null;
$visit_date = $_POST['visit_date'] ?? null;
$notes = $_POST['notes'] ?? null;

if (!$child_id || !$weight || !$height || !$recorded_by || !$facility_id || !$visit_date) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO growth_records (
        child_id, weight, height, head_circumference, recorded_by, 
        facility_id, visit_date, notes, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("idddiiis", $child_id, $weight, $height, $head_circumference, $recorded_by, $facility_id, $visit_date, $notes);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Growth record saved']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
