<?php
require 'db.php';

echo "<h2>Adding Growth Records and Vaccination Records</h2>";

// Add Growth Records for Alice (C001) - 21 records
$growth_records_alice = [
    ['2020-02-15', 4.2, 55.0],
    ['2020-05-15', 6.1, 62.0],
    ['2020-08-15', 7.5, 68.0],
    ['2020-11-15', 8.2, 72.0],
    ['2021-02-15', 9.1, 76.0],
    ['2021-05-15', 10.0, 80.0],
    ['2021-08-15', 11.2, 84.0],
    ['2021-11-15', 12.1, 88.0],
    ['2022-02-15', 13.0, 92.0],
    ['2022-05-15', 14.2, 96.0],
    ['2022-08-15', 15.1, 100.0],
    ['2022-11-15', 16.0, 104.0],
    ['2023-02-15', 17.2, 108.0],
    ['2023-05-15', 18.1, 112.0],
    ['2023-08-15', 19.0, 116.0],
    ['2023-11-15', 20.1, 120.0],
    ['2024-02-15', 21.0, 124.0],
    ['2024-05-15', 22.1, 128.0],
    ['2024-08-15', 23.0, 132.0],
    ['2024-11-15', 24.1, 136.0],
    ['2025-01-15', 25.0, 140.0]
];

// Add Growth Records for Bob (C002) - 15 records
$growth_records_bob = [
    ['2021-04-20', 5.2, 58.0],
    ['2021-07-20', 6.8, 64.0],
    ['2021-10-20', 7.9, 70.0],
    ['2022-01-20', 8.6, 74.0],
    ['2022-04-20', 9.5, 78.0],
    ['2022-07-20', 10.4, 82.0],
    ['2022-10-20', 11.3, 86.0],
    ['2023-01-20', 12.2, 90.0],
    ['2023-04-20', 13.1, 94.0],
    ['2023-07-20', 14.0, 98.0],
    ['2023-10-20', 15.1, 102.0],
    ['2024-01-20', 16.0, 106.0],
    ['2024-04-20', 17.1, 110.0],
    ['2024-07-20', 18.0, 114.0],
    ['2024-10-20', 19.1, 118.0],
    ['2025-01-20', 20.0, 122.0]
];

// Add Vaccination Records for Alice (C001) - 15 records
$vaccination_records_alice = [
    ['VR001', 1, 1, '2020-01-20'],
    ['VR002', 1, 4, '2020-01-20'],
    ['VR003', 1, 4, '2020-02-20'],
    ['VR004', 1, 4, '2020-06-20'],
    ['VR005', 1, 4, '2020-12-20'],
    ['VR006', 1, 2, '2020-02-15'],
    ['VR007', 1, 2, '2020-04-15'],
    ['VR008', 1, 2, '2020-06-15'],
    ['VR009', 1, 2, '2020-08-15'],
    ['VR010', 1, 2, '2021-08-15'],
    ['VR011', 1, 5, '2020-02-15'],
    ['VR012', 1, 5, '2020-04-15'],
    ['VR013', 1, 5, '2020-06-15'],
    ['VR014', 1, 5, '2020-08-15'],
    ['VR015', 1, 3, '2021-01-15']
];

// Add Vaccination Records for Bob (C002) - 15 records
$vaccination_records_bob = [
    ['VR016', 2, 1, '2021-03-25'],
    ['VR017', 2, 4, '2021-03-25'],
    ['VR018', 2, 4, '2021-04-25'],
    ['VR019', 2, 4, '2021-08-25'],
    ['VR020', 2, 4, '2022-02-25'],
    ['VR021', 2, 2, '2021-04-20'],
    ['VR022', 2, 2, '2021-06-20'],
    ['VR023', 2, 2, '2021-08-20'],
    ['VR024', 2, 2, '2021-10-20'],
    ['VR025', 2, 2, '2022-10-20'],
    ['VR026', 2, 5, '2021-04-20'],
    ['VR027', 2, 5, '2021-06-20'],
    ['VR028', 2, 5, '2021-08-20'],
    ['VR029', 2, 5, '2021-10-20'],
    ['VR030', 2, 3, '2022-03-20']
];

// Insert Growth Records
echo "<h3>Adding Growth Records...</h3>";
$stmt = $conn->prepare("INSERT INTO growth_records (child_id, date_recorded, weight, height, recorded_by) VALUES (?, ?, ?, ?, 1)");

$total_growth = 0;
foreach ($growth_records_alice as $record) {
    $stmt->bind_param("isdd", $child_id, $record[0], $record[1], $record[2]);
    $child_id = 1; // Alice's child_id
    if ($stmt->execute()) {
        $total_growth++;
    }
}

foreach ($growth_records_bob as $record) {
    $stmt->bind_param("isdd", $child_id, $record[0], $record[1], $record[2]);
    $child_id = 2; // Bob's child_id
    if ($stmt->execute()) {
        $total_growth++;
    }
}

echo "✅ Added $total_growth growth records<br>";

// Insert Vaccination Records
echo "<h3>Adding Vaccination Records...</h3>";
$stmt = $conn->prepare("INSERT INTO vaccination_records (record_id, child_id, vaccine_id, dose_number, administration_date, administered_by) VALUES (?, ?, ?, ?, ?, 1)");

$total_vaccinations = 0;
foreach ($vaccination_records_alice as $record) {
    $stmt->bind_param("siiss", $record[0], $child_id, $record[2], $dose_number, $record[3]);
    $child_id = 1; // Alice's child_id
    $dose_number = 1; // Default dose number
    if ($stmt->execute()) {
        $total_vaccinations++;
    }
}

foreach ($vaccination_records_bob as $record) {
    $stmt->bind_param("siiss", $record[0], $child_id, $record[2], $dose_number, $record[3]);
    $child_id = 2; // Bob's child_id
    $dose_number = 1; // Default dose number
    if ($stmt->execute()) {
        $total_vaccinations++;
    }
}

echo "✅ Added $total_vaccinations vaccination records<br>";

// Verify the data
echo "<h3>Verifying Data...</h3>";

$result = $conn->query("SELECT COUNT(*) as count FROM growth_records WHERE child_id IN (1, 2)");
$growth_count = $result->fetch_assoc()['count'];
echo "Growth records for Alice and Bob: $growth_count<br>";

$result = $conn->query("SELECT COUNT(*) as count FROM vaccination_records WHERE child_id IN (1, 2)");
$vaccination_count = $result->fetch_assoc()['count'];
echo "Vaccination records for Alice and Bob: $vaccination_count<br>";

$conn->close();
echo "<br>✅ Sample data added successfully!";
?> 