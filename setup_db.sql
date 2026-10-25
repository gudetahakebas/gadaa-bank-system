-- Gadaa Bank Voting System Database Schema
-- NOTE: If you get a 'No database selected' error, create the database manually:
-- 1. Click 'New' in phpMyAdmin sidebar
-- 2. Name it 'gadaa_voting' and click 'Create'
-- 3. Click the 'gadaa_voting' database name in the sidebar
-- 4. Import this file

-- Users Table (Handles all roles: admin, officer, candidate, voter)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'officer', 'candidate', 'voter') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Candidates Table
CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    candidate_id_str VARCHAR(20) UNIQUE NOT NULL, -- Logical ID like CAND-001
    candidate_category ENUM('Non-Influential Shareholder Candidate', 'Influential Shareholder Candidate', 'Independent Candidate') NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    sex ENUM('Male', 'Female') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    education VARCHAR(50) NOT NULL,
    experience INT NOT NULL,
    bio TEXT,
    photo_path VARCHAR(255),
    doc_path VARCHAR(255),
    status ENUM('Registered', 'Final') DEFAULT 'Registered',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Voters Table
CREATE TABLE IF NOT EXISTS voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    voter_id_str VARCHAR(20) UNIQUE NOT NULL, -- Logical ID like VOT-001
    voter_category ENUM('Non-Influential Shareholder', 'Influential Shareholder') NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    sex ENUM('Male', 'Female') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    share_value DECIMAL(15, 2) NOT NULL,
    share_doc_path VARCHAR(255),
    photo_path VARCHAR(255),
    status ENUM('Pending', 'Approved') DEFAULT 'Pending',
    has_voted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Officers Table
CREATE TABLE IF NOT EXISTS officers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    sex ENUM('Male', 'Female') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Votes Table
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id INT NOT NULL,
    candidate_id INT NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voter_id) REFERENCES voters(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id)
);

-- System Settings Table
CREATE TABLE IF NOT EXISTS system_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT NOT NULL
);

-- Audit Logs Table
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    role VARCHAR(20),
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- This first checks if admin exists to avoid errors on re-import

-- Set Initial Election Status
INSERT IGNORE INTO system_settings (setting_key, setting_value) VALUES ('election_status', 'Registration');
