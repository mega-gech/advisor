DROP DATABASE IF EXISTS advisorhub;
CREATE DATABASE advisorhub;
USE advisorhub;

-- Base Users Table (Authentication & General Info)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'advisor', 'registrar') NOT NULL DEFAULT 'student',
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    student_number VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students Table (Extends users, can hold department, major, etc. in future)
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Advisors Table (Extends users, can hold department, title, etc. in future)
CREATE TABLE IF NOT EXISTS advisors (
    id INT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Advisor Assignments with History (Registrar assigns advisors to students)
CREATE TABLE IF NOT EXISTS advisor_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    advisor_id INT NOT NULL,
    assigned_by INT NOT NULL, -- Registrar ID
    is_active BOOLEAN DEFAULT TRUE,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (advisor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Messaging System
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NULL,
    audience_type ENUM('advisor') NULL,
    message_type ENUM('broadcast', 'individual') DEFAULT 'individual',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Appointments System
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    advisor_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (advisor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Notifications System
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message VARCHAR(255) NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Demo accounts (password for all: password123)
INSERT INTO users (name, email, password, role, status, student_number) VALUES
('Registrar Admin', 'registrar@aau.edu.et', '$2y$10$YFUBL7CYvb4bm1sxN0yZTe7xbuQ6mH7jtudB7GXeuRTLBZPhCvOOS', 'registrar', 'approved', NULL),
('Dr. Michael Tesfaye', 'advisor@aau.edu.et', '$2y$10$YFUBL7CYvb4bm1sxN0yZTe7xbuQ6mH7jtudB7GXeuRTLBZPhCvOOS', 'advisor', 'approved', NULL),
('Abel Eshetu', 'student@aau.edu.et', '$2y$10$YFUBL7CYvb4bm1sxN0yZTe7xbuQ6mH7jtudB7GXeuRTLBZPhCvOOS', 'student', 'approved', 'UGR/1234/15');

INSERT INTO students (id, user_id) SELECT id, id FROM users WHERE email = 'student@aau.edu.et';
INSERT INTO advisors (id, user_id) SELECT id, id FROM users WHERE email = 'advisor@aau.edu.et';

INSERT INTO advisor_assignments (student_id, advisor_id, assigned_by, is_active)
SELECT s.id, a.id, r.id, TRUE
FROM users s, users a, users r
WHERE s.email = 'student@aau.edu.et'
  AND a.email = 'advisor@aau.edu.et'
  AND r.email = 'registrar@aau.edu.et';

INSERT INTO messages (sender_id, receiver_id, audience_type, message_type, title, message)
SELECT r.id, NULL, 'advisor', 'broadcast', 'Welcome advisors', 'Use AdvisorHub to manage your advisees, messages, and appointments.'
FROM users r WHERE r.email = 'registrar@aau.edu.et';
