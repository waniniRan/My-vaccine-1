<?php
require 'db.php';

echo "<h2>Testing Frontend Data Flow</h2>";

// Simulate the frontend flow:
// 1. Get guardian profile (user_id = 2 for guardian1)
echo "<h3>1. Getting Guardian Profile (user_id = 2)</h3>";
$stmt = $conn->prepare("SELECT * FROM guardians WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$user_id = 2; // guardian1
$stmt->execute();
$result = $stmt->get_result();
$guardian = $result->fetch_assoc();
echo "Guardian: " . json_encode($guardian) . "<br>";

// 2. Get children for guardian
echo "<h3>2. Getting Children for Guardian ID = " . $guardian['id'] . "</h3>";
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

// 3. Get growth records for all children
echo "<h3>3. Getting Growth Records for All Children</h3>";
$total_growth = 0;
foreach ($children as $child) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM growth_records WHERE child_id = ?");
    $stmt->bind_param("i", $child['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $total_growth += $count;
    echo "Child {$child['first_name']} ({$child['id']}): {$count} growth records<br>";
}
echo "Total growth records: {$total_growth}<br>";

// 4. Get vaccination records for all children
echo "<h3>4. Getting Vaccination Records for All Children</h3>";
$total_vaccinations = 0;
foreach ($children as $child) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM vaccination_records WHERE child_id = ?");
    $stmt->bind_param("i", $child['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $total_vaccinations += $count;
    echo "Child {$child['first_name']} ({$child['id']}): {$count} vaccination records<br>";
}
echo "Total vaccination records: {$total_vaccinations}<br>";

// 5. Show what the frontend should display
echo "<h3>5. Frontend Stats Summary</h3>";
echo "Children: " . count($children) . "<br>";
echo "Total Growth Records: {$total_growth}<br>";
echo "Total Vaccination Records: {$total_vaccinations}<br>";

$conn->close();
?> 