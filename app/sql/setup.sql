-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS lensi_db;
USE lensi_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    account_type ENUM('client', 'freelancer', 'admin') NOT NULL DEFAULT 'freelancer',
    country VARCHAR(100),
    promo_emails BOOLEAN DEFAULT TRUE,
    terms_accepted BOOLEAN NOT NULL DEFAULT FALSE,
    bio TEXT,
    skills TEXT,
    profile_image VARCHAR(255),
    status ENUM('active', 'inactive', 'suspended', 'deleted') DEFAULT 'active',
    force_password_change BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- User Profiles table
CREATE TABLE IF NOT EXISTS user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT,
    location VARCHAR(255),
    professional_title VARCHAR(255),
    experience_level ENUM('entry', 'intermediate', 'expert') DEFAULT 'entry',
    hourly_rate DECIMAL(10,2) DEFAULT 0.00,
    hours_per_week VARCHAR(50),
    profile_visibility ENUM('public', 'private', 'connections') DEFAULT 'public',
    project_preference ENUM('short_term', 'long_term', 'both') DEFAULT 'both',
    categories VARCHAR(255),
    languages TEXT,
    skills TEXT,
    education TEXT,
    certifications TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Portfolio table
CREATE TABLE IF NOT EXISTS user_portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    project_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Work History table
CREATE TABLE IF NOT EXISTS user_work_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    current_job BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Education table
CREATE TABLE IF NOT EXISTS user_education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    institution VARCHAR(255) NOT NULL,
    degree VARCHAR(255),
    field_of_study VARCHAR(255),
    start_date DATE,
    end_date DATE,
    current_education BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Skills table
CREATE TABLE IF NOT EXISTS user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill VARCHAR(100) NOT NULL,
    skill_level ENUM('beginner', 'intermediate', 'advanced', 'expert') DEFAULT 'intermediate',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Certifications table
CREATE TABLE IF NOT EXISTS user_certifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    organization VARCHAR(255) NOT NULL,
    issue_date DATE,
    expiry_date DATE,
    credential_id VARCHAR(255),
    credential_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Reviews and Ratings table
CREATE TABLE IF NOT EXISTS user_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    rating DECIMAL(3,1) NOT NULL,
    review_text TEXT,
    project_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Login History
CREATE TABLE IF NOT EXISTS user_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    client_id INT NOT NULL,
    provider_id INT NOT NULL,
    requirements TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'accepted', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (provider_id) REFERENCES users(id)
);

-- Order messages table
CREATE TABLE IF NOT EXISTS order_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Reviews table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    service_id INT NOT NULL,
    client_id INT NOT NULL,
    provider_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (provider_id) REFERENCES users(id),
    UNIQUE KEY unique_order_review (order_id)
);

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Jobs table
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    budget DECIMAL(10,2) NOT NULL,
    skills TEXT,
    category VARCHAR(50) NOT NULL,
    status ENUM('active', 'completed', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Applications table for job applications
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    proposal TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_job_freelancer (job_id, freelancer_id)
);

-- Admin tasks table
CREATE TABLE IF NOT EXISTS admin_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255) NOT NULL,
    user_id INT,
    completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Admin notifications table
CREATE TABLE IF NOT EXISTS admin_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY DEFAULT 1,
    site_name VARCHAR(255) NOT NULL DEFAULT 'Lensi',
    site_description TEXT,
    contact_email VARCHAR(255),
    support_phone VARCHAR(50),
    logo VARCHAR(255),
    primary_color VARCHAR(20) DEFAULT '#007bff',
    secondary_color VARCHAR(20) DEFAULT '#6c757d',
    default_theme VARCHAR(20) DEFAULT 'light',
    smtp_host VARCHAR(255),
    smtp_port INT,
    smtp_username VARCHAR(255),
    smtp_password VARCHAR(255),
    from_email VARCHAR(255),
    from_name VARCHAR(255),
    enable_recaptcha BOOLEAN DEFAULT FALSE,
    enable_2fa BOOLEAN DEFAULT FALSE,
    recaptcha_site_key VARCHAR(255),
    recaptcha_secret_key VARCHAR(255),
    currency VARCHAR(10) DEFAULT 'USD',
    platform_fee DECIMAL(5,2) DEFAULT 5.00,
    enable_paypal BOOLEAN DEFAULT TRUE,
    enable_stripe BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (id, site_name, site_description) VALUES (1, 'Lensi', 'Freelance Services Marketplace') ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Add sample profile data for testing
INSERT INTO user_profiles (user_id, bio, location, professional_title, experience_level, hourly_rate, profile_visibility, languages, skills)
VALUES (1, 'Experienced web developer with a passion for creating responsive, user-friendly websites.', 'New York, USA', 'Full Stack Web Developer', 'expert', 45.00, 'public', 'English (Fluent), Spanish (Intermediate)', 'PHP, JavaScript, HTML, CSS, MySQL, Laravel, React');