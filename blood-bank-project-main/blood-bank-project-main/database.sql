CREATE DATABASE IF NOT EXISTS blood_bank;
USE blood_bank;

-- Users table (for donors)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    id_proof VARCHAR(20) NOT NULL UNIQUE,
    country VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    blood_group VARCHAR(3) NOT NULL,
    height INT NOT NULL,
    weight INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'hospital', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hospitals table
CREATE TABLE IF NOT EXISTS hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    license_number VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- State inventory table
CREATE TABLE IF NOT EXISTS state_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state VARCHAR(50) NOT NULL,
    blood_group VARCHAR(3) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY state_blood_group (state, blood_group)
);

-- Blood inventory table (hospital level)
CREATE TABLE IF NOT EXISTS blood_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT NOT NULL,
    blood_group VARCHAR(3) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);

-- Blood requests table
CREATE TABLE IF NOT EXISTS blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT NOT NULL,
    blood_group VARCHAR(3) NOT NULL,
    quantity INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);

-- Donations table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    location_type ENUM('hospital', 'blood_bank') NOT NULL,
    location_id INT NOT NULL,
    blood_group VARCHAR(3) NOT NULL,
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('completed', 'scheduled', 'cancelled') DEFAULT 'scheduled',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Blood Bank Centers table
CREATE TABLE IF NOT EXISTS blood_banks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    license_number VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blood bank inventory table
CREATE TABLE IF NOT EXISTS blood_bank_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_bank_id INT NOT NULL,
    blood_group VARCHAR(3) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blood_bank_id) REFERENCES blood_banks(id)
);

-- Insert demo hospitals
INSERT INTO hospitals (name, email, password, address, city, state, country, contact_number, license_number) VALUES
('City General Hospital', 'citygeneral@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Main Street', 'Mumbai', 'Maharashtra', 'India', '9876543210', 'HOSP001'),
('Metro Medical Center', 'metro@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Central Avenue', 'Delhi', 'Delhi', 'India', '9876543211', 'HOSP002'),
('Sunrise Hospital', 'sunrise@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Park Road', 'Bangalore', 'Karnataka', 'India', '9876543212', 'HOSP003'),
('Green Valley Medical', 'greenvalley@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '321 Valley Street', 'Chennai', 'Tamil Nadu', 'India', '9876543213', 'HOSP004'),
('Royal Care Hospital', 'royalcare@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '654 Royal Avenue', 'Kolkata', 'West Bengal', 'India', '9876543214', 'HOSP005'),
('Unity Health Center', 'unity@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '987 Unity Road', 'Hyderabad', 'Telangana', 'India', '9876543215', 'HOSP006'),
('Life Care Hospital', 'lifecare@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '147 Life Street', 'Pune', 'Maharashtra', 'India', '9876543216', 'HOSP007'),
('Hope Medical Center', 'hope@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '258 Hope Avenue', 'Ahmedabad', 'Gujarat', 'India', '9876543217', 'HOSP008'),
('Prime Hospital', 'prime@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '369 Prime Road', 'Jaipur', 'Rajasthan', 'India', '9876543218', 'HOSP009'),
('Elite Medical Center', 'elite@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '741 Elite Street', 'Lucknow', 'Uttar Pradesh', 'India', '9876543219', 'HOSP010');

-- Insert demo blood banks (one per city)
INSERT INTO blood_banks (name, email, password, address, city, state, country, contact_number, license_number) VALUES
('Mumbai Blood Bank Center', 'mumbai@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Blood Center Road', 'Mumbai', 'Maharashtra', 'India', '9876543210', 'BB001'),
('Delhi Blood Bank Center', 'delhi@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Blood Center Avenue', 'Delhi', 'Delhi', 'India', '9876543211', 'BB002'),
('Bangalore Blood Bank Center', 'bangalore@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Blood Center Street', 'Bangalore', 'Karnataka', 'India', '9876543212', 'BB003'),
('Chennai Blood Bank Center', 'chennai@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '321 Blood Center Lane', 'Chennai', 'Tamil Nadu', 'India', '9876543213', 'BB004'),
('Kolkata Blood Bank Center', 'kolkata@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '654 Blood Center Road', 'Kolkata', 'West Bengal', 'India', '9876543214', 'BB005'),
('Hyderabad Blood Bank Center', 'hyderabad@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '987 Blood Center Avenue', 'Hyderabad', 'Telangana', 'India', '9876543215', 'BB006'),
('Pune Blood Bank Center', 'pune@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '147 Blood Center Street', 'Pune', 'Maharashtra', 'India', '9876543216', 'BB007'),
('Ahmedabad Blood Bank Center', 'ahmedabad@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '258 Blood Center Lane', 'Ahmedabad', 'Gujarat', 'India', '9876543217', 'BB008'),
('Jaipur Blood Bank Center', 'jaipur@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '369 Blood Center Road', 'Jaipur', 'Rajasthan', 'India', '9876543218', 'BB009'),
('Lucknow Blood Bank Center', 'lucknow@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '741 Blood Center Avenue', 'Lucknow', 'Uttar Pradesh', 'India', '9876543219', 'BB010'); 