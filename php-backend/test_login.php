<?php
require 'db.php';

echo "<h2>Testing Login Process</h2>";

// Test login for guardian1
$username = 'guardian1';
$password = 'password'; // This is the hashed password in the database

echo "<h3>Testing login for username: $username</h3>";

// Check if user exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo "User found: " . json_encode($user) . "<br>";
    
    // Check if password matches (using password_verify)
    if (password_verify($password, $user['password'])) {
        echo "✅ Password is correct<br>";
        
        // Get guardian profile
        $stmt = $conn->prepare("SELECT * FROM guardians WHERE user_id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $guardian = $result->fetch_assoc();
        
        if ($guardian) {
            echo "Guardian profile: " . json_encode($guardian) . "<br>";
            
            // Get children for this guardian
            $stmt = $conn->prepare("SELECT * FROM children WHERE guardian_id = ?");
            $stmt->bind_param("i", $guardian['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $children = [];
            while ($row = $result->fetch_assoc()) {
                $children[] = $row;
            }
            
            echo "Children found: " . count($children) . "<br>";
            foreach ($children as $child) {
                echo "Child: " . json_encode($child) . "<br>";
            }
            
        } else {
            echo "❌ No guardian profile found for user_id: " . $user['id'] . "<br>";
        }
        
    } else {
        echo "❌ Password is incorrect<br>";
    }
} else {
    echo "❌ User not found<br>";
}

$conn->close();
?> 