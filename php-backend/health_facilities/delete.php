<?php
require '../db.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Facility id required']);
    exit;
}
$stmt = $conn->prepare("DELETE FROM health_facilities WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Facility deleted']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close(); 