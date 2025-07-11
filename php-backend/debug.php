<?php
require 'db.php';

echo "<h2>Debug Information</h2>";

// Test 1: Get user guardian1
echo "<h3>1. User guardian1:</h3>";
$stmt = $conn->prepare("SELECT * FROM users WHERE username = 'guardian1'");
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
echo "<pre>" . print_r($user, true) . "</pre>";

// Test 2: Get guardian profile for user_id = 2
echo "<h3>2. Guardian profile for user_id = 2:</h3>";
$stmt = $conn->prepare("SELECT * FROM guardians WHERE user_id = 2");
$stmt->execute();
$result = $stmt->get_result();
$guardian = $result->fetch_assoc();
echo "<pre>" . print_r($guardian, true) . "</pre>";

// Test 3: Get children for guardian_id = 1
echo "<h3>3. Children for guardian_id = 1:</h3>";
$stmt = $conn->prepare("SELECT * FROM children WHERE guardian_id = 1");
$stmt->execute();
$result = $stmt->get_result();
$children = $result->fetch_all(MYSQLI_ASSOC);
echo "<pre>" . print_r($children, true) . "</pre>";

// Test 4: Get notifications for guardian_id = 1
echo "<h3>4. Notifications for guardian_id = 1:</h3>";
$stmt = $conn->prepare("SELECT * FROM notifications WHERE guardian_id = 1");
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
echo "<pre>" . print_r($notifications, true) . "</pre>";

// Test 5: Get all children (to see what's actually there)
echo "<h3>5. All children in database:</h3>";
$result = $conn->query("SELECT * FROM children");
$all_children = $result->fetch_all(MYSQLI_ASSOC);
echo "<pre>" . print_r($all_children, true) . "</pre>";

$conn->close();
?> 