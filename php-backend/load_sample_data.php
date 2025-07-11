<?php
require 'db.php';

echo "<h2>Loading Sample Data</h2>";

// Function to execute SQL and show results
function executeSQL($conn, $sql, $description) {
    echo "<h3>$description</h3>";
    if ($conn->query($sql)) {
        echo "✅ Success: " . $conn->affected_rows . " rows affected<br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
        return false;
    }
    return true;
}

// Read and execute the sample data file
$sqlFile = 'clean_sample_data.sql';
if (file_exists($sqlFile)) {
    $sql = file_get_contents($sqlFile);
    
    // Split by semicolon and execute each statement
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            if ($conn->query($statement) === FALSE) {
                echo "❌ Error executing: " . $conn->error . "<br>";
                echo "Statement: " . substr($statement, 0, 100) . "...<br><br>";
            }
        }
    }
    
    echo "<h3>✅ Sample data loaded successfully!</h3>";
} else {
    echo "❌ Error: $sqlFile not found";
}

// Verify the data was loaded
echo "<h3>Verifying Data Load:</h3>";

$tables = [
    'users' => 'SELECT COUNT(*) as count FROM users',
    'health_facilities' => 'SELECT COUNT(*) as count FROM health_facilities',
    'healthcare_workers' => 'SELECT COUNT(*) as count FROM healthcare_workers',
    'guardians' => 'SELECT COUNT(*) as count FROM guardians',
    'children' => 'SELECT COUNT(*) as count FROM children',
    'vaccines' => 'SELECT COUNT(*) as count FROM vaccines',
    'growth_records' => 'SELECT COUNT(*) as count FROM growth_records',
    'vaccination_records' => 'SELECT COUNT(*) as count FROM vaccination_records',
    'notifications' => 'SELECT COUNT(*) as count FROM notifications'
];

foreach ($tables as $table => $query) {
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        echo "📊 $table: {$row['count']} records<br>";
    } else {
        echo "❌ Error checking $table: " . $conn->error . "<br>";
    }
}

// Show some sample data
echo "<h3>Sample Data Preview:</h3>";

// Show children with their guardians
$result = $conn->query("
    SELECT c.first_name, c.last_name, c.child_id, g.fullname as guardian_name 
    FROM children c 
    JOIN guardians g ON c.guardian_id = g.id 
    LIMIT 5
");

echo "<h4>Children and their Guardians:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "👶 {$row['first_name']} {$row['last_name']} (ID: {$row['child_id']}) - Guardian: {$row['guardian_name']}<br>";
}

// Show growth records count by child
$result = $conn->query("
    SELECT c.first_name, c.last_name, COUNT(gr.id) as growth_count 
    FROM children c 
    LEFT JOIN growth_records gr ON c.id = gr.child_id 
    GROUP BY c.id 
    LIMIT 5
");

echo "<h4>Growth Records by Child:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "📈 {$row['first_name']} {$row['last_name']}: {$row['growth_count']} growth records<br>";
}

// Show vaccination records count by child
$result = $conn->query("
    SELECT c.first_name, c.last_name, COUNT(vr.id) as vaccine_count 
    FROM children c 
    LEFT JOIN vaccination_records vr ON c.id = vr.child_id 
    GROUP BY c.id 
    LIMIT 5
");

echo "<h4>Vaccination Records by Child:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "💉 {$row['first_name']} {$row['last_name']}: {$row['vaccine_count']} vaccination records<br>";
}

echo "<h3>🎉 Data loading complete! You can now test the application.</h3>";
?> 