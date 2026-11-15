-- Creates the contact_manager database.
-- Resets both tables every time it is run.

CREATE DATABASE IF NOT EXISTS contact_manager
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE contact_manager;

DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS admins;

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO contacts (name, email, phone) VALUES
('Aisha Khan', 'aisha.khan@example.com', '+92 300 1234567'),
('Omar Ali', 'omar.ali@example.com', '+971 50 234 5678'),
('Sara Ahmed', 'sara.ahmed@example.com', '+44 20 7946 0958');

INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$x7rpqM/Zr6c4G/o3T7aMcuAIQze3GaFO0Rev9L9eRmd01ar/sr80C');
