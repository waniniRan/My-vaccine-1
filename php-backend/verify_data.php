<?php
require 'db.php';

echo "<h2>Data Verification Report</h2>";

// Check if tables exist and have data
$tables = [
    'users',
    'health_facilities', 
    'healthcare_workers',
    'guardians',
    'children',
    'vaccines',
    'growth_records',
    'vaccination_records',
    'notifications'
];

echo "<h3>📊 Table Record Counts:</h3>";
foreach ($tables as $table) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $status = $count > 0 ? "✅" : "❌";
        echo "$status $table: $count records<br>";
    } else {
        echo "❌ Error checking $table: " . $conn->error . "<br>";
    }
}

echo "<h3>🔍 Detailed Data Check:</h3>";

// Check users
$result = $conn->query("SELECT username, role FROM users LIMIT 5");
echo "<h4>Users:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "👤 {$row['username']} ({$row['role']})<br>";
}

// Check guardians
$result = $conn->query("SELECT fullname, email FROM guardians LIMIT 5");
echo "<h4>Guardians:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "👨‍👩‍👧‍👦 {$row['fullname']} ({$row['email']})<br>";
}

// Check children
$result = $conn->query("
    SELECT c.first_name, c.last_name, c.child_id, g.fullname as guardian 
    FROM children c 
    JOIN guardians g ON c.guardian_id = g.id 
    LIMIT 5
");
echo "<h4>Children:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "👶 {$row['first_name']} {$row['last_name']} (ID: {$row['child_id']}) - Guardian: {$row['guardian']}<br>";
}

// Check growth records
$result = $conn->query("
    SELECT c.first_name, c.last_name, COUNT(gr.id) as count 
    FROM children c 
    LEFT JOIN growth_records gr ON c.id = gr.child_id 
    GROUP BY c.id 
    HAVING count > 0
    LIMIT 5
");
echo "<h4>Growth Records:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "📈 {$row['first_name']} {$row['last_name']}: {$row['count']} records<br>";
}

// Check vaccination records
$result = $conn->query("
    SELECT c.first_name, c.last_name, COUNT(vr.id) as count 
    FROM children c 
    LEFT JOIN vaccination_records vr ON c.id = vr.child_id 
    GROUP BY c.id 
    HAVING count > 0
    LIMIT 5
");
echo "<h4>Vaccination Records:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "💉 {$row['first_name']} {$row['last_name']}: {$row['count']} records<br>";
}

// Check vaccines
$result = $conn->query("SELECT name, v_id FROM vaccines LIMIT 5");
echo "<h4>Vaccines:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "💊 {$row['name']} (ID: {$row['v_id']})<br>";
}

// Test API endpoints
echo "<h3>🔗 API Endpoint Test:</h3>";

// Test children endpoint
$children_url = "http://localhost/My-vaccine/php-backend/children/read.php";
echo "<h4>Testing Children API:</h4>";
echo "URL: $children_url<br>";

// Test growth records endpoint
$growth_url = "http://localhost/My-vaccine/php-backend/growth_records/read.php";
echo "<h4>Testing Growth Records API:</h4>";
echo "URL: $growth_url<br>";

// Test vaccination records endpoint
$vaccination_url = "http://localhost/My-vaccine/php-backend/vaccination_records/read.php";
echo "<h4>Testing Vaccination Records API:</h4>";
echo "URL: $vaccination_url<br>";

echo "<h3>📝 Instructions:</h3>";
echo "1. Make sure your database is running<br>";
echo "2. Run the schema.sql file first to create tables<br>";
echo "3. Run load_sample_data.php to load the sample data<br>";
echo "4. Test the API endpoints above<br>";
echo "5. Check your frontend application<br>";

echo "<h3>🎯 Expected Results:</h3>";
echo "✅ Users: 5 records (admin, 3 guardians, 1 worker)<br>";
echo "✅ Health Facilities: 3 records<br>";
echo "✅ Healthcare Workers: 1 record<br>";
echo "✅ Guardians: 3 records<br>";
echo "✅ Children: 5 records<br>";
echo "✅ Vaccines: 5 records<br>";
echo "✅ Growth Records: 36 records (21 for Alice + 15 for Bob)<br>";
echo "✅ Vaccination Records: 30 records (15 for Alice + 15 for Bob)<br>";
echo "✅ Notifications: 5 records<br>";
?> 