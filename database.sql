CREATE DATABASE IF NOT EXISTS student_queue;
USE student_queue;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','cashier') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    student_id INT NOT NULL,
    service_id INT NOT NULL,
    queue_number INT NOT NULL,
    status ENUM('waiting','in_progress','served') NOT NULL DEFAULT 'waiting',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
);

INSERT IGNORE INTO services (name) VALUES
('Enrollment'),
('Downpayment'),
('Tuition Payment'),
('Library Fee'),
('Laboratory Fee'),
('ID Release'),
('Miscellaneous');

-- Demo cashier account:
-- Email: admin@test.com
-- Password: admin123
INSERT IGNORE INTO users (name, email, password, role)
VALUES ('Cashier Admin', 'admin@test.com', MD5('admin123'), 'cashier');
