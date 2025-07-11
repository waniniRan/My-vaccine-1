<?php
require '../db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM health_facilities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $facility = $result->fetch_assoc();
    echo json_encode($facility ?: []);
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM health_facilities");
    $facilities = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($facilities);
}
$conn->close(); 