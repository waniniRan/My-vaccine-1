<?php
require '../db.php';

$user_id = $_POST['user_id'] ?? null;
$national_id = $_POST['national_id'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$temp_password = $_POST['temporary_password'] ?? '';
$password_changed = isset($_POST['password_changed']) ? (bool)$_POST['password_changed'] : true;
$registered_by = $_POST['registered_by'] ?? null;
$is_active = isset($_POST['is_active']) ? (bool)$_POST['is_active'] : true;

if (!$user_id || !$national_id || !$fullname) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

// Create user account for guardian if not already created
$hashed = password_hash($temp_password, PASSWORD_DEFAULT);
$stmt_user = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'GUARDIAN')");
$stmt_user->bind_param("sss", $national_id, $hashed, $email);
$stmt_user->execute();
$user_id = $conn->insert_id;
$stmt_user->close();

$stmt = $conn->prepare("INSERT INTO guardians (user_id, national_id, fullname, email, phone_number, temporary_password, password_changed, registered_by, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssii", $user_id, $national_id, $fullname, $email, $phone_number, $temp_password, $password_changed, $registered_by, $is_active);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Guardian created']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close(); 