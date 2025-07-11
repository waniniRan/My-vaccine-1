-- Sample data for testing

-- Insert sample users
INSERT INTO users (username, password, email, role) VALUES
('123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian1@example.com', 'GUARDIAN'),
('987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian2@example.com', 'GUARDIAN'),
('456789123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian3@example.com', 'GUARDIAN'),
('W001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'worker1@example.com', 'WORKER'),
('A001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'SYSTEM_ADMIN');

-- Insert sample health facility
INSERT INTO health_facilities (facility_id, name, facility_type, location, phone, email) VALUES
('H001', 'City General Hospital', 'HOSPITAL', 'Downtown', '123-456-7890', 'info@cityhospital.com');

-- Insert sample healthcare worker
INSERT INTO healthcare_workers (user_id, worker_id, worker_username, fullname, email, phone_number, position, facility_id, status) VALUES
(5, 'W001', 'worker1', 'Dr. John Smith', 'john@hospital.com', '123-456-7890', 'doctor', 1, 'active');

-- Insert sample guardians
INSERT INTO guardians (user_id, national_id, fullname, email, phone_number, registered_by) VALUES
(2, '123456789', 'Jane Doe', 'jane@example.com', '987-654-3210', 1),
(3, '987654321', 'Mary Johnson', 'mary@example.com', '555-123-4567', 1),
(4, '456789123', 'Robert Wilson', 'robert@example.com', '777-888-9999', 1);

-- Insert sample children for different guardians
INSERT INTO children (child_id, first_name, last_name, date_of_birth, gender, birth_weight, birth_height, guardian_id, registered_by) VALUES
('C001', 'Alice', 'Doe', '2020-01-15', 'F', 3.2, 50.0, 1, 1),
('C002', 'Bob', 'Doe', '2021-03-20', 'M', 3.5, 52.0, 1, 1),
('C003', 'Emma', 'Johnson', '2019-06-10', 'F', 3.0, 48.0, 2, 1),
('C004', 'David', 'Johnson', '2022-09-05', 'M', 3.8, 54.0, 2, 1),
('C005', 'Sophie', 'Wilson', '2021-12-03', 'F', 3.1, 49.0, 3, 1);

-- Insert sample growth records
INSERT INTO growth_records (child_id, date_recorded, weight, height, recorded_by) VALUES
(1, '2024-01-15', 12.5, 75.0, 1),
(1, '2024-02-15', 13.2, 77.0, 1),
(2, '2024-01-20', 10.8, 70.0, 1),
(3, '2024-01-25', 15.2, 85.0, 1),
(4, '2024-02-01', 8.5, 65.0, 1),
(5, '2024-01-30', 11.8, 72.0, 1);

-- Insert sample vaccines
INSERT INTO vaccines (name, v_id, description, dosage, disease_prevented, recommended_age) VALUES
('BCG', 'V001', 'Bacillus Calmette-Guérin vaccine', 'Single dose', 'Tuberculosis', '0-2 months'),
('DPT', 'V002', 'Diphtheria, Pertussis, Tetanus vaccine', '3 doses', 'Diphtheria, Pertussis, Tetanus', '2-4 months'),
('MMR', 'V003', 'Measles, Mumps, Rubella vaccine', '2 doses', 'Measles, Mumps, Rubella', '12-15 months');

-- Insert sample vaccination records
INSERT INTO vaccination_records (record_id, child_id, vaccine_id, dose_number, administered_by) VALUES
('VR001', 1, 1, 1, 1),
('VR002', 1, 2, 1, 1),
('VR003', 2, 1, 1, 1),
('VR004', 3, 1, 1, 1),
('VR005', 3, 2, 1, 1),
('VR006', 4, 1, 1, 1),
('VR007', 5, 1, 1, 1);

-- Insert sample notifications for different guardians
INSERT INTO notifications (guardian_id, notification_type, message) VALUES
(1, 'WEEK_BEFORE', 'Reminder: Alice has a vaccination appointment next week'),
(1, 'TWO_DAYS_BEFORE', 'Reminder: Bob has a vaccination appointment in 2 days'),
(2, 'WEEK_BEFORE', 'Reminder: Emma has a vaccination appointment next week'),
(2, 'MISSED_APPOINTMENT', 'David missed his vaccination appointment'),
(3, 'WEEK_BEFORE', 'Reminder: Sophie has a vaccination appointment next week');

-- Sample data for the vaccine management system

-- Users
INSERT INTO users (username, password, email, role, must_change_password, created_at) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'ADMIN', 0, NOW()),
('guardian1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian1@example.com', 'GUARDIAN', 1, NOW()),
('guardian2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian2@example.com', 'GUARDIAN', 1, NOW()),
('guardian3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian3@example.com', 'GUARDIAN', 1, NOW());

-- Guardians
INSERT INTO guardians (user_id, national_id, fullname, email, phone_number, temporary_password, password_changed, date_registered, registered_by, is_active) VALUES
(2, '123456789', 'Jane Doe', 'jane@example.com', '987-654-3210', '', 1, NOW(), 1, 1),
(3, '987654321', 'John Johnson', 'john@example.com', '123-456-7890', '', 1, NOW(), 1, 1),
(4, '456789123', 'Sarah Wilson', 'sarah@example.com', '555-123-4567', '', 1, NOW(), 1, 1);

-- Health Facilities
INSERT INTO health_facilities (facility_id, facility_name, address, phone_number, email, registered_by, date_registered, is_active) VALUES
('HF001', 'City General Hospital', '123 Main St, City', '555-0101', 'info@cityhospital.com', 1, NOW(), 1),
('HF002', 'Community Health Center', '456 Oak Ave, Town', '555-0202', 'info@communityhealth.com', 1, NOW(), 1),
('HF003', 'Pediatric Clinic', '789 Pine Rd, Village', '555-0303', 'info@pediatricclinic.com', 1, NOW(), 1);

-- Vaccines
INSERT INTO vaccines (vaccine_id, vaccine_name, description, manufacturer, storage_requirements, registered_by, date_registered, is_active) VALUES
('VAC001', 'BCG', 'Bacillus Calmette-Guérin vaccine for tuberculosis', 'VaccineCorp', '2-8°C', 1, NOW(), 1),
('VAC002', 'DPT', 'Diphtheria, Pertussis, Tetanus vaccine', 'VaccineCorp', '2-8°C', 1, NOW(), 1),
('VAC003', 'MMR', 'Measles, Mumps, Rubella vaccine', 'VaccineCorp', '2-8°C', 1, NOW(), 1),
('VAC004', 'Hepatitis B', 'Hepatitis B vaccine', 'VaccineCorp', '2-8°C', 1, NOW(), 1),
('VAC005', 'Polio', 'Polio vaccine', 'VaccineCorp', '2-8°C', 1, NOW(), 1);

-- Children
INSERT INTO children (child_id, first_name, last_name, date_of_birth, gender, birth_weight, birth_height, guardian_id, registered_by, date_registered, is_active) VALUES
('C001', 'Alice', 'Doe', '2020-01-15', 'F', 3.20, 50.00, 1, 1, NOW(), 1),
('C002', 'Bob', 'Doe', '2021-03-20', 'M', 3.50, 52.00, 1, 1, NOW(), 1),
('C003', 'Emma', 'Johnson', '2019-06-10', 'F', 3.00, 48.00, 2, 1, NOW(), 1),
('C004', 'David', 'Johnson', '2022-09-05', 'M', 3.80, 54.00, 2, 1, NOW(), 1),
('C005', 'Sophie', 'Wilson', '2021-12-03', 'F', 3.10, 49.00, 3, 1, NOW(), 1);

-- Growth Records
INSERT INTO growth_records (child_id, date_recorded, height, weight, head_circumference, bmi, recorded_by, date_created, is_active) VALUES
('C001', '2020-02-15', 55.0, 4.2, 38.5, 13.9, 1, NOW(), 1),
('C001', '2020-05-15', 62.0, 6.1, 41.0, 15.9, 1, NOW(), 1),
('C001', '2020-08-15', 68.0, 7.5, 43.5, 16.2, 1, NOW(), 1),
('C001', '2020-11-15', 72.0, 8.2, 45.0, 15.8, 1, NOW(), 1),
('C001', '2021-02-15', 76.0, 9.1, 46.5, 15.8, 1, NOW(), 1),
('C001', '2021-05-15', 80.0, 10.0, 47.5, 15.6, 1, NOW(), 1),
('C001', '2021-08-15', 84.0, 11.2, 48.5, 15.9, 1, NOW(), 1),
('C001', '2021-11-15', 88.0, 12.1, 49.0, 15.6, 1, NOW(), 1),
('C001', '2022-02-15', 92.0, 13.0, 49.5, 15.4, 1, NOW(), 1),
('C001', '2022-05-15', 96.0, 14.2, 50.0, 15.4, 1, NOW(), 1),
('C001', '2022-08-15', 100.0, 15.1, 50.5, 15.1, 1, NOW(), 1),
('C001', '2022-11-15', 104.0, 16.0, 51.0, 14.8, 1, NOW(), 1),
('C001', '2023-02-15', 108.0, 17.2, 51.5, 14.8, 1, NOW(), 1),
('C001', '2023-05-15', 112.0, 18.1, 52.0, 14.4, 1, NOW(), 1),
('C001', '2023-08-15', 116.0, 19.0, 52.5, 14.1, 1, NOW(), 1),
('C001', '2023-11-15', 120.0, 20.1, 53.0, 14.0, 1, NOW(), 1),
('C001', '2024-02-15', 124.0, 21.0, 53.5, 13.7, 1, NOW(), 1),
('C001', '2024-05-15', 128.0, 22.1, 54.0, 13.5, 1, NOW(), 1),
('C001', '2024-08-15', 132.0, 23.0, 54.5, 13.2, 1, NOW(), 1),
('C001', '2024-11-15', 136.0, 24.1, 55.0, 13.0, 1, NOW(), 1),
('C001', '2025-01-15', 140.0, 25.0, 55.5, 12.8, 1, NOW(), 1),
('C002', '2021-04-20', 58.0, 5.2, 39.0, 15.5, 1, NOW(), 1),
('C002', '2021-07-20', 64.0, 6.8, 41.5, 16.6, 1, NOW(), 1),
('C002', '2021-10-20', 70.0, 7.9, 43.5, 16.1, 1, NOW(), 1),
('C002', '2022-01-20', 74.0, 8.6, 44.5, 15.7, 1, NOW(), 1),
('C002', '2022-04-20', 78.0, 9.5, 45.5, 15.6, 1, NOW(), 1),
('C002', '2022-07-20', 82.0, 10.4, 46.5, 15.5, 1, NOW(), 1),
('C002', '2022-10-20', 86.0, 11.3, 47.0, 15.3, 1, NOW(), 1),
('C002', '2023-01-20', 90.0, 12.2, 47.5, 15.1, 1, NOW(), 1),
('C002', '2023-04-20', 94.0, 13.1, 48.0, 14.8, 1, NOW(), 1),
('C002', '2023-07-20', 98.0, 14.0, 48.5, 14.6, 1, NOW(), 1),
('C002', '2023-10-20', 102.0, 15.1, 49.0, 14.5, 1, NOW(), 1),
('C002', '2024-01-20', 106.0, 16.0, 49.5, 14.2, 1, NOW(), 1),
('C002', '2024-04-20', 110.0, 17.1, 50.0, 14.1, 1, NOW(), 1),
('C002', '2024-07-20', 114.0, 18.0, 50.5, 13.8, 1, NOW(), 1),
('C002', '2024-10-20', 118.0, 19.1, 51.0, 13.7, 1, NOW(), 1),
('C002', '2025-01-20', 122.0, 20.0, 51.5, 13.4, 1, NOW(), 1);

-- Vaccination Records
INSERT INTO vaccination_records (record_id, child_id, vaccine_id, dose_number, administration_date, next_due_date, facility_id, administered_by, date_created, is_active) VALUES
('VR001', 'C001', 'VAC001', 1, '2020-01-20', NULL, 'HF001', 1, NOW(), 1),
('VR002', 'C001', 'VAC004', 1, '2020-01-20', '2020-02-20', 'HF001', 1, NOW(), 1),
('VR003', 'C001', 'VAC004', 2, '2020-02-20', '2020-06-20', 'HF001', 1, NOW(), 1),
('VR004', 'C001', 'VAC004', 3, '2020-06-20', '2020-12-20', 'HF001', 1, NOW(), 1),
('VR005', 'C001', 'VAC004', 4, '2020-12-20', '2025-12-20', 'HF001', 1, NOW(), 1),
('VR006', 'C001', 'VAC002', 1, '2020-02-15', '2020-04-15', 'HF001', 1, NOW(), 1),
('VR007', 'C001', 'VAC002', 2, '2020-04-15', '2020-06-15', 'HF001', 1, NOW(), 1),
('VR008', 'C001', 'VAC002', 3, '2020-06-15', '2020-08-15', 'HF001', 1, NOW(), 1),
('VR009', 'C001', 'VAC002', 4, '2020-08-15', '2021-08-15', 'HF001', 1, NOW(), 1),
('VR010', 'C001', 'VAC002', 5, '2021-08-15', '2025-08-15', 'HF001', 1, NOW(), 1),
('VR011', 'C001', 'VAC005', 1, '2020-02-15', '2020-04-15', 'HF001', 1, NOW(), 1),
('VR012', 'C001', 'VAC005', 2, '2020-04-15', '2020-06-15', 'HF001', 1, NOW(), 1),
('VR013', 'C001', 'VAC005', 3, '2020-06-15', '2020-08-15', 'HF001', 1, NOW(), 1),
('VR014', 'C001', 'VAC005', 4, '2020-08-15', '2025-08-15', 'HF001', 1, NOW(), 1),
('VR015', 'C001', 'VAC003', 1, '2021-01-15', '2025-01-15', 'HF001', 1, NOW(), 1),
('VR016', 'C002', 'VAC001', 1, '2021-03-25', NULL, 'HF001', 1, NOW(), 1),
('VR017', 'C002', 'VAC004', 1, '2021-03-25', '2021-04-25', 'HF001', 1, NOW(), 1),
('VR018', 'C002', 'VAC004', 2, '2021-04-25', '2021-08-25', 'HF001', 1, NOW(), 1),
('VR019', 'C002', 'VAC004', 3, '2021-08-25', '2022-02-25', 'HF001', 1, NOW(), 1),
('VR020', 'C002', 'VAC004', 4, '2022-02-25', '2026-02-25', 'HF001', 1, NOW(), 1),
('VR021', 'C002', 'VAC002', 1, '2021-04-20', '2021-06-20', 'HF001', 1, NOW(), 1),
('VR022', 'C002', 'VAC002', 2, '2021-06-20', '2021-08-20', 'HF001', 1, NOW(), 1),
('VR023', 'C002', 'VAC002', 3, '2021-08-20', '2021-10-20', 'HF001', 1, NOW(), 1),
('VR024', 'C002', 'VAC002', 4, '2021-10-20', '2022-10-20', 'HF001', 1, NOW(), 1),
('VR025', 'C002', 'VAC002', 5, '2022-10-20', '2026-10-20', 'HF001', 1, NOW(), 1),
('VR026', 'C002', 'VAC005', 1, '2021-04-20', '2021-06-20', 'HF001', 1, NOW(), 1),
('VR027', 'C002', 'VAC005', 2, '2021-06-20', '2021-08-20', 'HF001', 1, NOW(), 1),
('VR028', 'C002', 'VAC005', 3, '2021-08-20', '2021-10-20', 'HF001', 1, NOW(), 1),
('VR029', 'C002', 'VAC005', 4, '2021-10-20', '2026-10-20', 'HF001', 1, NOW(), 1),
('VR030', 'C002', 'VAC003', 1, '2022-03-20', '2026-03-20', 'HF001', 1, NOW(), 1);

-- Notifications
INSERT INTO notifications (guardian_id, notification_type, message, is_sent, date_sent, date_created) VALUES
(1, 'WEEK_BEFORE', 'Reminder: Alice has a vaccination appointment next week', 0, NULL, NOW()),
(1, 'TWO_DAYS_BEFORE', 'Reminder: Bob has a vaccination appointment in 2 days', 0, NULL, NOW()),
(2, 'WEEK_BEFORE', 'Reminder: Emma has a vaccination appointment next week', 0, NULL, NOW()),
(2, 'TWO_DAYS_BEFORE', 'Reminder: David has a vaccination appointment in 2 days', 0, NULL, NOW()),
(3, 'WEEK_BEFORE', 'Reminder: Sophie has a vaccination appointment next week', 0, NULL, NOW()); 