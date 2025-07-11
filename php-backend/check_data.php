<?php
require 'db.php';

echo "<h2>Checking Database Data</h2>";

// Check growth records
$result = $conn->query("SELECT COUNT(*) as count FROM growth_records WHERE child_id IN (1, 2)");
$growth_count = $result->fetch_assoc()['count'];
echo "Growth records for Alice and Bob: $growth_count<br>";

// Check vaccination records
$result = $conn->query("SELECT COUNT(*) as count FROM vaccination_records WHERE child_id IN (1, 2)");
$vaccination_count = $result->fetch_assoc()['count'];
echo "Vaccination records for Alice and Bob: $vaccination_count<br>";

// Check children
$result = $conn->query("SELECT * FROM children WHERE guardian_id = 1");
echo "<h3>Children for Guardian 1:</h3>";
while ($row = $result->fetch_assoc()) {
    echo "Child ID: {$row['child_id']}, Name: {$row['first_name']} {$row['last_name']}<br>";
}

// Test API endpoints
echo "<h2>Testing API Endpoints</h2>";

// Test growth records API
echo "<h3>Growth Records API Response:</h3>";
$guardian_id = 1;
$stmt = $conn->prepare("SELECT gr.*, c.first_name, c.last_name FROM growth_records gr JOIN children c ON gr.child_id = c.child_id WHERE c.guardian_id = ? ORDER BY gr.date_recorded DESC");
$stmt->bind_param("i", $guardian_id);
$stmt->execute();
$result = $stmt->get_result();

$growth_data = [];
while ($row = $result->fetch_assoc()) {
    $growth_data[] = $row;
}

echo "Growth records found: " . count($growth_data) . "<br>";
if (count($growth_data) > 0) {
    echo "Sample record: " . json_encode($growth_data[0]) . "<br>";
}

// Test vaccination records API
echo "<h3>Vaccination Records API Response:</h3>";
$stmt = $conn->prepare("SELECT vr.*, v.name as vaccine_name, c.first_name, c.last_name FROM vaccination_records vr JOIN vaccines v ON vr.vaccine_id = v.id JOIN children c ON vr.child_id = c.child_id WHERE c.guardian_id = ? ORDER BY vr.administration_date DESC");
$stmt->bind_param("i", $guardian_id);
$stmt->execute();
$result = $stmt->get_result();

$vaccination_data = [];
while ($row = $result->fetch_assoc()) {
    $vaccination_data[] = $row;
}

echo "Vaccination records found: " . count($vaccination_data) . "<br>";
if (count($vaccination_data) > 0) {
    echo "Sample record: " . json_encode($vaccination_data[0]) . "<br>";
}

$conn->close();
?> 