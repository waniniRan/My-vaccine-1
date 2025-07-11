<?php
// Add CORS headers for frontend integration
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = 'localhost';
$db   = 'immunity';
$user = 'root';
$pass = '';
$port = 3306;
$socket = '/opt/lampp/var/mysql/mysql.sock';

$conn = new mysqli($host, $user, $pass, $db, $port, $socket);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 