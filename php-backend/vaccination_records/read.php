<?php
require '../db.php';

$child_id = $_GET['child_id'] ?? null;

// Resolve string child_id to numeric ID if needed
if ($child_id && !is_numeric($child_id)) {
    $stmt = $conn->prepare("SELECT id FROM children WHERE child_id = ?");
    $stmt->bind_param("s", $child_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if ($row) {
        $child_id = $row['id'];
    } else {
        echo json_encode([]);
        $conn->close();
        exit;
    }
}

// Main query
$sql = "
SELECT 
    vr.id AS record_id,
    c.child_id,
    c.fullname AS child_name,
    v.name AS vaccine_name,
    vr.dose_number,
    vr.date_given,
    vr.remarks,
    u.username AS administered_by,
    hf.name AS facility_name,
    vr.created_at
FROM vaccination_records vr
JOIN children c ON vr.child_id = c.id
JOIN vaccines v ON vr.vaccine_id = v.id
JOIN users u ON vr.administered_by = u.id
JOIN health_facilities hf ON vr.facility_id = hf.id
";

if ($child_id) {
    $sql .= " WHERE vr.child_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $child_id);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$records = [];

while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

echo json_encode([
    'status' => 'success',
    'data' => $records
]);

$stmt->close();
$conn->close();
?>
