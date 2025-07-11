<?php
require '../db.php';

$id_number = $_POST['id_number'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? 'GUARDIAN';

if (!$id_number || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'ID number and password required']);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $id_number, $hashed, $email, $role);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User created']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close(); 