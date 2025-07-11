<?php
require '../db.php';

$username = $_POST['id_number'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Username and password required']);
    exit;
}

$stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashed, $role);
    $stmt->fetch();
    if (password_verify($password, $hashed)) {
        echo json_encode(['status' => 'success', 'user_id' => $id, 'role' => $role]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}
$stmt->close();
$conn->close(); 