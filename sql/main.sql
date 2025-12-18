-- create DATABASE if it do not EXISTS
CREATE DATABASE IF NOT EXISTS clinic_management
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- USERS (Patient / Doctor / Admin)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient','doctor','admin') NOT NULL,
    status ENUM('active','blocked','pending') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- PROFILE
DROP TABLE IF EXISTS profile;
CREATE TABLE profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_number BIGINT NOT NULL UNIQUE,
    dob DATE NOT NULL,
    address TEXT NOT NULL,
    status ENUM('Verified','Pending') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SPECIALIZATIONS
DROP TABLE IF EXISTS specializations;
CREATE TABLE specializations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    status ENUM('active','inactive') DEFAULT 'active'
);

-- DOCTOR PROFILES
DROP TABLE IF EXISTS doctor_profiles;
CREATE TABLE doctor_profiles (
    id INT PRIMARY KEY,
    specialization_id INT NOT NULL,
    approved TINYINT(1) DEFAULT 0,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (specialization_id) REFERENCES specializations(id)
);

-- DOCTOR AVAILABILITY (UTC)
DROP TABLE IF EXISTS slots;
CREATE TABLE slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    start_utc DATETIME NOT NULL,
    end_utc DATETIME NOT NULL,
    is_blocked TINYINT(1) DEFAULT 0,
    UNIQUE (doctor_id, start_utc, end_utc),
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- APPOINTMENTS
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    start_utc DATETIME NOT NULL,
    end_utc DATETIME NOT NULL,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (doctor_id, start_utc),
    FOREIGN KEY (patient_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);
