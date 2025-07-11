<?php
require '../db.php';

$user_id = $_GET['user_id'] ?? null;
$national_id = $_GET['national_id'] ?? null;

if ($user_id) {
    // Get specific guardian by user_id (for logged-in user)
    $stmt = $conn->prepare("SELECT * FROM guardians WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $guardian = $result->fetch_assoc();
    echo json_encode($guardian ?: []);
    $stmt->close();
} else if ($national_id) {
    // Get guardian by national_id
    $stmt = $conn->prepare("SELECT * FROM guardians WHERE national_id = ?");
    $stmt->bind_param("s", $national_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $guardian = $result->fetch_assoc();
    echo json_encode($guardian ?: []);
    $stmt->close();
} else {
    // For admin purposes, return all guardians
    $result = $conn->query("SELECT * FROM guardians");
    $guardians = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($guardians);
}
$conn->close();
?> 