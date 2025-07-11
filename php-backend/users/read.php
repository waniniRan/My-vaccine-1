<?php
require '../db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("SELECT id, username, email, role, must_change_password, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo json_encode($user ?: []);
    $stmt->close();
} else {
    $result = $conn->query("SELECT id, username, email, role, must_change_password, created_at FROM users");
    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
}
$conn->close(); 