<?php
require 'db.php';

echo "<h2>Debug Authentication and Data Access</h2>";

// Check if there are any users in the system
echo "<h3>All Users in System:</h3>";
$result = $conn->query("SELECT id, username, email, role FROM users");
while ($row = $result->fetch_assoc()) {
    echo "User ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}, Role: {$row['role']}<br>";
}

// Check guardians
echo "<h3>All Guardians:</h3>";
$result = $conn->query("SELECT g.id, g.user_id, g.fullname, u.username FROM guardians g JOIN users u ON g.user_id = u.id");
while ($row = $result->fetch_assoc()) {
    echo "Guardian ID: {$row['id']}, User ID: {$row['user_id']}, Name: {$row['fullname']}, Username: {$row['username']}<br>";
}

// Check children for each guardian
echo "<h3>Children by Guardian:</h3>";
$result = $conn->query("SELECT c.id, c.child_id, c.first_name, c.last_name, c.guardian_id, g.fullname as guardian_name FROM children c JOIN guardians g ON c.guardian_id = g.id ORDER BY c.guardian_id");
while ($row = $result->fetch_assoc()) {
    echo "Child: {$row['first_name']} {$row['last_name']} (ID: {$row['child_id']}) - Guardian: {$row['guardian_name']} (ID: {$row['guardian_id']})<br>";
}

// Check growth records by child
echo "<h3>Growth Records by Child:</h3>";
$result = $conn->query("SELECT gr.child_id, c.first_name, c.last_name, COUNT(*) as count FROM growth_records gr JOIN children c ON gr.child_id = c.id GROUP BY gr.child_id");
while ($row = $result->fetch_assoc()) {
    echo "Child: {$row['first_name']} {$row['last_name']} (ID: {$row['child_id']}) - {$row['count']} growth records<br>";
}

// Check vaccination records by child
echo "<h3>Vaccination Records by Child:</h3>";
$result = $conn->query("SELECT vr.child_id, c.first_name, c.last_name, COUNT(*) as count FROM vaccination_records vr JOIN children c ON vr.child_id = c.id GROUP BY vr.child_id");
while ($row = $result->fetch_assoc()) {
    echo "Child: {$row['first_name']} {$row['last_name']} (ID: {$row['child_id']}) - {$row['count']} vaccination records<br>";
}

$conn->close();
?> 