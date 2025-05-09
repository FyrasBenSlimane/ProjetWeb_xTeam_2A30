-- Table structure for notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `data` text DEFAULT NULL,
  `linked_id` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_email` (`user_email`),
  KEY `is_read` (`is_read`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add foreign key if needed (depends on your existing schema)
-- ALTER TABLE `notifications` 
-- ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_email`) 
-- REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE; 