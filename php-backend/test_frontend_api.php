<?php
require 'db.php';

echo "<h2>Testing Frontend API Calls</h2>";

// Simulate the exact API calls the frontend makes
// 1. Login (user_id = 2 for guardian1)
$user_id = 2;

echo "<h3>1. Getting Guardian Profile (user_id = $user_id)</h3>";
$stmt = $conn->prepare("SELECT * FROM guardians WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$guardian = $result->fetch_assoc();

if ($guardian) {
    echo "✅ Guardian found: " . json_encode($guardian) . "<br>";
    $guardian_id = $guardian['id'];
    
    // 2. Get children for this guardian
    echo "<h3>2. Getting Children (guardian_id = $guardian_id)</h3>";
    $stmt = $conn->prepare("SELECT * FROM children WHERE guardian_id = ?");
    $stmt->bind_param("i", $guardian_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $children = [];
    while ($row = $result->fetch_assoc()) {
        $children[] = $row;
    }
    
    echo "✅ Children found: " . count($children) . "<br>";
    foreach ($children as $child) {
        echo "Child: " . json_encode($child) . "<br>";
    }
    
    // 3. Get growth records for each child
    echo "<h3>3. Getting Growth Records for Each Child</h3>";
    $total_growth = 0;
    foreach ($children as $child) {
        $stmt = $conn->prepare("SELECT * FROM growth_records WHERE child_id = ?");
        $stmt->bind_param("i", $child['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $growth_records = [];
        while ($row = $result->fetch_assoc()) {
            $growth_records[] = $row;
        }
        $total_growth += count($growth_records);
        echo "Child {$child['first_name']} ({$child['id']}): " . count($growth_records) . " growth records<br>";
    }
    echo "Total growth records: $total_growth<br>";
    
    // 4. Get vaccination records for each child
    echo "<h3>4. Getting Vaccination Records for Each Child</h3>";
    $total_vaccinations = 0;
    foreach ($children as $child) {
        $stmt = $conn->prepare("SELECT * FROM vaccination_records WHERE child_id = ?");
        $stmt->bind_param("i", $child['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $vaccination_records = [];
        while ($row = $result->fetch_assoc()) {
            $vaccination_records[] = $row;
        }
        $total_vaccinations += count($vaccination_records);
        echo "Child {$child['first_name']} ({$child['id']}): " . count($vaccination_records) . " vaccination records<br>";
    }
    echo "Total vaccination records: $total_vaccinations<br>";
    
    // 5. Summary
    echo "<h3>5. Frontend Should Display:</h3>";
    echo "Children: " . count($children) . "<br>";
    echo "Total Growth Records: $total_growth<br>";
    echo "Total Vaccination Records: $total_vaccinations<br>";
    
} else {
    echo "❌ No guardian found for user_id: $user_id<br>";
}

$conn->close();
?> 