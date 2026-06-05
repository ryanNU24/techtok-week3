-- ==========================================
-- MediSched Database
-- Barangay Healthcare Appointment System
-- Full Database Script
-- ==========================================

CREATE DATABASE IF NOT EXISTS medisched_db;
USE medisched_db;

-- ==========================================
-- 1. Users Table
-- For future login system: patient, staff, admin
-- ==========================================

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password_hash VARCHAR(255),
    contact_number VARCHAR(20),
    address TEXT,
    role ENUM('patient', 'staff', 'admin') DEFAULT 'patient',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- 2. Patients Table
-- Extra patient profile details
-- ==========================================

CREATE TABLE IF NOT EXISTS patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    birthdate DATE,
    emergency_contact VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);

-- ==========================================
-- 3. Health Staff Table
-- Doctors, nurses, and midwives
-- ==========================================

CREATE TABLE IF NOT EXISTS health_staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    position VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    availability_status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);

-- ==========================================
-- 4. Services Table
-- Barangay health services
-- ==========================================

CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- 5. Clinic Schedules Table
-- Staff schedules per service
-- ==========================================

CREATE TABLE IF NOT EXISTS clinic_schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    service_id INT NOT NULL,
    schedule_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    max_slots INT DEFAULT 10,
    available_slots INT DEFAULT 10,
    status ENUM('open', 'closed', 'full') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (staff_id) REFERENCES health_staff(staff_id)
    ON DELETE CASCADE,

    FOREIGN KEY (service_id) REFERENCES services(service_id)
    ON DELETE CASCADE
);

-- ==========================================
-- 6. Appointments Table
-- For future logged-in patient appointment system
-- ==========================================

CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    service_id INT NOT NULL,
    staff_id INT,
    schedule_id INT,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
    ON DELETE CASCADE,

    FOREIGN KEY (service_id) REFERENCES services(service_id)
    ON DELETE CASCADE,

    FOREIGN KEY (staff_id) REFERENCES health_staff(staff_id)
    ON DELETE SET NULL,

    FOREIGN KEY (schedule_id) REFERENCES clinic_schedules(schedule_id)
    ON DELETE SET NULL
);

-- ==========================================
-- 7. Appointment Requests Table
-- This connects directly to your current appointment form
-- No login required
-- ==========================================

CREATE TABLE IF NOT EXISTS appointment_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time VARCHAR(20) NOT NULL,
    assigned_staff VARCHAR(100),
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- 8. Announcements Table
-- Clinic advisories and updates
-- ==========================================

CREATE TABLE IF NOT EXISTS announcements (
    announcement_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    posted_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (posted_by) REFERENCES users(user_id)
    ON DELETE SET NULL
);

-- ==========================================
-- 9. Medicine Availability Table
-- Medicine stock information
-- ==========================================

CREATE TABLE IF NOT EXISTS medicine_availability (
    medicine_id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_name VARCHAR(100) NOT NULL,
    quantity INT DEFAULT 0,
    status ENUM('available', 'limited', 'out_of_stock') DEFAULT 'available',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- 10. Vaccine Availability Table
-- Vaccine stock information
-- ==========================================

CREATE TABLE IF NOT EXISTS vaccine_availability (
    vaccine_id INT AUTO_INCREMENT PRIMARY KEY,
    vaccine_name VARCHAR(100) NOT NULL,
    quantity INT DEFAULT 0,
    status ENUM('available', 'limited', 'out_of_stock') DEFAULT 'available',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- 11. Contact Messages Table
-- Messages from the contact form
-- ==========================================

CREATE TABLE IF NOT EXISTS contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- Sample Data
-- ==========================================

INSERT INTO services (service_name, description)
VALUES
('Consultation', 'Medical consultation for common illnesses and health concerns.'),
('Vaccination', 'Vaccination and immunization services for children and families.'),
('Prenatal Check-up', 'Prenatal care and pregnancy monitoring.'),
('Child Check-up', 'Health check-up for infants and children.'),
('Other Services', 'Other barangay health center services.');

INSERT INTO users (full_name, email, password_hash, contact_number, address, role)
VALUES
('Admin User', 'admin@medisched.com', '$2y$10$samplepasswordhash', '09998887777', 'Paco Manila', 'admin'),
('Nurse Ana Santos', 'ana@medisched.com', '$2y$10$samplepasswordhash', '09123456789', 'Paco Manila', 'staff'),
('Dr. Miguel Reyes', 'reyes@medisched.com', '$2y$10$samplepasswordhash', '09234567890', 'Paco Manila', 'staff'),
('Midwife Elena Cruz', 'cruz@medisched.com', '$2y$10$samplepasswordhash', '09345678901', 'Paco Manila', 'staff');

INSERT INTO health_staff (user_id, position, specialization, availability_status)
VALUES
(2, 'Nurse', 'General Care', 'available'),
(3, 'Doctor', 'General Medicine', 'available'),
(4, 'Midwife', 'Prenatal Care', 'available');

INSERT INTO clinic_schedules 
(staff_id, service_id, schedule_date, start_time, end_time, max_slots, available_slots, status)
VALUES
(1, 1, '2026-06-10', '08:00:00', '12:00:00', 10, 10, 'open'),
(2, 1, '2026-06-10', '13:00:00', '17:00:00', 10, 10, 'open'),
(3, 3, '2026-06-11', '08:00:00', '12:00:00', 8, 8, 'open');

INSERT INTO announcements (title, content, posted_by)
VALUES
('Free Check-up Advisory', 'Free check-up schedule is open every Wednesday and Friday morning.', 1),
('Vaccine Availability Update', 'Routine child immunization vaccines are available this week.', 1);

INSERT INTO medicine_availability (medicine_name, quantity, status)
VALUES
('Paracetamol', 100, 'available'),
('Cough Syrup', 20, 'limited'),
('Oral Rehydration Salts', 50, 'available');

INSERT INTO vaccine_availability (vaccine_name, quantity, status)
VALUES
('Routine Child Immunization Vaccine', 40, 'available'),
('Flu Vaccine', 10, 'limited');