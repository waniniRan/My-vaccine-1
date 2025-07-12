-- Clean sample data for the vaccine management system
-- This file should be run AFTER creating the database schema

-- Clear existing data (in reverse dependency order)
DELETE FROM healthcare_worker_sessions;
DELETE FROM notifications;
DELETE FROM vaccination_records;
DELETE FROM growth_records;
DELETE FROM children;
DELETE FROM guardians;
DELETE FROM healthcare_workers;
DELETE FROM facility_admins;
DELETE FROM health_facilities;
DELETE FROM vaccines;
DELETE FROM users;

-- Reset auto-increment counters
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE health_facilities AUTO_INCREMENT = 1;
ALTER TABLE facility_admins AUTO_INCREMENT = 1;
ALTER TABLE healthcare_workers AUTO_INCREMENT = 1;
ALTER TABLE guardians AUTO_INCREMENT = 1;
ALTER TABLE children AUTO_INCREMENT = 1;
ALTER TABLE growth_records AUTO_INCREMENT = 1;
ALTER TABLE vaccines AUTO_INCREMENT = 1;
ALTER TABLE vaccination_records AUTO_INCREMENT = 1;
ALTER TABLE notifications AUTO_INCREMENT = 1;
ALTER TABLE healthcare_worker_sessions AUTO_INCREMENT = 1;

-- Users (with correct role values)
Run this 
INSERT INTO users (username, password, email, role) VALUES
('123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian1@example.com', 'GUARDIAN'),
('987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian2@example.com', 'GUARDIAN'),
('456789123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guardian3@example.com', 'GUARDIAN'),
('W001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'worker1@example.com', 'WORKER'),
('A001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'SYSTEM_ADMIN');
('FA001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'janeMW@gmail.com', 'FACILITY_ADMIN');


-- Health Facilities
INSERT INTO health_facilities (facility_id, name, facility_type, location, phone, email, created_by, is_active) VALUES
('HF001', 'City General Hospital', 'HOSPITAL', 'Downtown', '555-0101', 'info@cityhospital.com', 1, 1),
('HF002', 'Community Health Center', 'CLINIC', 'Suburb', '555-0202', 'info@communityhealth.com', 1, 1),
('HF003', 'Pediatric Clinic', 'CLINIC', 'City Center', '555-0303', 'info@pediatricclinic.com', 1, 1);

-- Facility Admins (using the first facility)
INSERT INTO facility_admins (user_id, facility_id, fullname, email, temporary_password, password_changed)
VALUES (6, 1, 'Jane Mwangi', 'janeMW@example.com', '', 1);


-- Healthcare Workers
INSERT INTO healthcare_workers (user_id, worker_id, worker_username, fullname, email, phone_number, position, facility_id, facility_admin_id, status) VALUES
(5, 'W001', 'worker1', 'Dr. John Smith', 'john@hospital.com', '123-456-7890', 'doctor', 1, 1, 'active');

-- Guardians
INSERT INTO guardians (user_id, national_id, fullname, email, phone_number, temporary_password, password_changed, date_registered, registered_by, is_active) VALUES
(2, '123456789', 'Jane Doe', 'jane@example.com', '987-654-3210', '', 1, NOW(), 1, 1),
(3, '987654321', 'John Johnson', 'john@example.com', '123-456-7890', '', 1, NOW(), 1, 1),
(4, '456789123', 'Sarah Wilson', 'sarah@example.com', '555-123-4567', '', 1, NOW(), 1, 1);

-- Children
INSERT INTO children (child_id, first_name, last_name, date_of_birth, gender, birth_weight, birth_height, guardian_id, registered_by, date_registered, is_active) VALUES
('C001', 'Alice', 'Doe', '2020-01-15', 'F', 3.20, 50.00, 1, 1, NOW(), 1),
('C002', 'Bob', 'Doe', '2021-03-20', 'M', 3.50, 52.00, 1, 1, NOW(), 1),
('C003', 'Emma', 'Johnson', '2019-06-10', 'F', 3.00, 48.00, 2, 1, NOW(), 1),
('C004', 'David', 'Johnson', '2022-09-05', 'M', 3.80, 54.00, 2, 1, NOW(), 1),
('C005', 'Sophie', 'Wilson', '2021-12-03', 'F', 3.10, 49.00, 3, 1, NOW(), 1);

-- Vaccines
INSERT INTO vaccines (name, v_id, description, dosage, disease_prevented, recommended_age) VALUES
('BCG', 'VAC001', 'Bacillus Calmette-Guérin vaccine for tuberculosis', 'Single dose', 'Tuberculosis', '0-2 months'),
('DPT', 'VAC002', 'Diphtheria, Pertussis, Tetanus vaccine', '3 doses', 'Diphtheria, Pertussis, Tetanus', '2-4 months'),
('MMR', 'VAC003', 'Measles, Mumps, Rubella vaccine', '2 doses', 'Measles, Mumps, Rubella', '12-15 months'),
('Hepatitis B', 'VAC004', 'Hepatitis B vaccine', '3 doses', 'Hepatitis B', '0-6 months'),
('Polio', 'VAC005', 'Polio vaccine', '4 doses', 'Polio', '0-4 months');

-- Growth Records for Alice (C001) - 21 records from birth to age 5
INSERT INTO growth_records (child_id, date_recorded, weight, height, recorded_by) VALUES
(1, '2020-02-15', 4.2, 55.0, 1),
(1, '2020-05-15', 6.1, 62.0, 1),
(1, '2020-08-15', 7.5, 68.0, 1),
(1, '2020-11-15', 8.2, 72.0, 1),
(1, '2021-02-15', 9.1, 76.0, 1),
(1, '2021-05-15', 10.0, 80.0, 1),
(1, '2021-08-15', 11.2, 84.0, 1),
(1, '2021-11-15', 12.1, 88.0, 1),
(1, '2022-02-15', 13.0, 92.0, 1),
(1, '2022-05-15', 14.2, 96.0, 1),
(1, '2022-08-15', 15.1, 100.0, 1),
(1, '2022-11-15', 16.0, 104.0, 1),
(1, '2023-02-15', 17.2, 108.0, 1),
(1, '2023-05-15', 18.1, 112.0, 1),
(1, '2023-08-15', 19.0, 116.0, 1),
(1, '2023-11-15', 20.1, 120.0, 1),
(1, '2024-02-15', 21.0, 124.0, 1),
(1, '2024-05-15', 22.1, 128.0, 1),
(1, '2024-08-15', 23.0, 132.0, 1),
(1, '2024-11-15', 24.1, 136.0, 1),
(1, '2025-01-15', 25.0, 140.0, 1);

-- Growth Records for Bob (C002) - 15 records from birth to age 4
INSERT INTO growth_records (child_id, date_recorded, weight, height, recorded_by) VALUES
(2, '2021-04-20', 5.2, 58.0, 1),
(2, '2021-07-20', 6.8, 64.0, 1),
(2, '2021-10-20', 7.9, 70.0, 1),
(2, '2022-01-20', 8.6, 74.0, 1),
(2, '2022-04-20', 9.5, 78.0, 1),
(2, '2022-07-20', 10.4, 82.0, 1),
(2, '2022-10-20', 11.3, 86.0, 1),
(2, '2023-01-20', 12.2, 90.0, 1),
(2, '2023-04-20', 13.1, 94.0, 1),
(2, '2023-07-20', 14.0, 98.0, 1),
(2, '2023-10-20', 15.1, 102.0, 1),
(2, '2024-01-20', 16.0, 106.0, 1),
(2, '2024-04-20', 17.1, 110.0, 1),
(2, '2024-07-20', 18.0, 114.0, 1),
(2, '2024-10-20', 19.1, 118.0, 1),
(2, '2025-01-20', 20.0, 122.0, 1);

-- Vaccination Records for Alice (C001) - 15 records
INSERT INTO vaccination_records (record_id, child_id, vaccine_id, dose_number, administration_date, administered_by) VALUES
('VR001', 1, 1, 1, '2020-01-20', 1),
('VR002', 1, 4, 1, '2020-01-20', 1),
('VR003', 1, 4, 2, '2020-02-20', 1),
('VR004', 1, 4, 3, '2020-06-20', 1),
('VR005', 1, 4, 4, '2020-12-20', 1),
('VR006', 1, 2, 1, '2020-02-15', 1),
('VR007', 1, 2, 2, '2020-04-15', 1),
('VR008', 1, 2, 3, '2020-06-15', 1),
('VR009', 1, 2, 4, '2020-08-15', 1),
('VR010', 1, 2, 5, '2021-08-15', 1),
('VR011', 1, 5, 1, '2020-02-15', 1),
('VR012', 1, 5, 2, '2020-04-15', 1),
('VR013', 1, 5, 3, '2020-06-15', 1),
('VR014', 1, 5, 4, '2020-08-15', 1),
('VR015', 1, 3, 1, '2021-01-15', 1);

-- Vaccination Records for Bob (C002) - 15 records
INSERT INTO vaccination_records (record_id, child_id, vaccine_id, dose_number, administration_date, administered_by) VALUES
('VR016', 2, 1, 1, '2021-03-25', 1),
('VR017', 2, 4, 1, '2021-03-25', 1),
('VR018', 2, 4, 2, '2021-04-25', 1),
('VR019', 2, 4, 3, '2021-08-25', 1),
('VR020', 2, 4, 4, '2022-02-25', 1),
('VR021', 2, 2, 1, '2021-04-20', 1),
('VR022', 2, 2, 2, '2021-06-20', 1),
('VR023', 2, 2, 3, '2021-08-20', 1),
('VR024', 2, 2, 4, '2021-10-20', 1),
('VR025', 2, 2, 5, '2022-10-20', 1),
('VR026', 2, 5, 1, '2021-04-20', 1),
('VR027', 2, 5, 2, '2021-06-20', 1),
('VR028', 2, 5, 3, '2021-08-20', 1),
('VR029', 2, 5, 4, '2021-10-20', 1),
('VR030', 2, 3, 1, '2022-03-20', 1);

-- Notifications
INSERT INTO notifications (guardian_id, notification_type, message, is_sent, date_sent, date_created) VALUES
(1, 'WEEK_BEFORE', 'Reminder: Alice has a vaccination appointment next week', 0, NULL, NOW()),
(1, 'TWO_DAYS_BEFORE', 'Reminder: Bob has a vaccination appointment in 2 days', 0, NULL, NOW()),
(2, 'WEEK_BEFORE', 'Reminder: Emma has a vaccination appointment next week', 0, NULL, NOW()),
(2, 'TWO_DAYS_BEFORE', 'Reminder: David has a vaccination appointment in 2 days', 0, NULL, NOW()),
(3, 'WEEK_BEFORE', 'Reminder: Sophie has a vaccination appointment next week', 0, NULL, NOW()); 