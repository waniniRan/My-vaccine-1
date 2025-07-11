<?php
require '../db.php';

$child_id = $_GET['child_id'] ?? null;
if ($child_id) {
    // If not numeric, resolve string child_id to numeric id
    if (!is_numeric($child_id)) {
        $stmt = $conn->prepare("SELECT id FROM children WHERE child_id = ?");
        $stmt->bind_param("s", $child_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        if ($row) {
            $child_id = $row['id'];
        } else {
            echo json_encode([]); // Not found
            $conn->close();
            exit;
        }
    }
    $stmt = $conn->prepare("SELECT * FROM vaccination_records WHERE child_id = ?");
    $stmt->bind_param("i", $child_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $records = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($records);
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM vaccination_records");
    $records = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($records);
}
$conn->close(); 