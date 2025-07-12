-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(254),
    role ENUM('SYSTEM_ADMIN', 'FACILITY_ADMIN', 'WORKER', 'GUARDIAN') DEFAULT 'GUARDIAN',
    must_change_password BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Health Facilities table
CREATE TABLE IF NOT EXISTS health_facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id VARCHAR(15) UNIQUE NOT NULL,
    name VARCHAR(200) NOT NULL,
    facility_type ENUM('HOSPITAL', 'CLINIC', 'HEALTH_CENTER'),
    location VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(254),
    admin_id INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (admin_id) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Facility Admins table
CREATE TABLE IF NOT EXISTS facility_admins (
    id INT AUTO_INCREMENT PRIMARY KEY,                     -- Unique row ID
    user_id INT UNIQUE NOT NULL,                           -- Links to 'users' table where role = 'FACILITY_ADMIN'
    facility_id INT NOT NULL,                              -- Assigned facility
    fullname VARCHAR(200),                                 -- Full name of the facility admin
    email VARCHAR(254),                                    -- Optional contact email
    temporary_password VARCHAR(120),                       -- Temporary password before first login
    password_changed BOOLEAN DEFAULT FALSE,                -- Tracks if user changed password
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (facility_id) REFERENCES health_facilities(id)
);


-- Healthcare Workers table
CREATE TABLE IF NOT EXISTS healthcare_workers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    worker_id VARCHAR(15) UNIQUE NOT NULL,
    worker_username VARCHAR(100) UNIQUE NOT NULL,
    fullname VARCHAR(200),
    email VARCHAR(254),
    phone_number VARCHAR(15),
    position ENUM('doctor', 'nurse'),
    facility_id INT,
    facility_admin_id INT,
    temporary_password VARCHAR(120),
    password_changed BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'suspended'),
    date_joined DATETIME,
    date_left DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (facility_id) REFERENCES health_facilities(id),
    FOREIGN KEY (facility_admin_id) REFERENCES facility_admins(id)
);

-- Guardians table
CREATE TABLE IF NOT EXISTS guardians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    fullname VARCHAR(200),
    email VARCHAR(254),
    phone_number VARCHAR(15),
    temporary_password VARCHAR(128),
    password_changed BOOLEAN DEFAULT TRUE,
    date_registered DATETIME DEFAULT CURRENT_TIMESTAMP,
    registered_by INT,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (registered_by) REFERENCES healthcare_workers(id)
);

-- Children table
CREATE TABLE IF NOT EXISTS children (
    id INT AUTO_INCREMENT PRIMARY KEY,
    child_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    date_of_birth DATE,
    gender ENUM('M', 'F'),
    birth_weight DECIMAL(5,2),
    birth_height DECIMAL(5,2),
    guardian_id INT,
    registered_by INT,
    date_registered DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (guardian_id) REFERENCES guardians(id),
    FOREIGN KEY (registered_by) REFERENCES healthcare_workers(id)
);

-- Growth Records table
CREATE TABLE IF NOT EXISTS growth_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    child_id INT,
    date_recorded DATE,
    weight DECIMAL(5,2),
    height DECIMAL(5,2),
    recorded_by INT,
    notes TEXT,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (child_id) REFERENCES children(id),
    FOREIGN KEY (recorded_by) REFERENCES healthcare_workers(id)
);

-- Vaccines table
CREATE TABLE IF NOT EXISTS vaccines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    v_id VARCHAR(10),
    description TEXT,
    dosage VARCHAR(50),
    disease_prevented VARCHAR(100),
    recommended_age VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Vaccination Records table
CREATE TABLE IF NOT EXISTS vaccination_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id VARCHAR(20) UNIQUE NOT NULL,
    child_id INT,
    vaccine_id INT,
    administration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    dose_number INT,
    remarks TEXT,
    administered_by INT,
    side_effects TEXT,
    FOREIGN KEY (child_id) REFERENCES children(id),
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(id),
    FOREIGN KEY (administered_by) REFERENCES healthcare_workers(id)
);

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guardian_id INT,
    notification_type ENUM('WEEK_BEFORE', 'TWO_DAYS_BEFORE', 'MISSED_APPOINTMENT'),
    message TEXT,
    is_sent BOOLEAN DEFAULT FALSE,
    date_sent DATETIME,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guardian_id) REFERENCES guardians(id)
);

-- Healthcare Worker Sessions table
CREATE TABLE IF NOT EXISTS healthcare_worker_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    healthcare_worker_id INT,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    logout_time DATETIME,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (healthcare_worker_id) REFERENCES healthcare_workers(id)
); 