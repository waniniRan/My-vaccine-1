<?php
require '../db.php';

// 1. Read data from POST request
$username = $_POST['username'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$temp_password = $_POST['temporary_password'] ?? '';
$facility_id = $_POST['facility_id'] ?? null;

// 2. Validate required fields
if (!$username || !$fullname || !$temp_password || !$facility_id) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing']);
    exit;
}

// 3. Hash password
$hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

// 4. Insert into users table
$stmt_user = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'FACILITY_ADMIN')");
$stmt_user->bind_param("sss", $username, $hashed_password, $email);

if (!$stmt_user->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'User creation failed: ' . $stmt_user->error]);
    exit;
}
$user_id = $conn->insert_id;
$stmt_user->close();
//username is FA001 when logging in and creating facility admin
// 5. Insert into facility_admins table
$stmt = $conn->prepare("INSERT INTO facility_admins (user_id, facility_id, fullname, email, temporary_password, password_changed)
                        VALUES (?, ?, ?, ?, ?, FALSE)");
$stmt->bind_param("iisss", $user_id, $facility_id, $fullname, $email, $temp_password);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Facility admin created']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Facility admin insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
