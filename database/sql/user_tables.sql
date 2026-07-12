-- XapiVerse User Platform Tables
-- Run after main tables.sql

CREATE TABLE IF NOT EXISTS `watch_history` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `link` text NOT NULL,
    `title` varchar(500) DEFAULT NULL,
    `thumbnail` varchar(500) DEFAULT NULL,
    `file_size` varchar(50) DEFAULT NULL,
    `duration` varchar(20) DEFAULT NULL,
    `status` enum('success','failed','pending') NOT NULL DEFAULT 'success',
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `wh_user` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `bookmarks` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `link` text NOT NULL,
    `title` varchar(500) DEFAULT NULL,
    `thumbnail` varchar(500) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `bm_user` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `support_tickets` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `ticket_id` varchar(20) NOT NULL,
    `subject` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
    `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
    `admin_reply` text DEFAULT NULL,
    `replied_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `st_ticket` (`ticket_id`),
    KEY `st_user` (`user_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
    `is_read` tinyint(1) NOT NULL DEFAULT 0,
    `link` varchar(500) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `notif_user` (`user_id`,`is_read`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_subscriptions` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `plan` enum('free','pro','enterprise') NOT NULL DEFAULT 'free',
    `daily_limit` int NOT NULL DEFAULT 5,
    `starts_at` timestamp NULL DEFAULT NULL,
    `expires_at` timestamp NULL DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `us_user` (`user_id`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `download_stats` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `link` text NOT NULL,
    `title` varchar(500) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `ds_user` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
