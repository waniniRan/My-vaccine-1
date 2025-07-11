<?php
require 'db.php';

echo "<h2>Adding Sample Growth Records and Vaccination Records</h2>";

// Add Growth Records for Alice (C001) - 21 records from birth to age 5
$growth_records_alice = [
    ['C001', '2020-02-15', 55.0, 4.2, 38.5, 13.9],
    ['C001', '2020-05-15', 62.0, 6.1, 41.0, 15.9],
    ['C001', '2020-08-15', 68.0, 7.5, 43.5, 16.2],
    ['C001', '2020-11-15', 72.0, 8.2, 45.0, 15.8],
    ['C001', '2021-02-15', 76.0, 9.1, 46.5, 15.8],
    ['C001', '2021-05-15', 80.0, 10.0, 47.5, 15.6],
    ['C001', '2021-08-15', 84.0, 11.2, 48.5, 15.9],
    ['C001', '2021-11-15', 88.0, 12.1, 49.0, 15.6],
    ['C001', '2022-02-15', 92.0, 13.0, 49.5, 15.4],
    ['C001', '2022-05-15', 96.0, 14.2, 50.0, 15.4],
    ['C001', '2022-08-15', 100.0, 15.1, 50.5, 15.1],
    ['C001', '2022-11-15', 104.0, 16.0, 51.0, 14.8],
    ['C001', '2023-02-15', 108.0, 17.2, 51.5, 14.8],
    ['C001', '2023-05-15', 112.0, 18.1, 52.0, 14.4],
    ['C001', '2023-08-15', 116.0, 19.0, 52.5, 14.1],
    ['C001', '2023-11-15', 120.0, 20.1, 53.0, 14.0],
    ['C001', '2024-02-15', 124.0, 21.0, 53.5, 13.7],
    ['C001', '2024-05-15', 128.0, 22.1, 54.0, 13.5],
    ['C001', '2024-08-15', 132.0, 23.0, 54.5, 13.2],
    ['C001', '2024-11-15', 136.0, 24.1, 55.0, 13.0],
    ['C001', '2025-01-15', 140.0, 25.0, 55.5, 12.8]
];

// Add Growth Records for Bob (C002) - 15 records from birth to age 4
$growth_records_bob = [
    ['C002', '2021-04-20', 58.0, 5.2, 39.0, 15.5],
    ['C002', '2021-07-20', 64.0, 6.8, 41.5, 16.6],
    ['C002', '2021-10-20', 70.0, 7.9, 43.5, 16.1],
    ['C002', '2022-01-20', 74.0, 8.6, 44.5, 15.7],
    ['C002', '2022-04-20', 78.0, 9.5, 45.5, 15.6],
    ['C002', '2022-07-20', 82.0, 10.4, 46.5, 15.5],
    ['C002', '2022-10-20', 86.0, 11.3, 47.0, 15.3],
    ['C002', '2023-01-20', 90.0, 12.2, 47.5, 15.1],
    ['C002', '2023-04-20', 94.0, 13.1, 48.0, 14.8],
    ['C002', '2023-07-20', 98.0, 14.0, 48.5, 14.6],
    ['C002', '2023-10-20', 102.0, 15.1, 49.0, 14.5],
    ['C002', '2024-01-20', 106.0, 16.0, 49.5, 14.2],
    ['C002', '2024-04-20', 110.0, 17.1, 50.0, 14.1],
    ['C002', '2024-07-20', 114.0, 18.0, 50.5, 13.8],
    ['C002', '2024-10-20', 118.0, 19.1, 51.0, 13.7],
    ['C002', '2025-01-20', 122.0, 20.0, 51.5, 13.4]
];

// Add Vaccination Records for Alice (C001) - 15 records
$vaccination_records_alice = [
    ['VR001', 'C001', 'VAC001', 1, '2020-01-20', NULL],
    ['VR002', 'C001', 'VAC004', 1, '2020-01-20', '2020-02-20'],
    ['VR003', 'C001', 'VAC004', 2, '2020-02-20', '2020-06-20'],
    ['VR004', 'C001', 'VAC004', 3, '2020-06-20', '2020-12-20'],
    ['VR005', 'C001', 'VAC004', 4, '2020-12-20', '2025-12-20'],
    ['VR006', 'C001', 'VAC002', 1, '2020-02-15', '2020-04-15'],
    ['VR007', 'C001', 'VAC002', 2, '2020-04-15', '2020-06-15'],
    ['VR008', 'C001', 'VAC002', 3, '2020-06-15', '2020-08-15'],
    ['VR009', 'C001', 'VAC002', 4, '2020-08-15', '2021-08-15'],
    ['VR010', 'C001', 'VAC002', 5, '2021-08-15', '2025-08-15'],
    ['VR011', 'C001', 'VAC005', 1, '2020-02-15', '2020-04-15'],
    ['VR012', 'C001', 'VAC005', 2, '2020-04-15', '2020-06-15'],
    ['VR013', 'C001', 'VAC005', 3, '2020-06-15', '2020-08-15'],
    ['VR014', 'C001', 'VAC005', 4, '2020-08-15', '2025-08-15'],
    ['VR015', 'C001', 'VAC003', 1, '2021-01-15', '2025-01-15']
];

// Add Vaccination Records for Bob (C002) - 15 records
$vaccination_records_bob = [
    ['VR016', 'C002', 'VAC001', 1, '2021-03-25', NULL],
    ['VR017', 'C002', 'VAC004', 1, '2021-03-25', '2021-04-25'],
    ['VR018', 'C002', 'VAC004', 2, '2021-04-25', '2021-08-25'],
    ['VR019', 'C002', 'VAC004', 3, '2021-08-25', '2022-02-25'],
    ['VR020', 'C002', 'VAC004', 4, '2022-02-25', '2026-02-25'],
    ['VR021', 'C002', 'VAC002', 1, '2021-04-20', '2021-06-20'],
    ['VR022', 'C002', 'VAC002', 2, '2021-06-20', '2021-08-20'],
    ['VR023', 'C002', 'VAC002', 3, '2021-08-20', '2021-10-20'],
    ['VR024', 'C002', 'VAC002', 4, '2021-10-20', '2022-10-20'],
    ['VR025', 'C002', 'VAC002', 5, '2022-10-20', '2026-10-20'],
    ['VR026', 'C002', 'VAC005', 1, '2021-04-20', '2021-06-20'],
    ['VR027', 'C002', 'VAC005', 2, '2021-06-20', '2021-08-20'],
    ['VR028', 'C002', 'VAC005', 3, '2021-08-20', '2021-10-20'],
    ['VR029', 'C002', 'VAC005', 4, '2021-10-20', '2026-10-20'],
    ['VR030', 'C002', 'VAC003', 1, '2022-03-20', '2026-03-20']
];

// Insert Growth Records
echo "<h3>Adding Growth Records...</h3>";
$stmt = $conn->prepare("INSERT INTO growth_records (child_id, date_recorded, height, weight, head_circumference, bmi, recorded_by, date_created, is_active) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), 1)");

$total_growth = 0;
foreach ($growth_records_alice as $record) {
    $stmt->bind_param("ssdddd", $record[0], $record[1], $record[2], $record[3], $record[4], $record[5]);
    if ($stmt->execute()) {
        $total_growth++;
    }
}

foreach ($growth_records_bob as $record) {
    $stmt->bind_param("ssdddd", $record[0], $record[1], $record[2], $record[3], $record[4], $record[5]);
    if ($stmt->execute()) {
        $total_growth++;
    }
}

echo "✅ Added $total_growth growth records<br>";

// Insert Vaccination Records
echo "<h3>Adding Vaccination Records...</h3>";
$stmt = $conn->prepare("INSERT INTO vaccination_records (record_id, child_id, vaccine_id, dose_number, administration_date, next_due_date, facility_id, administered_by, date_created, is_active) VALUES (?, ?, ?, ?, ?, ?, 'HF001', 1, NOW(), 1)");

$total_vaccinations = 0;
foreach ($vaccination_records_alice as $record) {
    $stmt->bind_param("sssis", $record[0], $record[1], $record[2], $record[3], $record[4], $record[5]);
    if ($stmt->execute()) {
        $total_vaccinations++;
    }
}

foreach ($vaccination_records_bob as $record) {
    $stmt->bind_param("sssis", $record[0], $record[1], $record[2], $record[3], $record[4], $record[5]);
    if ($stmt->execute()) {
        $total_vaccinations++;
    }
}

echo "✅ Added $total_vaccinations vaccination records<br>";

// Verify the data
echo "<h3>Verifying Data...</h3>";

$result = $conn->query("SELECT COUNT(*) as count FROM growth_records WHERE child_id IN ('C001', 'C002')");
$growth_count = $result->fetch_assoc()['count'];
echo "Growth records for Alice and Bob: $growth_count<br>";

$result = $conn->query("SELECT COUNT(*) as count FROM vaccination_records WHERE child_id IN ('C001', 'C002')");
$vaccination_count = $result->fetch_assoc()['count'];
echo "Vaccination records for Alice and Bob: $vaccination_count<br>";

$conn->close();
echo "<br>✅ Sample data added successfully!";
?> 