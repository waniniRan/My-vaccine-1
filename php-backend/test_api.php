<?php
require 'db.php';

echo "<h2>API Endpoint Tests</h2>";

// Test 1: Login endpoint
echo "<h3>1. Testing login for guardian1:</h3>";
$username = 'guardian1';
$password = 'password';

$stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashed, $role);
    $stmt->fetch();
    if (password_verify($password, $hashed)) {
        echo "Login successful: user_id=$id, role=$role<br>";
    } else {
        echo "Password verification failed<br>";
    }
} else {
    echo "User not found<br>";
}
$stmt->close();

// Test 2: Guardian profile endpoint
echo "<h3>2. Testing guardian profile for user_id=2:</h3>";
$user_id = 2;
$stmt = $conn->prepare("SELECT * FROM guardians WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$guardian = $result->fetch_assoc();
echo "Guardian profile: <pre>" . print_r($guardian, true) . "</pre>";
$stmt->close();

// Test 3: Children endpoint
echo "<h3>3. Testing children for guardian_id=1:</h3>";
$guardian_id = 1;
$stmt = $conn->prepare("SELECT * FROM children WHERE guardian_id = ?");
$stmt->bind_param("i", $guardian_id);
$stmt->execute();
$result = $stmt->get_result();
$children = $result->fetch_all(MYSQLI_ASSOC);
echo "Children: <pre>" . print_r($children, true) . "</pre>";
$stmt->close();

// Test 4: Notifications endpoint
echo "<h3>4. Testing notifications for guardian_id=1:</h3>";
$stmt = $conn->prepare("SELECT * FROM notifications WHERE guardian_id = ?");
$stmt->bind_param("i", $guardian_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
echo "Notifications: <pre>" . print_r($notifications, true) . "</pre>";
$stmt->close();

$conn->close();
?> 