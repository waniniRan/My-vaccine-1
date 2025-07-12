<?php
require '../db.php';

$notification_id = $_POST['notification_id'] ?? null;
$guardian_id = $_POST['guardian_id'] ?? null;

if (!$notification_id || !$guardian_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

// Update only if the guardian owns it
$stmt = $conn->prepare("
    UPDATE notifications 
    SET is_read = 1 
    WHERE id = ? AND guardian_id = ?
");

$stmt->bind_param("ii", $notification_id, $guardian_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Notification marked as read']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
