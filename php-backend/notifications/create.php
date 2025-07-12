<?php
require '../db.php';

// Inputs
$guardian_id = $_POST['guardian_id'] ?? null;
$title = $_POST['title'] ?? null;
$message = $_POST['message'] ?? null;
$created_by = $_POST['created_by'] ?? null;

if (!$guardian_id || !$title || !$message || !$created_by) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

$notification_type = 'info';         // or 'reminder', based on UI needs
$delivery_method = 'in_app';         // always in-app
$scheduled_for = null;               // immediate notification

$stmt = $conn->prepare("
    INSERT INTO notifications (
        guardian_id, title, message, created_by,
        notification_type, delivery_method, scheduled_for, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("issssss", $guardian_id, $title, $message, $created_by, $notification_type, $delivery_method, $scheduled_for);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'In-app notification created']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
