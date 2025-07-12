<?php
require '../db.php';

// 1. Get POST data
$worker_id = $_POST['worker_id'] ?? '';                 // e.g. "W003"
$worker_username = $_POST['worker_username'] ?? '';     // e.g. "janedoe"
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone_number'] ?? '';
$position = $_POST['position'] ?? 'nurse';
$facility_id = $_POST['facility_id'] ?? null;
$temp_password = $_POST['temporary_password'] ?? '';
$facility_admin_id = $_POST['facility_admin_id'] ?? null;

// 2. Validate required fields
if (!$worker_id || !$worker_username || !$fullname || !$facility_id || !$facility_admin_id) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

// 3. Hash password
$hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

// 4. Insert into users table
$stmt_user = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'WORKER')");
$stmt_user->bind_param("sss", $worker_username, $hashed_password, $email);

if (!$stmt_user->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'User creation failed: ' . $stmt_user->error]);
    exit;
}
$user_id = $conn->insert_id;
$stmt_user->close();

// 5. Insert into healthcare_workers table
$stmt = $conn->prepare("INSERT INTO healthcare_workers (
    user_id, worker_id, worker_username, fullname, email, phone_number, position,
    facility_id, facility_admin_id, temporary_password, password_changed, status, date_joined
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, 'active', NOW())");

$stmt->bind_param("issssssiss", $user_id, $worker_id, $worker_username, $fullname, $email, $phone, $position, $facility_id, $facility_admin_id, $temp_password);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Healthcare worker created']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Worker insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
