-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 04:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Database: `lensi_db`
--
CREATE DATABASE IF NOT EXISTS lensi_db;
USE lensi_db;
-- --------------------------------------------------------
--
-- Table structure for table `admin_notifications`
--
CREATE TABLE IF NOT EXISTS `admin_notifications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `is_read` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `admin_tasks`
--
CREATE TABLE IF NOT EXISTS `admin_tasks` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task` varchar(255) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `completed` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `completed_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `applications`
--
CREATE TABLE IF NOT EXISTS `applications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `job_id` int(11) NOT NULL,
    `freelancer_id` int(11) NOT NULL,
    `proposal` text NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `status` enum('pending', 'accepted', 'rejected') DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_job_freelancer` (`job_id`, `freelancer_id`),
    KEY `freelancer_id` (`freelancer_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `connects`
--
CREATE TABLE IF NOT EXISTS `connects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `balance` int(11) NOT NULL DEFAULT 0,
    `total_purchased` int(11) NOT NULL DEFAULT 0,
    `total_spent` int(11) NOT NULL DEFAULT 0,
    `monthly_free` int(11) NOT NULL DEFAULT 0,
    `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `connect_packages`
--
CREATE TABLE IF NOT EXISTS `connect_packages` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `connects` int(11) NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `description` text DEFAULT NULL,
    `is_featured` tinyint(1) NOT NULL DEFAULT 0,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `connect_packages`
--
INSERT INTO `connect_packages` (
        `id`,
        `name`,
        `connects`,
        `price`,
        `description`,
        `is_featured`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'Starter',
        10,
        1.50,
        '10 connects to apply for jobs',
        0,
        1,
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        2,
        'Basic',
        20,
        3.00,
        '20 connects to apply for jobs',
        0,
        1,
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        3,
        'Standard',
        60,
        8.95,
        '60 connects to apply for jobs',
        1,
        1,
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        4,
        'Professional',
        120,
        15.95,
        '120 connects to apply for jobs',
        0,
        1,
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        5,
        'Enterprise',
        200,
        25.00,
        '200 connects to apply for jobs',
        0,
        1,
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    );
-- --------------------------------------------------------
--
-- Table structure for table `connect_transactions`
--
CREATE TABLE IF NOT EXISTS `connect_transactions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `amount` int(11) NOT NULL,
    `type` enum('purchase', 'spend', 'refund', 'monthly_grant') NOT NULL,
    `description` varchar(255) NOT NULL,
    `reference_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `contact_messages`
--
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `subject` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `inquiry_type` enum('general', 'technical', 'billing', 'business') DEFAULT 'general',
    `status` enum('new', 'in_progress', 'resolved', 'spam') DEFAULT 'new',
    `priority` enum('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    `browser_info` text DEFAULT NULL,
    `billing_details` text DEFAULT NULL,
    `business_details` text DEFAULT NULL,
    `is_read` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `resolved_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `idx_contact_inquiry_type` (`inquiry_type`),
    KEY `idx_contact_status` (`status`),
    KEY `idx_contact_created_at` (`created_at`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Triggers `contact_messages`
--
DELIMITER $$ CREATE TRIGGER `contact_messages_before_update` BEFORE
UPDATE ON `contact_messages` FOR EACH ROW BEGIN
SET NEW.updated_at = CURRENT_TIMESTAMP;
IF NEW.status = 'resolved'
AND OLD.status != 'resolved' THEN
SET NEW.resolved_at = CURRENT_TIMESTAMP;
END IF;
END $$ DELIMITER;
-- --------------------------------------------------------
--
-- Table structure for table `earnings`
--
CREATE TABLE IF NOT EXISTS `earnings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `total_earned` decimal(10, 2) NOT NULL DEFAULT 0.00,
    `available_balance` decimal(10, 2) NOT NULL DEFAULT 0.00,
    `pending_balance` decimal(10, 2) NOT NULL DEFAULT 0.00,
    `withdrawn` decimal(10, 2) NOT NULL DEFAULT 0.00,
    `last_payment_date` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `faqs`
--
CREATE TABLE IF NOT EXISTS `faqs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `question` varchar(255) NOT NULL,
    `answer` text NOT NULL,
    `category` varchar(100) NOT NULL,
    `status` enum('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 9 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `faqs`
--
INSERT INTO `faqs` (
        `id`,
        `question`,
        `answer`,
        `category`,
        `status`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'How do I create an account?',
        'To create an account, click on the \"Register\" button in the top right corner of the homepage. Fill in your details including name, email, and password, then click \"Sign Up\".',
        'account',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        2,
        'How do I reset my password?',
        'To reset your password, click on the \"Login\" button, then click on \"Forgot Password\". Enter your email address, and we\'ll send you instructions to reset your password.',
        'account',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        3,
        'What payment methods do you accept?',
        'We accept credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers. All transactions are securely processed and encrypted.',
        'payments',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        4,
        'How do I contact support?',
        'You can contact our support team by creating a support ticket through your dashboard. Alternatively, you can email us at support@lensi.com or use the live chat feature during business hours.',
        'support',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        5,
        'How do I submit a project proposal?',
        'Log in to your account, navigate to the Projects section, click on \"Create New Proposal\", fill in all required details about your project, and click submit. Our team will review your proposal within 48 hours.',
        'projects',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        6,
        'What are connects?',
        'Connects are the virtual currency used by freelancers to apply for jobs on our platform. Each job application requires a certain number of connects, typically between 2-6 depending on the job size.',
        'connects',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        7,
        'How many connects do I get?',
        'Free accounts receive 10 connects per month. Premium members receive 70 connects per month and can purchase additional connects if needed.',
        'connects',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    ),
    (
        8,
        'How much do connects cost?',
        'Connects can be purchased in packages. The standard rate is approximately $0.15 per connect, with discounts available when purchasing larger packages.',
        'connects',
        'active',
        '2025-04-29 21:10:29',
        '2025-04-29 21:10:29'
    );
-- --------------------------------------------------------
--
-- Table structure for table `freelancer_profiles`
--
CREATE TABLE IF NOT EXISTS `freelancer_profiles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `hourly_rate` decimal(10, 2) NOT NULL DEFAULT 0.00,
    `title` varchar(255) DEFAULT NULL,
    `availability` enum('Full-time', 'Part-time', 'Weekends', 'Limited') DEFAULT 'Full-time',
    `experience_level` enum('Entry', 'Intermediate', 'Expert') DEFAULT 'Entry',
    `completed_projects` int(11) NOT NULL DEFAULT 0,
    `success_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
    `verified` tinyint(1) NOT NULL DEFAULT 0,
    `last_active` timestamp NOT NULL DEFAULT current_timestamp(),
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `jobs`
--
CREATE TABLE IF NOT EXISTS `jobs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `budget` decimal(10, 2) NOT NULL,
    `skills` text DEFAULT NULL,
    `category` varchar(50) NOT NULL,
    `status` enum('active', 'completed', 'deleted') DEFAULT 'active',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `notifications`
--
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `type` varchar(50) NOT NULL,
    `title` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `is_read` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `orders`
--
CREATE TABLE IF NOT EXISTS `orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `service_id` int(11) NOT NULL,
    `client_id` int(11) NOT NULL,
    `provider_id` int(11) NOT NULL,
    `requirements` text NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `status` enum('pending', 'accepted', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`),
    KEY `client_id` (`client_id`),
    KEY `provider_id` (`provider_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `order_messages`
--
CREATE TABLE IF NOT EXISTS `order_messages` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `message` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `reviews`
--
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `service_id` int(11) NOT NULL,
    `client_id` int(11) NOT NULL,
    `provider_id` int(11) NOT NULL,
    `rating` int(11) NOT NULL CHECK (
        `rating` >= 1
        and `rating` <= 5
    ),
    `comment` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_order_review` (`order_id`),
    KEY `service_id` (`service_id`),
    KEY `client_id` (`client_id`),
    KEY `provider_id` (`provider_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `services`
--
CREATE TABLE IF NOT EXISTS `services` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `category` varchar(50) NOT NULL,
    `status` enum('active', 'inactive', 'deleted') DEFAULT 'active',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `settings`
--
CREATE TABLE IF NOT EXISTS `settings` (
    `id` int(11) NOT NULL DEFAULT 1,
    `site_name` varchar(255) NOT NULL DEFAULT 'Lensi',
    `site_description` text DEFAULT NULL,
    `contact_email` varchar(255) DEFAULT NULL,
    `support_phone` varchar(50) DEFAULT NULL,
    `logo` varchar(255) DEFAULT NULL,
    `primary_color` varchar(20) DEFAULT '#007bff',
    `secondary_color` varchar(20) DEFAULT '#6c757d',
    `default_theme` varchar(20) DEFAULT 'light',
    `smtp_host` varchar(255) DEFAULT NULL,
    `smtp_port` int(11) DEFAULT NULL,
    `smtp_username` varchar(255) DEFAULT NULL,
    `smtp_password` varchar(255) DEFAULT NULL,
    `from_email` varchar(255) DEFAULT NULL,
    `from_name` varchar(255) DEFAULT NULL,
    `enable_recaptcha` tinyint(1) DEFAULT 0,
    `enable_2fa` tinyint(1) DEFAULT 0,
    `recaptcha_site_key` varchar(255) DEFAULT NULL,
    `recaptcha_secret_key` varchar(255) DEFAULT NULL,
    `currency` varchar(10) DEFAULT 'USD',
    `platform_fee` decimal(5, 2) DEFAULT 5.00,
    `enable_paypal` tinyint(1) DEFAULT 1,
    `enable_stripe` tinyint(1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `settings`
--
INSERT INTO `settings` (
        `id`,
        `site_name`,
        `site_description`,
        `contact_email`,
        `support_phone`,
        `logo`,
        `primary_color`,
        `secondary_color`,
        `default_theme`,
        `smtp_host`,
        `smtp_port`,
        `smtp_username`,
        `smtp_password`,
        `from_email`,
        `from_name`,
        `enable_recaptcha`,
        `enable_2fa`,
        `recaptcha_site_key`,
        `recaptcha_secret_key`,
        `currency`,
        `platform_fee`,
        `enable_paypal`,
        `enable_stripe`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'Lensi',
        'Freelance Services Marketplace',
        NULL,
        NULL,
        NULL,
        '#007bff',
        '#6c757d',
        'light',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        0,
        0,
        NULL,
        NULL,
        'USD',
        5.00,
        1,
        1,
        '2025-04-29 15:37:33',
        '2025-04-29 15:37:33'
    );
-- --------------------------------------------------------
--
-- Table structure for table `support_replies`
--
CREATE TABLE IF NOT EXISTS `support_replies` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ticket_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `message` text NOT NULL,
    `is_admin` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `ticket_id` (`ticket_id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Table structure for table `support_tickets`
--
CREATE TABLE IF NOT EXISTS `support_tickets` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `subject` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `category` varchar(50) NOT NULL,
    `priority` enum('low', 'medium', 'high') DEFAULT 'medium',
    `status` enum('open', 'pending', 'answered', 'closed') DEFAULT 'open',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `support_tickets`
--
INSERT INTO `support_tickets` (
        `id`,
        `user_id`,
        `subject`,
        `description`,
        `category`,
        `priority`,
        `status`,
        `created_at`,
        `updated_at`
    )
VALUES (
        2,
        5,
        'seifeddine',
        'provide me with good data wallah',
        'billing',
        'low',
        'open',
        '2025-04-29 22:34:52',
        '2025-04-29 22:38:09'
    ),
    (
        3,
        5,
        'mister salta3',
        'spongebob ahmer',
        'other',
        'high',
        'open',
        '2025-04-29 22:46:54',
        '2025-04-29 22:46:54'
    ),
    (
        5,
        5,
        'aaaaa',
        'mmm',
        'account',
        'medium',
        'open',
        '2025-04-29 23:44:04',
        '2025-05-01 13:35:03'
    );
-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `account_type` enum('client', 'freelancer', 'admin') NOT NULL DEFAULT 'freelancer',
    `country` varchar(100) DEFAULT NULL,
    `promo_emails` tinyint(1) DEFAULT 1,
    `terms_accepted` tinyint(1) NOT NULL DEFAULT 0,
    `bio` text DEFAULT NULL,
    `skills` text DEFAULT NULL,
    `profile_image` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `reset_token` varchar(255) DEFAULT NULL,
    `reset_expires` datetime DEFAULT NULL,
    `remember_token` varchar(255) DEFAULT NULL,
    `token_expires` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `users`
--
INSERT INTO `users` (
        `id`,
        `name`,
        `email`,
        `password`,
        `account_type`,
        `country`,
        `promo_emails`,
        `terms_accepted`,
        `bio`,
        `skills`,
        `profile_image`,
        `created_at`,
        `updated_at`,
        `reset_token`,
        `reset_expires`,
        `remember_token`,
        `token_expires`
    )
VALUES (
        5,
        'seifeddine kefi',
        'seifeddine.kefi@esprit.tn',
        '$2y$10$gyYqHpsCgiHdoq8Ayeb5CeAd1lN4OBY/sbkjvLXgadBSiwuNYOFOi',
        'freelancer',
        'TN',
        1,
        1,
        NULL,
        NULL,
        NULL,
        '2025-04-29 16:57:17',
        '2025-04-29 16:57:17',
        NULL,
        NULL,
        NULL,
        NULL
    ),
    (
        6,
        'seifeddine kefi',
        'kefiseifeddine@gmail.com',
        '$2y$10$Kvzh/KWTTW8bXbkfr77OxeKpfoJHQRRJo/7F0lpY56Aib6ackggWK',
        'client',
        'TN',
        0,
        1,
        NULL,
        NULL,
        NULL,
        '2025-04-29 21:32:38',
        '2025-04-29 21:32:38',
        NULL,
        NULL,
        NULL,
        NULL
    );
-- --------------------------------------------------------
--
-- Table structure for table `user_stats`
--
CREATE TABLE IF NOT EXISTS `user_stats` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `projects_completed` int(11) NOT NULL DEFAULT 0,
    `in_progress_projects` int(11) NOT NULL DEFAULT 0,
    `proposals_sent` int(11) NOT NULL DEFAULT 0,
    `success_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
    `profile_views` int(11) NOT NULL DEFAULT 0,
    `search_appearances` int(11) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Stand-in structure for view `vw_contact_messages`
--
CREATE OR REPLACE VIEW `vw_contact_messages` AS
SELECT `cm`.`id` AS `id`,
    `cm`.`user_id` AS `user_id`,
    `cm`.`name` AS `name`,
    `cm`.`email` AS `email`,
    `cm`.`subject` AS `subject`,
    `cm`.`message` AS `message`,
    `cm`.`inquiry_type` AS `inquiry_type`,
    `cm`.`status` AS `status`,
    `cm`.`priority` AS `priority`,
    `cm`.`browser_info` AS `browser_info`,
    `cm`.`billing_details` AS `billing_details`,
    `cm`.`business_details` AS `business_details`,
    `cm`.`is_read` AS `is_read`,
    `cm`.`created_at` AS `created_at`,
    `cm`.`updated_at` AS `updated_at`,
    `cm`.`resolved_at` AS `resolved_at`,
    `u`.`name` AS `user_name`,
    `u`.`email` AS `user_email`,
    `u`.`account_type` AS `account_type`
FROM (
        `contact_messages` `cm`
        JOIN `users` `u` ON(`cm`.`user_id` = `u`.`id`)
    );
-- --------------------------------------------------------
--
-- Constraints for dumped tables
--
--
-- Constraints for table `admin_tasks`
--
ALTER TABLE `admin_tasks`
ADD CONSTRAINT `admin_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE
SET NULL;
--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `connects`
--
ALTER TABLE `connects`
ADD CONSTRAINT `connects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `connect_transactions`
--
ALTER TABLE `connect_transactions`
ADD CONSTRAINT `connect_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `earnings`
--
ALTER TABLE `earnings`
ADD CONSTRAINT `earnings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `freelancer_profiles`
--
ALTER TABLE `freelancer_profiles`
ADD CONSTRAINT `freelancer_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
    ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
    ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`);
--
-- Constraints for table `order_messages`
--
ALTER TABLE `order_messages`
ADD CONSTRAINT `order_messages_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
    ADD CONSTRAINT `order_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
    ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
    ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
    ADD CONSTRAINT `reviews_ibfk_4` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`);
--
-- Constraints for table `services`
--
ALTER TABLE `services`
ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `support_replies`
--
ALTER TABLE `support_replies`
ADD CONSTRAINT `support_replies_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `support_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Constraints for table `user_stats`
--
ALTER TABLE `user_stats`
ADD CONSTRAINT `user_stats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
-- Community Database Tables
-- Created: May 2, 2025
-- Forums Tables
CREATE TABLE IF NOT EXISTS forum_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    slug VARCHAR(150) NOT NULL UNIQUE,
    icon VARCHAR(50),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS forum_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    status ENUM('open', 'closed', 'pinned', 'archived') DEFAULT 'open',
    views INT DEFAULT 0,
    is_pinned BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    has_solution BOOLEAN DEFAULT FALSE,
    reply_count INT DEFAULT 0,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES forum_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS forum_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    is_solution BOOLEAN DEFAULT FALSE,
    is_edited BOOLEAN DEFAULT FALSE,
    edited_at TIMESTAMP NULL DEFAULT NULL,
    editor_id INT NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (editor_id) REFERENCES users(id) ON DELETE
    SET NULL
);
CREATE TABLE IF NOT EXISTS forum_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content_type ENUM('topic', 'reply') NOT NULL,
    content_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, content_type, content_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- Groups Tables
CREATE TABLE IF NOT EXISTS community_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    slug VARCHAR(150) NOT NULL UNIQUE,
    cover_image VARCHAR(255),
    creator_id INT NOT NULL,
    is_private BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('member', 'moderator', 'admin') DEFAULT 'member',
    status ENUM('pending', 'approved', 'rejected', 'banned') DEFAULT 'pending',
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_membership (group_id, user_id),
    FOREIGN KEY (group_id) REFERENCES community_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS group_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    attachment VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES community_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS group_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES group_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- Resources Tables
CREATE TABLE IF NOT EXISTS resource_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS community_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    content TEXT,
    resource_type ENUM(
        'article',
        'tutorial',
        'template',
        'tool',
        'other'
    ) DEFAULT 'article',
    file_path VARCHAR(255),
    external_link VARCHAR(255),
    thumbnail VARCHAR(255),
    status ENUM('draft', 'published', 'archived') DEFAULT 'published',
    views INT DEFAULT 0,
    downloads INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES resource_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS resource_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resource_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (
        rating BETWEEN 1 AND 5
    ),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rating (resource_id, user_id),
    FOREIGN KEY (resource_id) REFERENCES community_resources(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS resource_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(60) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS resource_tag_relations (
    resource_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (resource_id, tag_id),
    FOREIGN KEY (resource_id) REFERENCES community_resources(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES resource_tags(id) ON DELETE CASCADE
);
-- Sample Data for Testing
INSERT INTO forum_categories (name, description, slug, icon, display_order)
VALUES (
        'General Discussion',
        'General community discussions and topics',
        'general-discussion',
        'chat-dots',
        1
    ),
    (
        'Project Showcase',
        'Share your projects and get feedback',
        'project-showcase',
        'laptop-code',
        2
    ),
    (
        'Help & Support',
        'Get help with your issues and questions',
        'help-support',
        'question-circle',
        3
    );
INSERT INTO resource_categories (name, description, icon, display_order)
VALUES (
        'Tutorials',
        'Step-by-step guides to learn new skills',
        'book',
        1
    ),
    (
        'Templates',
        'Ready-to-use starter templates and boilerplates',
        'file-earmark-code',
        2
    ),
    (
        'Tools',
        'Useful tools and utilities for your projects',
        'tools',
        3
    );