<?php
require 'db.php';

echo "<h2>Calendar Debug - Vaccination Records</h2>";

// Check Bob's records specifically
echo "<h3>Bob's Vaccination Records:</h3>";
$result = $conn->query("
    SELECT vr.*, c.first_name, c.last_name, c.child_id as string_child_id, c.id as numeric_child_id
    FROM vaccination_records vr 
    JOIN children c ON vr.child_id = c.id 
    WHERE c.first_name = 'Bob'
    ORDER BY vr.administration_date DESC
");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "📅 <strong>{$row['administration_date']}</strong> - {$row['first_name']} {$row['last_name']}<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Record ID: {$row['record_id']}<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Child ID (numeric): {$row['numeric_child_id']}<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Child ID (string): {$row['string_child_id']}<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Vaccine ID: {$row['vaccine_id']}<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Dose: {$row['dose_number']}<br><br>";
    }
} else {
    echo "❌ No vaccination records found for Bob<br>";
}

// Check all vaccination records
echo "<h3>All Vaccination Records (Last 10):</h3>";
$result = $conn->query("
    SELECT vr.*, c.first_name, c.last_name 
    FROM vaccination_records vr 
    JOIN children c ON vr.child_id = c.id 
    ORDER BY vr.administration_date DESC 
    LIMIT 10
");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "📅 <strong>{$row['administration_date']}</strong> - {$row['first_name']} {$row['last_name']} (ID: {$row['child_id']})<br>";
    }
} else {
    echo "❌ No vaccination records found<br>";
}

// Check children table
echo "<h3>Children in Database:</h3>";
$result = $conn->query("SELECT id, child_id, first_name, last_name FROM children ORDER BY id");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "👶 {$row['first_name']} {$row['last_name']} - Numeric ID: {$row['id']}, String ID: {$row['child_id']}<br>";
    }
} else {
    echo "❌ No children found<br>";
}

echo "<h3>🔍 Calendar API Test:</h3>";
echo "Testing vaccination records API for Bob (assuming child_id = 'C002'):<br>";
echo "URL: <a href='http://localhost/My-vaccine/php-backend/vaccination_records/read.php?child_id=C002' target='_blank'>vaccination_records/read.php?child_id=C002</a><br>";

$conn->close();
?> 