-- Création de la base de données
CREATE DATABASE IF NOT EXISTS lensi_db;
USE lensi_db;

-- Création de la table des événements
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table des participants
CREATE TABLE IF NOT EXISTS participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    event_id INT,
    status ENUM('pending', 'confirmed', 'rejected') DEFAULT 'pending',
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table des administrateurs
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion d'un administrateur par défaut (mot de passe: admin123)
INSERT INTO admins (username, password, email) VALUES
('admin', '$2y$10$8K1p/bIPR1C1OKhF8vyJeevIYe0YpTxO.rYX3qHYL3JkXd85ZJKnS', 'admin@lensi.com');

-- Insertion de quelques événements d'exemple
INSERT INTO events (title, date, location, description, image) VALUES
('Tech Conference 2025', '2025-04-25', 'Silicon Valley', 'Join us for the biggest tech conference of the year. Network with industry leaders and learn about the latest innovations.', 'assets/images/events/event1.jpg'),
('Freelancer Meetup', '2025-05-10', 'New York', 'Connect with fellow freelancers, share experiences, and explore collaboration opportunities.', 'assets/images/events/event2.jpg'),
('Digital Marketing Workshop', '2025-06-05', 'London', 'Learn effective digital marketing strategies from industry experts in this hands-on workshop.', 'assets/images/events/event3.jpg');

-- Insertion de quelques participants d'exemple
INSERT INTO participants (name, email, phone, event_id) VALUES
('John Doe', 'john@example.com', '+1234567890', 1),
('Jane Smith', 'jane@example.com', '+0987654321', 1),
('Mike Johnson', 'mike@example.com', '+1122334455', 2);

-- Création des index pour améliorer les performances
CREATE INDEX idx_event_date ON events(date);
CREATE INDEX idx_participant_email ON participants(email);
CREATE INDEX idx_participant_event ON participants(event_id);