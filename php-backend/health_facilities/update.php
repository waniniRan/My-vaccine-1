<?php
require '../db.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Facility id required']);
    exit;
}
$fields = [];
$params = [];
$types = '';
foreach ([
    'facility_id' => 's',
    'name' => 's',
    'facility_type' => 's',
    'location' => 's',
    'phone' => 's',
    'email' => 's',
    'admin_id' => 'i',
    'created_by' => 'i'
] as $field => $type) {
    if (isset($_POST[$field])) {
        $fields[] = "$field=?";
        $params[] = $_POST[$field];
        $types .= $type;
    }
}
if (!$fields) {
    echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
    exit;
}
$params[] = $id;
$types .= 'i';
$sql = "UPDATE health_facilities SET ".implode(',', $fields)." WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Facility updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close(); 