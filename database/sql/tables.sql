-- XapiVerse Database Schema
-- This file is imported during installation

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Drop existing tables (clean install)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `api_request_logs`;
DROP TABLE IF EXISTS `api_key_import_batches`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `user_api_keys`;
DROP TABLE IF EXISTS `api_source_keys`;
DROP TABLE IF EXISTS `credit_packages`;
DROP TABLE IF EXISTS `api_services`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','developer','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `total_credits_purchased` bigint(20) NOT NULL DEFAULT 0,
  `total_credits_used` bigint(20) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: password_reset_tokens
-- --------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: sessions
-- --------------------------------------------------------
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: cache
-- --------------------------------------------------------
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: jobs
-- --------------------------------------------------------
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: api_services
-- --------------------------------------------------------
CREATE TABLE `api_services` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `base_url` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL DEFAULT 'v1',
  `rotation_strategy` enum('round_robin','priority','least_used','weighted','fill_rotate') NOT NULL DEFAULT 'round_robin',
  `credits_per_request` int(11) NOT NULL DEFAULT 1,
  `rate_limit_per_minute` int(11) NOT NULL DEFAULT 60,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `endpoints` json DEFAULT NULL,
  `headers` json DEFAULT NULL,
  `documentation` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_services_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: api_source_keys
-- --------------------------------------------------------
CREATE TABLE `api_source_keys` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `api_service_id` bigint(20) UNSIGNED NOT NULL,
  `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free',
  `api_key` text NOT NULL,
  `base_url_override` varchar(255) DEFAULT NULL,
  `headers_override` json DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `daily_limit` bigint(20) UNSIGNED DEFAULT NULL,
  `monthly_limit` bigint(20) UNSIGNED DEFAULT NULL,
  `total_limit` bigint(20) UNSIGNED DEFAULT NULL,
  `used_today` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `used_this_month` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `used_total` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `priority` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `weight` int(10) UNSIGNED NOT NULL DEFAULT 50,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_exhausted` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `cooldown_until` timestamp NULL DEFAULT NULL,
  `last_error` varchar(255) DEFAULT NULL,
  `error_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `success_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `avg_response_time_ms` float NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `import_batch_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `api_source_keys_service_active` (`api_service_id`,`is_active`,`is_exhausted`),
  KEY `api_source_keys_service_priority` (`api_service_id`,`priority`,`last_used_at`),
  KEY `api_source_keys_service_used` (`api_service_id`,`used_today`),
  KEY `api_source_keys_batch` (`import_batch_id`),
  CONSTRAINT `api_source_keys_service_fk` FOREIGN KEY (`api_service_id`) REFERENCES `api_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: user_api_keys
-- --------------------------------------------------------
CREATE TABLE `user_api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `credits_balance` bigint(20) NOT NULL DEFAULT 0,
  `total_used` bigint(20) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_services` json DEFAULT NULL,
  `rate_limit_per_minute` int(11) NOT NULL DEFAULT 60,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_api_keys_api_key_unique` (`api_key`),
  KEY `user_api_keys_key_active` (`api_key`,`is_active`),
  KEY `user_api_keys_user_active` (`user_id`,`is_active`),
  CONSTRAINT `user_api_keys_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: credit_packages
-- --------------------------------------------------------
CREATE TABLE `credit_packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `credits` bigint(20) NOT NULL,
  `description` text DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: transactions
-- --------------------------------------------------------
CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `type` enum('purchase','bonus','refund','admin_credit','admin_debit') NOT NULL,
  `credits` bigint(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_transaction_id_unique` (`transaction_id`),
  KEY `transactions_user_status` (`user_id`,`status`),
  CONSTRAINT `transactions_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: api_request_logs
-- --------------------------------------------------------
CREATE TABLE `api_request_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_api_key_id` bigint(20) UNSIGNED DEFAULT NULL,
  `api_service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `api_source_key_id` bigint(20) UNSIGNED DEFAULT NULL,
  `endpoint` varchar(255) NOT NULL,
  `method` varchar(10) NOT NULL DEFAULT 'POST',
  `status` enum('success','failed','rate_limited','no_credits') NOT NULL,
  `http_status_code` int(11) DEFAULT NULL,
  `response_time_ms` float DEFAULT NULL,
  `credits_charged` int(11) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `request_params` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `api_request_logs_user_date` (`user_id`,`created_at`),
  KEY `api_request_logs_service_date` (`api_service_id`,`created_at`),
  KEY `api_request_logs_status_date` (`status`,`created_at`),
  KEY `api_request_logs_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: api_key_import_batches
-- --------------------------------------------------------
CREATE TABLE `api_key_import_batches` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_id` varchar(255) NOT NULL,
  `api_service_id` bigint(20) UNSIGNED NOT NULL,
  `imported_by` bigint(20) UNSIGNED NOT NULL,
  `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free',
  `total_imported` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_failed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `daily_limit_per_key` bigint(20) UNSIGNED DEFAULT NULL,
  `monthly_limit_per_key` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `status` enum('processing','completed','failed') NOT NULL DEFAULT 'processing',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key_import_batches_batch_id_unique` (`batch_id`),
  KEY `api_key_import_batches_service_fk` (`api_service_id`),
  KEY `api_key_import_batches_user_fk` (`imported_by`),
  CONSTRAINT `api_key_import_batches_service_fk` FOREIGN KEY (`api_service_id`) REFERENCES `api_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `api_key_import_batches_user_fk` FOREIGN KEY (`imported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: settings
-- --------------------------------------------------------
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_key` (`group`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: migrations (Laravel tracking)
-- --------------------------------------------------------
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mark migrations as run
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2024_01_01_000003_create_api_services_table', 1),
('2024_01_01_000004_create_api_source_keys_table', 1),
('2024_01_01_000005_create_user_api_keys_table', 1),
('2024_01_01_000006_create_credits_table', 1),
('2024_01_01_000007_create_api_request_logs_table', 1),
('2024_01_01_000008_create_api_key_import_batches_table', 1),
('2024_01_01_000009_create_settings_table', 1);

-- --------------------------------------------------------
-- Default Settings (always imported)
-- --------------------------------------------------------
INSERT IGNORE INTO `settings` (`group`, `key`, `value`, `type`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
('general', 'site_name', 'XapiVerse', 'string', 'Platform name', 0, NOW(), NOW()),
('general', 'site_description', 'Fast & Affordable APIs for Developers', 'string', 'Platform description', 1, NOW(), NOW()),
('general', 'site_version', '1.0.0', 'string', 'Platform version', 0, NOW(), NOW()),
('general', 'maintenance_mode', '0', 'boolean', 'Enable maintenance mode', 0, NOW(), NOW()),
('api', 'default_rate_limit', '60', 'integer', 'Default rate limit per minute', 0, NOW(), NOW()),
('api', 'free_credits_on_signup', '1000', 'integer', 'Free credits given to new developers', 0, NOW(), NOW()),
('api', 'max_keys_per_user', '10', 'integer', 'Max API keys per developer', 0, NOW(), NOW()),
('api', 'key_auto_disable_errors', '5', 'integer', 'Auto-disable source key after this many errors', 0, NOW(), NOW()),
('api', 'key_cooldown_seconds', '60', 'integer', 'Cooldown seconds after a source key error', 0, NOW(), NOW()),
('rotation', 'default_strategy', 'round_robin', 'string', 'Default rotation strategy for new services', 0, NOW(), NOW()),
('rotation', 'daily_reset_time', '00:00', 'string', 'Time to reset daily usage counters (UTC)', 0, NOW(), NOW()),
('rotation', 'retry_attempts', '3', 'integer', 'Number of source keys to try before failing', 0, NOW(), NOW()),
('payment', 'currency', 'USD', 'string', 'Default currency', 0, NOW(), NOW()),
('payment', 'min_purchase_amount', '1.00', 'string', 'Minimum purchase amount', 0, NOW(), NOW());

-- Default Credit Packages
INSERT IGNORE INTO `credit_packages` (`name`, `price`, `credits`, `description`, `is_popular`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Starter', 1.00, 25000, 'Perfect for testing', 0, 1, 1, NOW(), NOW()),
('Developer', 5.00, 150000, 'For small projects', 1, 1, 2, NOW(), NOW()),
('Business', 20.00, 750000, 'For production apps', 0, 1, 3, NOW(), NOW()),
('Enterprise', 100.00, 5000000, 'High-volume usage', 0, 1, 4, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
