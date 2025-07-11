<?php
require '../db.php';

$guardian_id = $_GET['guardian_id'] ?? null;
if ($guardian_id) {
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE guardian_id = ?");
    $stmt->bind_param("i", $guardian_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($notifications);
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM notifications");
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($notifications);
}
$conn->close(); 