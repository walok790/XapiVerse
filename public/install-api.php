<?php
/**
 * XapiVerse Installation API - Standalone endpoint
 * This file runs OUTSIDE Laravel to avoid any middleware/session/CSRF issues.
 * It directly uses PDO to create tables one at a time.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'POST only']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$step = (int)($input['step'] ?? 0);
$host = $input['db_host'] ?? '127.0.0.1';
$port = $input['db_port'] ?? '3306';
$dbName = $input['db_name'] ?? 'xapiverse_db';
$user = $input['db_user'] ?? 'root';
$pass = $input['db_password'] ?? '';
$mode = $input['mode'] ?? 'demo';

// Connect to database
try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbName}", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

// Table definitions
$tables = [
    'users' => "CREATE TABLE `users` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `email` varchar(255) NOT NULL, `email_verified_at` timestamp NULL, `password` varchar(255) NOT NULL, `role` enum('admin','developer','user') NOT NULL DEFAULT 'user', `is_active` tinyint(1) NOT NULL DEFAULT 1, `avatar` varchar(255) DEFAULT NULL, `bio` text, `company` varchar(255) DEFAULT NULL, `website` varchar(255) DEFAULT NULL, `total_credits_purchased` bigint NOT NULL DEFAULT 0, `total_credits_used` bigint NOT NULL DEFAULT 0, `last_login_at` timestamp NULL, `last_login_ip` varchar(255) DEFAULT NULL, `remember_token` varchar(100) DEFAULT NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, `deleted_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY `users_email_unique` (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'password_reset_tokens' => "CREATE TABLE `password_reset_tokens` (`email` varchar(255) NOT NULL, `token` varchar(255) NOT NULL, `created_at` timestamp NULL, PRIMARY KEY (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'sessions' => "CREATE TABLE `sessions` (`id` varchar(255) NOT NULL, `user_id` bigint unsigned DEFAULT NULL, `ip_address` varchar(45) DEFAULT NULL, `user_agent` text, `payload` longtext NOT NULL, `last_activity` int NOT NULL, PRIMARY KEY (`id`), KEY `sessions_user_id` (`user_id`), KEY `sessions_last_activity` (`last_activity`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'cache' => "CREATE TABLE `cache` (`key` varchar(255) NOT NULL, `value` mediumtext NOT NULL, `expiration` int NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'cache_locks' => "CREATE TABLE `cache_locks` (`key` varchar(255) NOT NULL, `owner` varchar(255) NOT NULL, `expiration` int NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'jobs' => "CREATE TABLE `jobs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `queue` varchar(255) NOT NULL, `payload` longtext NOT NULL, `attempts` tinyint unsigned NOT NULL, `reserved_at` int unsigned DEFAULT NULL, `available_at` int unsigned NOT NULL, `created_at` int unsigned NOT NULL, PRIMARY KEY (`id`), KEY `jobs_queue` (`queue`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'failed_jobs' => "CREATE TABLE `failed_jobs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `uuid` varchar(255) NOT NULL, `connection` text NOT NULL, `queue` text NOT NULL, `payload` longtext NOT NULL, `exception` longtext NOT NULL, `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `failed_jobs_uuid` (`uuid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'api_services' => "CREATE TABLE `api_services` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `slug` varchar(255) NOT NULL, `description` text, `icon` varchar(255) DEFAULT NULL, `base_url` varchar(255) NOT NULL, `version` varchar(255) NOT NULL DEFAULT 'v1', `rotation_strategy` enum('round_robin','priority','least_used','weighted','fill_rotate') NOT NULL DEFAULT 'round_robin', `credits_per_request` int NOT NULL DEFAULT 1, `rate_limit_per_minute` int NOT NULL DEFAULT 60, `is_active` tinyint(1) NOT NULL DEFAULT 1, `is_public` tinyint(1) NOT NULL DEFAULT 1, `endpoints` text DEFAULT NULL, `headers` text DEFAULT NULL, `documentation` text, `sort_order` int NOT NULL DEFAULT 0, `created_at` timestamp NULL, `updated_at` timestamp NULL, `deleted_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY `api_services_slug` (`slug`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'api_source_keys' => "CREATE TABLE `api_source_keys` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `api_service_id` bigint unsigned NOT NULL, `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free', `api_key` text NOT NULL, `base_url_override` varchar(255) DEFAULT NULL, `headers_override` text DEFAULT NULL, `label` varchar(255) DEFAULT NULL, `daily_limit` bigint unsigned DEFAULT NULL, `monthly_limit` bigint unsigned DEFAULT NULL, `total_limit` bigint unsigned DEFAULT NULL, `used_today` bigint unsigned NOT NULL DEFAULT 0, `used_this_month` bigint unsigned NOT NULL DEFAULT 0, `used_total` bigint unsigned NOT NULL DEFAULT 0, `priority` int unsigned NOT NULL DEFAULT 5, `weight` int unsigned NOT NULL DEFAULT 50, `is_active` tinyint(1) NOT NULL DEFAULT 1, `is_exhausted` tinyint(1) NOT NULL DEFAULT 0, `last_used_at` timestamp NULL, `cooldown_until` timestamp NULL, `last_error` varchar(255) DEFAULT NULL, `error_count` int unsigned NOT NULL DEFAULT 0, `success_count` int unsigned NOT NULL DEFAULT 0, `avg_response_time_ms` float NOT NULL DEFAULT 0, `notes` text, `import_batch_id` varchar(255) DEFAULT NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), KEY `ask_svc` (`api_service_id`,`is_active`,`is_exhausted`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'user_api_keys' => "CREATE TABLE `user_api_keys` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `user_id` bigint unsigned NOT NULL, `name` varchar(255) NOT NULL, `api_key` varchar(64) NOT NULL, `prefix` varchar(10) NOT NULL, `credits_balance` bigint NOT NULL DEFAULT 0, `total_used` bigint NOT NULL DEFAULT 0, `is_active` tinyint(1) NOT NULL DEFAULT 1, `allowed_services` text DEFAULT NULL, `rate_limit_per_minute` int NOT NULL DEFAULT 60, `last_used_at` timestamp NULL, `expires_at` timestamp NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, `deleted_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY `uak_api_key` (`api_key`), KEY `uak_user` (`user_id`,`is_active`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'credit_packages' => "CREATE TABLE `credit_packages` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `price` decimal(10,2) NOT NULL, `credits` bigint NOT NULL, `description` text, `is_popular` tinyint(1) NOT NULL DEFAULT 0, `is_active` tinyint(1) NOT NULL DEFAULT 1, `sort_order` int NOT NULL DEFAULT 0, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'transactions' => "CREATE TABLE `transactions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `user_id` bigint unsigned NOT NULL, `transaction_id` varchar(255) NOT NULL, `type` enum('purchase','bonus','refund','admin_credit','admin_debit') NOT NULL, `credits` bigint NOT NULL, `amount` decimal(10,2) NOT NULL DEFAULT 0.00, `currency` varchar(3) NOT NULL DEFAULT 'USD', `payment_method` varchar(255) DEFAULT NULL, `payment_id` varchar(255) DEFAULT NULL, `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending', `notes` text, `meta` text DEFAULT NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY `transactions_tid` (`transaction_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'api_request_logs' => "CREATE TABLE `api_request_logs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `user_id` bigint unsigned DEFAULT NULL, `user_api_key_id` bigint unsigned DEFAULT NULL, `api_service_id` bigint unsigned DEFAULT NULL, `api_source_key_id` bigint unsigned DEFAULT NULL, `endpoint` varchar(255) NOT NULL, `method` varchar(10) NOT NULL DEFAULT 'POST', `status` enum('success','failed','rate_limited','no_credits') NOT NULL, `http_status_code` int DEFAULT NULL, `response_time_ms` float DEFAULT NULL, `credits_charged` int NOT NULL DEFAULT 0, `ip_address` varchar(45) DEFAULT NULL, `error_message` text, `request_params` text DEFAULT NULL, `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), KEY `arl_date` (`created_at`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'api_key_import_batches' => "CREATE TABLE `api_key_import_batches` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `batch_id` varchar(255) NOT NULL, `api_service_id` bigint unsigned NOT NULL, `imported_by` bigint unsigned NOT NULL, `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free', `total_imported` int unsigned NOT NULL DEFAULT 0, `total_failed` int unsigned NOT NULL DEFAULT 0, `daily_limit_per_key` bigint unsigned DEFAULT NULL, `monthly_limit_per_key` bigint unsigned DEFAULT NULL, `priority` int unsigned NOT NULL DEFAULT 5, `status` enum('processing','completed','failed') NOT NULL DEFAULT 'processing', `notes` text, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY `akib_batch` (`batch_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'settings' => "CREATE TABLE `settings` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `group` varchar(255) NOT NULL DEFAULT 'general', `key` varchar(255) NOT NULL, `value` text, `type` varchar(255) NOT NULL DEFAULT 'string', `description` text, `is_public` tinyint(1) NOT NULL DEFAULT 0, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY `settings_key` (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'migrations' => "CREATE TABLE `migrations` (`id` int unsigned NOT NULL AUTO_INCREMENT, `migration` varchar(255) NOT NULL, `batch` int NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

$tableNames = array_keys($tables);
$totalSteps = count($tables) + 2; // drop + tables + data

try {
    // Step 0: Drop all tables
    if ($step === 0) {
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
        foreach ($tableNames as $t) {
            $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
        }
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
        echo json_encode(['success' => true, 'message' => 'Cleaned database', 'next' => 1, 'total' => $totalSteps]);
        exit;
    }

    // Steps 1-16: Create tables one by one
    if ($step >= 1 && $step <= count($tables)) {
        $idx = $step - 1;
        $name = $tableNames[$idx];
        $pdo->exec($tables[$name]);
        echo json_encode(['success' => true, 'message' => 'Created: ' . $name, 'next' => $step + 1, 'total' => $totalSteps]);
        exit;
    }

    // Final step: Insert data
    if ($step === count($tables) + 1) {
        // Default settings
        $stmt = $pdo->prepare("INSERT INTO settings (`group`,`key`,`value`,`type`,`description`,`is_public`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,0,NOW(),NOW())");
        $stmt->execute(['general','site_name','XapiVerse','string','Platform name']);
        $stmt->execute(['general','site_description','Fast & Affordable APIs','string','Description']);
        $stmt->execute(['general','site_version','1.0.0','string','Version']);
        $stmt->execute(['general','maintenance_mode','0','boolean','Maintenance']);
        $stmt->execute(['api','default_rate_limit','60','integer','Rate limit']);
        $stmt->execute(['api','free_credits_on_signup','1000','integer','Free credits']);
        $stmt->execute(['api','max_keys_per_user','10','integer','Max keys']);
        $stmt->execute(['rotation','default_strategy','round_robin','string','Strategy']);
        $stmt->execute(['rotation','retry_attempts','3','integer','Retries']);
        $stmt->execute(['payment','currency','USD','string','Currency']);

        // Credit packages
        $pkg = $pdo->prepare("INSERT INTO credit_packages (name,price,credits,description,is_popular,sort_order,is_active,created_at,updated_at) VALUES (?,?,?,?,?,?,1,NOW(),NOW())");
        $pkg->execute(['Starter',1.00,25000,'For testing',0,1]);
        $pkg->execute(['Developer',5.00,150000,'For projects',1,2]);
        $pkg->execute(['Business',20.00,750000,'Production',0,3]);
        $pkg->execute(['Enterprise',100.00,5000000,'High-volume',0,4]);

        // Demo data
        if ($mode === 'demo') {
            $hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 10]);
            $u = $pdo->prepare("INSERT INTO users (name,email,email_verified_at,password,role,is_active,created_at,updated_at) VALUES (?,?,NOW(),?,?,1,NOW(),NOW())");
            $u->execute(['Admin User','admin@xapiverse.com',$hash,'admin']);
            $u->execute(['Demo Developer','dev@xapiverse.com',$hash,'developer']);
            $u->execute(['Demo User','user@xapiverse.com',$hash,'user']);

            $pdo->exec("INSERT INTO user_api_keys (user_id,name,api_key,prefix,credits_balance,total_used,is_active,rate_limit_per_minute,created_at,updated_at) VALUES (2,'Demo Key','xv_live_demo_1234567890abcdef12345678','xv_live_',50000,1250,1,60,NOW(),NOW())");
            $pdo->exec("INSERT INTO api_services (name,slug,description,base_url,rotation_strategy,is_active,is_public,sort_order,created_at,updated_at) VALUES ('TeraBox API','terabox','Download from TeraBox','https://api.example.com/terabox','round_robin',1,1,1,NOW(),NOW())");
            $pdo->exec("INSERT INTO api_services (name,slug,description,base_url,rotation_strategy,is_active,is_public,sort_order,created_at,updated_at) VALUES ('Twitter API','twitter','Extract from tweets','https://api.example.com/twitter','least_used',1,1,2,NOW(),NOW())");

            $k = $pdo->prepare("INSERT INTO api_source_keys (api_service_id,key_type,api_key,daily_limit,used_today,used_total,priority,weight,is_active,is_exhausted,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,1,0,NOW(),NOW())");
            $k->execute([1,'master','demo_tb_m1',10000,4320,45000,1,80]);
            $k->execute([1,'free','demo_tb_f1',100,87,5400,5,50]);
            $k->execute([1,'free','demo_tb_f2',100,45,4200,5,50]);
            $k->execute([2,'master','demo_tw_m1',5000,2100,18000,1,80]);
            $k->execute([2,'free','demo_tw_f1',50,38,1200,5,50]);

            $stmt->execute(['general','install_mode','demo','string','Install mode']);
        }

        // Save .env
        $envPath = dirname(__DIR__) . '/.env';
        $envContent = file_get_contents($envPath);
        $envVars = [
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $host,
            'DB_PORT' => $port,
            'DB_DATABASE' => $dbName,
            'DB_USERNAME' => $user,
            'DB_PASSWORD' => $pass,
            'SESSION_DRIVER' => 'database',
            'CACHE_STORE' => 'database',
            'APP_MODE' => $mode,
        ];
        if ($mode === 'demo') {
            $envVars['APP_NAME'] = '"XapiVerse Demo"';
        }
        foreach ($envVars as $k => $v) {
            if (preg_match('/^' . preg_quote($k, '/') . '=.*/m', $envContent)) {
                $envContent = preg_replace('/^' . preg_quote($k, '/') . '=.*/m', $k . '=' . $v, $envContent);
            } else {
                $envContent .= "\n" . $k . '=' . $v;
            }
        }
        file_put_contents($envPath, $envContent);

        // Mark installed
        $lockDir = dirname(__DIR__) . '/storage/installed';
        if (!is_dir($lockDir)) mkdir($lockDir, 0777, true);
        file_put_contents($lockDir . '/installed.lock', json_encode(['installed_at' => date('Y-m-d H:i:s'), 'version' => '1.0.0', 'mode' => $mode]));

        $redirect = ($mode === 'demo') ? '/admin/login' : '/install/account';
        echo json_encode(['success' => true, 'message' => 'Installation complete!', 'done' => true, 'redirect' => $redirect]);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Step ' . $step . ': ' . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid step: ' . $step]);
