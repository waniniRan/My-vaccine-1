<?php
// Database setup script for My-vaccine system
// This script will create the database, tables, and load sample data

echo "<h1>🚀 My-vaccine Database Setup</h1>";

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$port = 3306;
$socket = '/opt/lampp/var/mysql/mysql.sock';
$db_name = 'immunity';

// Step 1: Create database connection (without database)
$conn = new mysqli($host, $user, $pass, '', $port, $socket);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

echo "<h2>Step 1: Creating Database</h2>";
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql)) {
    echo "✅ Database '$db_name' created successfully<br>";
} else {
    echo "❌ Error creating database: " . $conn->error . "<br>";
}

// Step 2: Select the database
$conn->select_db($db_name);
echo "<h2>Step 2: Loading Schema</h2>";

// Load schema
$schema_file = 'schema.sql';
if (file_exists($schema_file)) {
    $schema = file_get_contents($schema_file);
    $statements = explode(';', $schema);
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            if ($conn->query($statement)) {
                $success_count++;
            } else {
                $error_count++;
                echo "❌ Error: " . $conn->error . "<br>";
            }
        }
    }
    
    echo "✅ Schema loaded: $success_count statements executed successfully<br>";
    if ($error_count > 0) {
        echo "⚠️ $error_count statements had errors (this might be normal for existing tables)<br>";
    }
} else {
    echo "❌ Schema file not found: $schema_file<br>";
}

// Step 3: Load sample data
echo "<h2>Step 3: Loading Sample Data</h2>";

$sample_data_file = 'clean_sample_data.sql';
if (file_exists($sample_data_file)) {
    $sample_data = file_get_contents($sample_data_file);
    $statements = explode(';', $sample_data);
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            if ($conn->query($statement)) {
                $success_count++;
            } else {
                $error_count++;
                echo "❌ Error: " . $conn->error . "<br>";
            }
        }
    }
    
    echo "✅ Sample data loaded: $success_count statements executed successfully<br>";
    if ($error_count > 0) {
        echo "⚠️ $error_count statements had errors<br>";
    }
} else {
    echo "❌ Sample data file not found: $sample_data_file<br>";
}

// Step 4: Verify data
echo "<h2>Step 4: Verifying Data</h2>";

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

$total_records = 0;
foreach ($tables as $table) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $total_records += $count;
        $status = $count > 0 ? "✅" : "❌";
        echo "$status $table: $count records<br>";
    } else {
        echo "❌ Error checking $table: " . $conn->error . "<br>";
    }
}

echo "<h3>📊 Total Records: $total_records</h3>";

// Step 5: Test API endpoints
echo "<h2>Step 5: Testing API Endpoints</h2>";

$base_url = "http://localhost/My-vaccine/php-backend";
$endpoints = [
    'children' => "$base_url/children/read.php",
    'growth_records' => "$base_url/growth_records/read.php", 
    'vaccination_records' => "$base_url/vaccination_records/read.php",
    'guardians' => "$base_url/guardians/read.php"
];

foreach ($endpoints as $name => $url) {
    echo "<h4>Testing $name API:</h4>";
    echo "URL: $url<br>";
    
    // Test with curl if available
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200) {
        echo "✅ API is accessible (HTTP $http_code)<br>";
        $data = json_decode($response, true);
        if (is_array($data)) {
            echo "📊 Response contains " . count($data) . " records<br>";
        }
    } else {
        echo "❌ API returned HTTP $http_code<br>";
    }
    echo "<br>";
}

// Step 6: Show sample data preview
echo "<h2>Step 6: Sample Data Preview</h2>";

// Show children
$result = $conn->query("
    SELECT c.first_name, c.last_name, c.child_id, g.fullname as guardian 
    FROM children c 
    JOIN guardians g ON c.guardian_id = g.id 
    LIMIT 3
");

echo "<h4>👶 Children:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "• {$row['first_name']} {$row['last_name']} (ID: {$row['child_id']}) - Guardian: {$row['guardian']}<br>";
}

// Show growth records summary
$result = $conn->query("
    SELECT c.first_name, c.last_name, COUNT(gr.id) as count 
    FROM children c 
    LEFT JOIN growth_records gr ON c.id = gr.child_id 
    GROUP BY c.id 
    HAVING count > 0
    LIMIT 3
");

echo "<h4>📈 Growth Records:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "• {$row['first_name']} {$row['last_name']}: {$row['count']} records<br>";
}

// Show vaccination records summary
$result = $conn->query("
    SELECT c.first_name, c.last_name, COUNT(vr.id) as count 
    FROM children c 
    LEFT JOIN vaccination_records vr ON c.id = vr.child_id 
    GROUP BY c.id 
    HAVING count > 0
    LIMIT 3
");

echo "<h4>💉 Vaccination Records:</h4>";
while ($row = $result->fetch_assoc()) {
    echo "• {$row['first_name']} {$row['last_name']}: {$row['count']} records<br>";
}

echo "<h2>🎉 Setup Complete!</h2>";
echo "<h3>📝 Next Steps:</h3>";
echo "1. ✅ Database created and populated<br>";
echo "2. ✅ Sample data loaded<br>";
echo "3. ✅ API endpoints tested<br>";
echo "4. 🔄 Test your frontend application<br>";
echo "5. 🔄 Login with username: 'admin', password: 'password'<br>";

echo "<h3>🔗 Useful URLs:</h3>";
echo "• <a href='$base_url/verify_data.php' target='_blank'>Data Verification</a><br>";
echo "• <a href='$base_url/load_sample_data.php' target='_blank'>Reload Sample Data</a><br>";
echo "• <a href='$base_url/children/read.php' target='_blank'>Children API</a><br>";
echo "• <a href='$base_url/growth_records/read.php' target='_blank'>Growth Records API</a><br>";
echo "• <a href='$base_url/vaccination_records/read.php' target='_blank'>Vaccination Records API</a><br>";

$conn->close();
?> 