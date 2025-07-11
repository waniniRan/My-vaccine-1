<?php
require 'db.php';

echo json_encode([
    'status' => 'success',
    'message' => 'PHP backend is working!',
    'database' => $conn ? 'Connected' : 'Failed to connect',
    'timestamp' => date('Y-m-d H:i:s')
]);
?> 