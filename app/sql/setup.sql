-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS lensi;
USE lensi;

-- Users table - Consolidated with profile data
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    account_type ENUM('client', 'freelancer', 'admin') NOT NULL DEFAULT 'freelancer',
    profile_image VARCHAR(255),
    location VARCHAR(255),
    country VARCHAR(100),
    bio TEXT,
    professional_title VARCHAR(255),
    hourly_rate DECIMAL(10,2) DEFAULT 0.00,
    experience_level ENUM('entry', 'intermediate', 'expert') DEFAULT 'entry',
    skills JSON,                    -- Store skills as JSON array
    education JSON,                -- Store education history as JSON
    work_history JSON,            -- Store work history as JSON
    certifications JSON,          -- Store certifications as JSON
    portfolio JSON,                -- Store portfolio items as JSON
    languages JSON,               -- Store languages as JSON
    visibility ENUM('public', 'private', 'connections') DEFAULT 'public',
    project_preference ENUM('short_term', 'long_term', 'both') DEFAULT 'both',
    categories VARCHAR(255),      -- Main categories of expertise
    promo_emails BOOLEAN DEFAULT TRUE,
    terms_accepted BOOLEAN NOT NULL DEFAULT FALSE,
    status ENUM('active', 'inactive', 'suspended', 'deleted') DEFAULT 'active',
    force_password_change BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Login History (kept separate for security audit purposes)
CREATE TABLE IF NOT EXISTS user_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Jobs table
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    budget DECIMAL(10,2) NOT NULL,
    skills JSON,                    -- Store required skills as JSON
    category VARCHAR(50) NOT NULL,
    job_type ENUM('fixed', 'hourly') DEFAULT 'fixed',
    duration VARCHAR(100),         -- Expected project duration
    experience_level ENUM('entry', 'intermediate', 'expert') DEFAULT 'intermediate',
    status ENUM('active', 'completed', 'cancelled', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    deliverables JSON,             -- Store deliverables as JSON
    delivery_time INT NOT NULL,    -- Delivery time in days
    revisions INT DEFAULT 1,       -- Number of revisions included
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Consolidated orders/contracts table
CREATE TABLE IF NOT EXISTS contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT,                -- NULL for job-based contracts
    job_id INT,                    -- NULL for service-based contracts
    client_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    requirements TEXT,
    price DECIMAL(10,2) NOT NULL,
    contract_type ENUM('fixed', 'hourly') DEFAULT 'fixed',
    payment_status ENUM('pending', 'paid', 'refunded', 'disputed') DEFAULT 'pending',
    status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    completion_percentage INT DEFAULT 0,
    milestones JSON,              -- Store milestones as JSON
    start_date TIMESTAMP NULL,
    end_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Applications/proposals table
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    proposal TEXT NOT NULL,
    bid_amount DECIMAL(10,2) NOT NULL,
    estimated_duration VARCHAR(100),
    attachments JSON,              -- Store attachments info as JSON
    status ENUM('pending', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_job_freelancer (job_id, freelancer_id)
);

-- Combined messaging system
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    contract_id INT,              -- For contract-related messages
    application_id INT,           -- For application-related messages
    subject VARCHAR(255),
    message TEXT NOT NULL,
    attachments JSON,             -- Store attachments info as JSON
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE SET NULL,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE SET NULL
);

-- Reviews table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contract_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    reviewee_id INT NOT NULL,
    rating DECIMAL(3,1) NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    response TEXT,                -- Response from reviewee
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_contract_reviewer (contract_id, reviewer_id)
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contract_id INT NOT NULL,
    payer_id INT NOT NULL,
    payee_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    fee DECIMAL(10,2) DEFAULT 0.00,
    payment_method VARCHAR(50),
    transaction_id VARCHAR(255),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE CASCADE,
    FOREIGN KEY (payer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (payee_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    reference_id INT,             -- ID of the related entity
    reference_type VARCHAR(50),   -- Type of the related entity (job, contract, etc.)
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Support Tickets table
CREATE TABLE IF NOT EXISTS support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in-progress', 'resolved', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    responses JSON,              -- Store ticket responses as JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Admin Notifications table
CREATE TABLE IF NOT EXISTS admin_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    reference_id INT,             -- ID of the related entity
    reference_type VARCHAR(50),   -- Type of the related entity
    ip_address VARCHAR(45),       -- IP address for tracking visits
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog system (simplified)
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    featured_image VARCHAR(255),
    tags JSON,                   -- Store tags as JSON
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    comments JSON,               -- Store comments as JSON (for simple blog systems)
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity Logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    module VARCHAR(100) NOT NULL,
    target_id INT,
    details JSON,                -- Additional details as JSON
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Settings table (consolidated)
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY DEFAULT 1,
    site_name VARCHAR(255) NOT NULL DEFAULT 'Lensi',
    site_description TEXT,
    contact_email VARCHAR(255),
    support_phone VARCHAR(50),
    logo VARCHAR(255),
    site_settings JSON,          -- Store site settings as JSON
    mail_settings JSON,          -- Store mail settings as JSON
    payment_settings JSON,       -- Store payment settings as JSON
    security_settings JSON,      -- Store security settings as JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (id, site_name, site_description, site_settings, payment_settings) VALUES 
(1, 'Lensi', 'Freelance Services Marketplace', 
'{"primary_color": "#2c3e50", "primary_light": "#34495e", "primary_dark": "#1a252f", "primary_accent": "#ecf0f1", "secondary_color": "#222325", "secondary_light": "#404145", "secondary_dark": "#0e0e10", "secondary_accent": "#f1f1f2", "default_theme": "light"}',
'{"currency": "USD", "platform_fee": 5.00, "enable_paypal": true, "enable_stripe": true}') 
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Add sample user data for testing
INSERT INTO users (id, name, email, password, account_type, country, bio, professional_title, 
                  experience_level, hourly_rate, skills, education, languages, terms_accepted, status) 
VALUES (1, 'Firas Ben Slimane', 'firaszx232@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
       'admin', 'USA', 
       'Experienced web developer with a passion for creating responsive, user-friendly websites.',
       'Full Stack Web Developer', 'expert', 45.00, 
       '["PHP", "JavaScript", "HTML", "CSS", "MySQL", "Laravel", "React"]',
       '[{"institution": "Harvard University", "degree": "BSc Computer Science", "year": "2018"}]',
       '[{"name": "English", "level": "Fluent"}, {"name": "Spanish", "level": "Intermediate"}]',
       TRUE, 'active');

-- Add sample job data
INSERT INTO jobs (user_id, title, description, budget, skills, category, job_type, status)
VALUES (1, 'Website Development', 'Looking for an experienced developer to build a responsive website for my business.', 
       1500.00, '["PHP", "JavaScript", "HTML", "CSS"]', 'Web Development', 'fixed', 'active');

-- Add sample activity logs
INSERT INTO activity_logs (user_id, action, module, target_id, details, ip_address, created_at)
VALUES 
    (1, 'login', 'auth', NULL, '{"browser": "Chrome", "platform": "Windows"}', '127.0.0.1', NOW() - INTERVAL 2 HOUR),
    (1, 'create', 'job', 1, '{"title": "Website Development"}', '127.0.0.1', NOW() - INTERVAL 1 HOUR);