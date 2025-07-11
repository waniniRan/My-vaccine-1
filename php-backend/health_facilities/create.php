<?php
require '../db.php';

$facility_id = $_POST['facility_id'] ?? '';
$name = $_POST['name'] ?? '';
$facility_type = $_POST['facility_type'] ?? '';
$location = $_POST['location'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$admin_id = $_POST['admin_id'] ?? null;
$created_by = $_POST['created_by'] ?? null;

if (!$facility_id || !$name || !$facility_type) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}
$stmt = $conn->prepare("INSERT INTO health_facilities (facility_id, name, facility_type, location, phone, email, admin_id, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssii", $facility_id, $name, $facility_type, $location, $phone, $email, $admin_id, $created_by);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Facility created']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();

// If creating a facility admin, also create user account with admin_id as username
if ($admin_id) {
    $admin_password = $_POST['admin_password'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $hashed_admin = password_hash($admin_password, PASSWORD_DEFAULT);
    $stmt_admin = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'FACILITY_ADMIN')");
    $stmt_admin->bind_param("sss", $admin_id, $hashed_admin, $admin_email);
    $stmt_admin->execute();
    $stmt_admin->close();
}

$conn->close(); 