<?php
require '../db.php';

$id = $_POST['id'] ?? null;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$role = $_POST['role'] ?? null;
must_change_password = isset($_POST['must_change_password']) ? (bool)$_POST['must_change_password'] : null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'User id required']);
    exit;
}

$fields = [];
$params = [];
$types = '';
if ($username) { $fields[] = 'username=?'; $params[] = $username; $types .= 's'; }
if ($email) { $fields[] = 'email=?'; $params[] = $email; $types .= 's'; }
if ($role) { $fields[] = 'role=?'; $params[] = $role; $types .= 's'; }
if (!is_null($must_change_password)) { $fields[] = 'must_change_password=?'; $params[] = $must_change_password; $types .= 'i'; }
if (!$fields) {
    echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
    exit;
}
$params[] = $id;
$types .= 'i';
$sql = "UPDATE users SET ".implode(',', $fields)." WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close(); 