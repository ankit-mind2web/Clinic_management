-- create DATABASE if it do not EXISTS
CREATE DATABASE IF NOT EXISTS clinic_management
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- USERS (Patient / Doctor / Admin)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient','doctor','admin') NOT NULL,
    status ENUM('active','blocked','pending') DEFAULT 'active',
    email_verified TINYINT(1) DEFAULT 0,
    email_verify_token VARCHAR(255) DEFAULT NULL,
    email_token_expires DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY email (email),
    UNIQUE KEY mobile (mobile)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;

-- PROFILE
DROP TABLE IF EXISTS profile;
CREATE TABLE profile (
    id INT NOT NULL AUTO_INCREMENT,
    gender ENUM('Male','Female','Others') NOT NULL,
    dob DATE NOT NULL,
    address TEXT NOT NULL,
    status ENUM('Verified','Pending') NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;


-- SPECIALIZATIONS
DROP TABLE IF EXISTS specializations;
CREATE TABLE specializations (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY name (name)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;


-- DOCTOR specialization
DROP TABLE IF EXISTS doctor_specialization;

CREATE TABLE doctor_specialization (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    specialization_id INT NOT NULL,
    experience INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_doctor_spec (doctor_id, specialization_id),
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (specialization_id) REFERENCES specializations(id) ON DELETE CASCADE
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;


-- DOCTOR AVAILABILITY (UTC)
DROP TABLE IF EXISTS slots;
CREATE TABLE slots (
    id INT NOT NULL AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    start_utc DATETIME NOT NULL,
    end_utc DATETIME NOT NULL,
    is_blocked TINYINT(1) DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY doctor_slot_unique (doctor_id, start_utc, end_utc),

    CONSTRAINT slots_ibfk_1
        FOREIGN KEY (doctor_id)
        REFERENCES users (id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;



-- APPOINTMENTS
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
    id INT NOT NULL AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    slot_id INT NOT NULL,
    start_utc DATETIME NOT NULL,
    end_utc DATETIME NOT NULL,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY doctor_start_unique (doctor_id, start_utc),
    KEY patient_id (patient_id),
    CONSTRAINT appointments_ibfk_1
        FOREIGN KEY (patient_id)
        REFERENCES users (id)
        ON DELETE CASCADE,
    CONSTRAINT appointments_ibfk_2
        FOREIGN KEY (doctor_id)
        REFERENCES users (id)
        ON DELETE CASCADE,
    CONSTRAINT appointments_ibfk_3
        FOREIGN KEY (slot_id)
        REFERENCES slots (id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;


