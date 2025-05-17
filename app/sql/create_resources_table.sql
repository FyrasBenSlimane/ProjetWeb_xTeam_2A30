CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `youtube_id` varchar(50) NOT NULL,
  `thumbnail_url` varchar(255) NOT NULL,
  `description` text,
  `category` varchar(50) DEFAULT 'general',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `youtube_id` (`youtube_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
