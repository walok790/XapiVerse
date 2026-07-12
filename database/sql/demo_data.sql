-- XapiVerse Demo Data
-- Imported only in Demo mode during installation

-- Clear any existing data first
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `user_api_keys`;
TRUNCATE TABLE `api_services`;
TRUNCATE TABLE `api_source_keys`;
SET FOREIGN_KEY_CHECKS=1;

-- Demo Users (password = "password" bcrypt hash)
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `company`, `website`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@xapiverse.com', NOW(), '$2y$12$YourHashWillBeReplacedByInstaller', 'admin', 1, NULL, NULL, NOW(), NOW()),
('Demo Developer', 'dev@xapiverse.com', NOW(), '$2y$12$YourHashWillBeReplacedByInstaller', 'developer', 1, 'Demo Company', 'https://example.com', NOW(), NOW()),
('Demo User', 'user@xapiverse.com', NOW(), '$2y$12$YourHashWillBeReplacedByInstaller', 'user', 1, NULL, NULL, NOW(), NOW());

-- Demo API Services
INSERT INTO `api_services` (`name`, `slug`, `description`, `base_url`, `version`, `rotation_strategy`, `credits_per_request`, `rate_limit_per_minute`, `is_active`, `is_public`, `endpoints`, `sort_order`, `created_at`, `updated_at`) VALUES
('TeraBox API', 'terabox', 'Get download links, HLS streaming up to 4K, multi-language subtitles, and file metadata from TeraBox links.', 'https://api.example.com/terabox', 'v1', 'round_robin', 1, 60, 1, 1, '[{\"method\":\"POST\",\"path\":\"/download\",\"description\":\"Get download link\"},{\"method\":\"POST\",\"path\":\"/stream\",\"description\":\"Get HLS streaming URL\"},{\"method\":\"POST\",\"path\":\"/info\",\"description\":\"Get file metadata\"}]', 1, NOW(), NOW()),
('X (Twitter) API', 'twitter', 'Extract media, engagement stats, and author info from X (Twitter) tweets.', 'https://api.example.com/twitter', 'v1', 'least_used', 1, 30, 1, 1, '[{\"method\":\"POST\",\"path\":\"/media\",\"description\":\"Extract media from tweet\"},{\"method\":\"POST\",\"path\":\"/info\",\"description\":\"Get tweet information\"}]', 2, NOW(), NOW()),
('Instagram API', 'instagram', 'Download reels, stories, posts and profile information from Instagram.', 'https://api.example.com/instagram', 'v1', 'priority', 2, 20, 0, 1, '[{\"method\":\"POST\",\"path\":\"/reels\",\"description\":\"Download reels\"},{\"method\":\"POST\",\"path\":\"/posts\",\"description\":\"Download posts\"}]', 3, NOW(), NOW());

-- Demo API Key for developer
INSERT INTO `user_api_keys` (`user_id`, `name`, `api_key`, `prefix`, `credits_balance`, `total_used`, `is_active`, `rate_limit_per_minute`, `created_at`, `updated_at`) VALUES
(2, 'Demo Key', 'xv_live_demo_key_123456789abcdef0123456789abcdef', 'xv_live_', 50000, 1250, 1, 60, NOW(), NOW());

-- Demo Source Keys for TeraBox (10 keys)
INSERT INTO `api_source_keys` (`api_service_id`, `key_type`, `api_key`, `daily_limit`, `monthly_limit`, `used_today`, `used_this_month`, `used_total`, `priority`, `weight`, `is_active`, `is_exhausted`, `success_count`, `avg_response_time_ms`, `created_at`, `updated_at`) VALUES
(1, 'master', 'demo_terabox_master_001', 10000, 300000, 4320, 145000, 45000, 1, 80, 1, 0, 8500, 45.2, NOW(), NOW()),
(1, 'master', 'demo_terabox_master_002', 10000, 300000, 6100, 180000, 52000, 1, 80, 1, 0, 9200, 38.7, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_001', 100, 3000, 87, 2100, 5400, 5, 50, 1, 0, 4800, 120.5, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_002', 100, 3000, 45, 1800, 4200, 5, 50, 1, 0, 3900, 95.3, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_003', 100, 3000, 100, 2500, 6100, 5, 50, 1, 1, 5500, 110.8, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_004', 100, 3000, 12, 900, 2300, 5, 50, 1, 0, 2100, 88.4, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_005', 100, 3000, 67, 1600, 3800, 5, 50, 1, 0, 3500, 102.1, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_006', 100, 3000, 34, 1200, 2900, 5, 50, 1, 0, 2700, 115.6, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_007', 100, 3000, 91, 2400, 5800, 5, 50, 1, 0, 5200, 98.9, NOW(), NOW()),
(1, 'free', 'demo_terabox_free_008', 100, 3000, 55, 1500, 3400, 5, 50, 1, 0, 3100, 107.2, NOW(), NOW());

-- Demo Source Keys for Twitter (5 keys)
INSERT INTO `api_source_keys` (`api_service_id`, `key_type`, `api_key`, `daily_limit`, `used_today`, `used_total`, `priority`, `is_active`, `is_exhausted`, `created_at`, `updated_at`) VALUES
(2, 'master', 'demo_twitter_master_001', 5000, 2100, 18000, 1, 1, 0, NOW(), NOW()),
(2, 'free', 'demo_twitter_free_001', 50, 38, 1200, 5, 1, 0, NOW(), NOW()),
(2, 'free', 'demo_twitter_free_002', 50, 50, 1500, 5, 1, 1, NOW(), NOW()),
(2, 'free', 'demo_twitter_free_003', 50, 22, 800, 5, 1, 0, NOW(), NOW()),
(2, 'free', 'demo_twitter_free_004', 50, 15, 650, 5, 1, 0, NOW(), NOW());

-- Store demo mode setting
INSERT INTO `settings` (`group`, `key`, `value`, `type`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
('general', 'install_mode', 'demo', 'string', 'Installation mode', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE `value` = 'demo';
