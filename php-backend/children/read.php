<?php
require '../db.php';

$id = $_GET['id'] ?? null;
$guardian_id = $_GET['guardian_id'] ?? null;

if ($id) {
    // Get specific child by child_id
    $stmt = $conn->prepare("SELECT * FROM children WHERE child_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $child = $result->fetch_assoc();
    echo json_encode($child ?: []);
    $stmt->close();
} else if ($guardian_id) {
    // Get children for specific guardian
    $stmt = $conn->prepare("SELECT * FROM children WHERE guardian_id = ?");
    $stmt->bind_param("i", $guardian_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $children = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($children);
    $stmt->close();
} else {
    // For admin purposes, return all children
    $result = $conn->query("SELECT * FROM children");
    $children = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($children);
}
$conn->close(); 