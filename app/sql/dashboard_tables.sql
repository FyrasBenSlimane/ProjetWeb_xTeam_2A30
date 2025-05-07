-- Dashboard Tables Schema

-- Blog Posts related tables
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS blog_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS blog_comment_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES blog_comments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS blog_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS blog_post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES blog_tags(id) ON DELETE CASCADE
);

-- Support Tickets related tables
CREATE TABLE IF NOT EXISTS support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('open', 'in-progress', 'resolved', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ticket_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT,
    message TEXT NOT NULL,
    is_staff BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Activity Logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    module VARCHAR(100) NOT NULL,
    target_id INT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Sample Data Insertion

-- Insert sample blog posts (assuming user with ID 1 exists)
INSERT INTO blog_posts (title, content, author_id, status, published_at, created_at, updated_at) VALUES 
    ('Getting Started with Site Guardian', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 1, 'published', '2025-04-10 10:30:00', '2025-04-05 14:22:10', '2025-04-10 10:30:00'),
    ('Advanced Security Features', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt.', 1, 'draft', NULL, '2025-04-20 16:15:40', '2025-04-25 09:10:30'),
    ('Monitoring Your Website Performance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt.', 1, 'published', '2025-04-22 11:30:00', '2025-04-18 13:45:20', '2025-04-22 11:30:00');

-- Insert sample blog tags
    INSERT INTO blog_tags (name) VALUES 
        ('Getting Started'), 
        ('Guide'), 
        ('Tutorial'), 
        ('Security'), 
        ('Advanced'), 
        ('Features'), 
        ('Performance'), 
        ('Monitoring');

-- Associate tags with posts
INSERT INTO blog_post_tags (post_id, tag_id) VALUES 
    (1, 1), (1, 2), (1, 3), 
    (2, 4), (2, 5), (2, 6), 
    (3, 7), (3, 8), (3, 2);

-- Insert sample blog comments
INSERT INTO blog_comments (post_id, user_id, content, status, created_at) VALUES 
    (1, 1, 'This was very helpful, thank you!', 'approved', '2025-04-12 09:45:22'),
    (1, 1, 'I have a question about step 3. Can you provide more details?', 'approved', '2025-04-15 11:20:35'),
    (3, 1, 'This is exactly what I needed. Thank you for the detailed explanation.', 'pending', '2025-04-23 14:55:10');

-- Insert sample comment responses
INSERT INTO blog_comment_responses (comment_id, user_id, content, created_at) VALUES 
    (1, 1, 'Glad you found it helpful!', '2025-04-12 10:30:15');

-- Insert sample support tickets
INSERT INTO support_tickets (subject, description, user_id, status, priority, created_at, updated_at) VALUES 
    ('Cannot access my account', 'I\'ve been trying to log in to my account for the past few days but keep getting an "Invalid credentials" error. I\'m sure my password is correct. Can you please help?', 1, 'open', 'high', '2025-04-25 09:15:30', '2025-04-25 09:15:30'),
    ('Feature request: Dark mode', 'I would love to see a dark mode option added to the dashboard. It would be much easier on the eyes when working late at night. Is this something you\'re planning to implement in the future?', 1, 'in-progress', 'medium', '2025-04-20 14:30:45', '2025-04-22 11:25:18'),
    ('Billing issue with my subscription', 'I was charged twice for my monthly subscription. The charges appeared on April 15 and April 16. Can you please refund one of these charges? My order numbers are #12345 and #12346.', 1, 'resolved', 'critical', '2025-04-17 10:05:22', '2025-04-18 15:42:30');

-- Insert sample ticket responses
INSERT INTO ticket_responses (ticket_id, user_id, message, is_staff, created_at) VALUES 
    (2, 1, 'Thank you for your suggestion! We\'re actually working on a dark mode implementation right now. It should be available in our next update in about two weeks. I\'ll mark this ticket as in-progress and update you when it\'s released.', 1, '2025-04-22 11:25:18'),
    (3, 1, 'I\'ve checked our billing system and confirmed the duplicate charge. I\'ve processed a refund for order #12346, which should appear on your account in 3-5 business days. I sincerely apologize for the inconvenience.', 1, '2025-04-18 12:30:10'),
    (3, 1, 'Thank you for the quick resolution! I\'ll keep an eye out for the refund.', 0, '2025-04-18 14:15:45'),
    (3, 1, 'You\'re welcome! Is there anything else we can help you with?', 1, '2025-04-18 15:42:30');

-- Insert sample activity logs
INSERT INTO activity_logs (user_id, action, module, target_id, ip_address, created_at) VALUES 
    (1, 'login', 'auth', NULL, '127.0.0.1', NOW() - INTERVAL 2 HOUR),
    (1, 'view', 'dashboard', NULL, '127.0.0.1', NOW() - INTERVAL 1 HOUR 55 MINUTE),
    (1, 'update', 'user', 2, '127.0.0.1', NOW() - INTERVAL 1 HOUR 30 MINUTE),
    (1, 'create', 'blog_post', 1, '127.0.0.1', NOW() - INTERVAL 50 MINUTE),
    (1, 'update', 'settings', NULL, '127.0.0.1', NOW() - INTERVAL 20 MINUTE);