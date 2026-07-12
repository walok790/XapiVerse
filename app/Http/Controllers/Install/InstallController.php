<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function requirements()
    {
        $requirements = [
            'PHP Version (≥ 8.2)' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'BCMath' => extension_loaded('bcmath'),
            'Ctype' => extension_loaded('ctype'),
            'Fileinfo' => extension_loaded('fileinfo'),
            'JSON' => extension_loaded('json'),
            'Mbstring' => extension_loaded('mbstring'),
            'OpenSSL' => extension_loaded('openssl'),
            'PDO' => extension_loaded('pdo'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'Tokenizer' => extension_loaded('tokenizer'),
            'XML' => extension_loaded('xml'),
            'cURL' => extension_loaded('curl'),
            'zip' => extension_loaded('zip'),
        ];
        $allPassed = !in_array(false, $requirements);
        return view('install.requirements', compact('requirements', 'allPassed'));
    }

    public function permissions()
    {
        $permissions = [
            'storage/app' => is_writable(storage_path('app')),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            '.env file' => is_writable(base_path('.env')),
        ];
        $allPassed = !in_array(false, $permissions);
        return view('install.permissions', compact('permissions', 'allPassed'));
    }

    public function mode()
    {
        return view('install.mode');
    }

    public function saveMode(Request $request)
    {
        $request->validate(['install_mode' => 'required|in:business,demo']);
        session(['install_mode' => $request->input('install_mode')]);
        return redirect()->route('install.database');
    }


    public function database()
    {
        $mode = session('install_mode', 'demo');
        return view('install.database', compact('mode'));
    }

    public function saveDatabase(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        $host = $request->input('db_host');
        $port = $request->input('db_port');
        $dbName = $request->input('db_name');
        $user = $request->input('db_user');
        $pass = $request->input('db_password', '');
        $mode = session('install_mode', 'demo');

        // 1. Connect to MySQL
        try {
            $pdo = new \PDO("mysql:host={$host};port={$port}", $user, $pass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Connection failed: ' . $e->getMessage()]);
        }

        // 2. Create DB
        try {
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$dbName}`");
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Database creation failed: ' . $e->getMessage()]);
        }

        // 3. Create tables one by one
        try {
            $this->createTables($pdo);
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Table creation failed: ' . $e->getMessage()]);
        }

        // 4. Insert default data
        try {
            $this->insertDefaults($pdo);
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Default data failed: ' . $e->getMessage()]);
        }

        // 5. Demo mode: insert demo data
        if ($mode === 'demo') {
            try {
                $this->insertDemoData($pdo);
            } catch (\Exception $e) {
                return back()->withErrors(['db_error' => 'Demo data failed: ' . $e->getMessage()]);
            }
        }


        // 6. Save to .env
        $this->setEnv('DB_CONNECTION', 'mysql');
        $this->setEnv('DB_HOST', $host);
        $this->setEnv('DB_PORT', $port);
        $this->setEnv('DB_DATABASE', $dbName);
        $this->setEnv('DB_USERNAME', $user);
        $this->setEnv('DB_PASSWORD', $pass);
        $this->setEnv('SESSION_DRIVER', 'database');
        $this->setEnv('CACHE_STORE', 'database');
        $this->setEnv('APP_MODE', $mode);

        if ($mode === 'demo') {
            $this->setEnv('APP_NAME', '"XapiVerse Demo"');
            $this->markInstalled($mode);
            return redirect('/admin/login');
        }

        return redirect()->route('install.account');
    }

    public function account()
    {
        if (session('install_mode') !== 'business') {
            return redirect()->route('install.mode');
        }
        return view('install.account');
    }

    public function saveAccount(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
        ]);

        try {
            $env = $this->readEnv();
            $pdo = new \PDO(
                "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']}",
                $env['DB_USERNAME'], $env['DB_PASSWORD'] ?? ''
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $hash = password_hash($request->input('admin_password'), PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,email_verified_at,password,role,is_active,created_at,updated_at) VALUES (?,?,NOW(),?,'admin',1,NOW(),NOW())");
            $stmt->execute([$request->input('admin_name'), $request->input('admin_email'), $hash]);

            $pdo->exec("UPDATE settings SET value='" . addslashes($request->input('site_name')) . "' WHERE `key`='site_name'");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed: ' . $e->getMessage()]);
        }

        $this->setEnv('APP_NAME', '"' . $request->input('site_name') . '"');
        $this->markInstalled('business');
        return redirect('/admin/login');
    }

    public function downloadSql()
    {
        return response()->download(base_path('database/sql/tables.sql'), 'xapiverse.sql');
    }


    private function createTables(\PDO $pdo): void
    {
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

        // Drop all existing tables
        $tables = ['api_request_logs','api_key_import_batches','transactions','user_api_keys','api_source_keys','credit_packages','api_services','settings','sessions','cache','cache_locks','jobs','job_batches','failed_jobs','password_reset_tokens','migrations','users'];
        foreach ($tables as $t) {
            $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
        }

        $pdo->exec("CREATE TABLE `users` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
            `total_credits_purchased` bigint NOT NULL DEFAULT 0,
            `total_credits_used` bigint NOT NULL DEFAULT 0,
            `last_login_at` timestamp NULL DEFAULT NULL,
            `last_login_ip` varchar(255) DEFAULT NULL,
            `remember_token` varchar(100) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `password_reset_tokens` (
            `email` varchar(255) NOT NULL,
            `token` varchar(255) NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `sessions` (
            `id` varchar(255) NOT NULL,
            `user_id` bigint unsigned DEFAULT NULL,
            `ip_address` varchar(45) DEFAULT NULL,
            `user_agent` text DEFAULT NULL,
            `payload` longtext NOT NULL,
            `last_activity` int NOT NULL,
            PRIMARY KEY (`id`),
            KEY (`user_id`),
            KEY (`last_activity`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");


        $pdo->exec("CREATE TABLE `cache` (
            `key` varchar(255) NOT NULL,
            `value` mediumtext NOT NULL,
            `expiration` int NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `cache_locks` (
            `key` varchar(255) NOT NULL,
            `owner` varchar(255) NOT NULL,
            `expiration` int NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `jobs` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `queue` varchar(255) NOT NULL,
            `payload` longtext NOT NULL,
            `attempts` tinyint unsigned NOT NULL,
            `reserved_at` int unsigned DEFAULT NULL,
            `available_at` int unsigned NOT NULL,
            `created_at` int unsigned NOT NULL,
            PRIMARY KEY (`id`),
            KEY (`queue`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `failed_jobs` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `uuid` varchar(255) NOT NULL,
            `connection` text NOT NULL,
            `queue` text NOT NULL,
            `payload` longtext NOT NULL,
            `exception` longtext NOT NULL,
            `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `api_services` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `description` text DEFAULT NULL,
            `icon` varchar(255) DEFAULT NULL,
            `base_url` varchar(255) NOT NULL,
            `version` varchar(255) NOT NULL DEFAULT 'v1',
            `rotation_strategy` enum('round_robin','priority','least_used','weighted','fill_rotate') NOT NULL DEFAULT 'round_robin',
            `credits_per_request` int NOT NULL DEFAULT 1,
            `rate_limit_per_minute` int NOT NULL DEFAULT 60,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `is_public` tinyint(1) NOT NULL DEFAULT 1,
            `endpoints` json DEFAULT NULL,
            `headers` json DEFAULT NULL,
            `documentation` text DEFAULT NULL,
            `sort_order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");


        $pdo->exec("CREATE TABLE `api_source_keys` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `api_service_id` bigint unsigned NOT NULL,
            `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free',
            `api_key` text NOT NULL,
            `base_url_override` varchar(255) DEFAULT NULL,
            `headers_override` json DEFAULT NULL,
            `label` varchar(255) DEFAULT NULL,
            `daily_limit` bigint unsigned DEFAULT NULL,
            `monthly_limit` bigint unsigned DEFAULT NULL,
            `total_limit` bigint unsigned DEFAULT NULL,
            `used_today` bigint unsigned NOT NULL DEFAULT 0,
            `used_this_month` bigint unsigned NOT NULL DEFAULT 0,
            `used_total` bigint unsigned NOT NULL DEFAULT 0,
            `priority` int unsigned NOT NULL DEFAULT 5,
            `weight` int unsigned NOT NULL DEFAULT 50,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `is_exhausted` tinyint(1) NOT NULL DEFAULT 0,
            `last_used_at` timestamp NULL DEFAULT NULL,
            `cooldown_until` timestamp NULL DEFAULT NULL,
            `last_error` varchar(255) DEFAULT NULL,
            `error_count` int unsigned NOT NULL DEFAULT 0,
            `success_count` int unsigned NOT NULL DEFAULT 0,
            `avg_response_time_ms` float NOT NULL DEFAULT 0,
            `notes` text DEFAULT NULL,
            `import_batch_id` varchar(255) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY (`api_service_id`,`is_active`,`is_exhausted`),
            KEY (`api_service_id`,`priority`,`last_used_at`),
            FOREIGN KEY (`api_service_id`) REFERENCES `api_services`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `user_api_keys` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint unsigned NOT NULL,
            `name` varchar(255) NOT NULL,
            `api_key` varchar(64) NOT NULL,
            `prefix` varchar(10) NOT NULL,
            `credits_balance` bigint NOT NULL DEFAULT 0,
            `total_used` bigint NOT NULL DEFAULT 0,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `allowed_services` json DEFAULT NULL,
            `rate_limit_per_minute` int NOT NULL DEFAULT 60,
            `last_used_at` timestamp NULL DEFAULT NULL,
            `expires_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`api_key`),
            KEY (`user_id`,`is_active`),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");


        $pdo->exec("CREATE TABLE `credit_packages` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `price` decimal(10,2) NOT NULL,
            `credits` bigint NOT NULL,
            `description` text DEFAULT NULL,
            `is_popular` tinyint(1) NOT NULL DEFAULT 0,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `sort_order` int NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `transactions` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint unsigned NOT NULL,
            `transaction_id` varchar(255) NOT NULL,
            `type` enum('purchase','bonus','refund','admin_credit','admin_debit') NOT NULL,
            `credits` bigint NOT NULL,
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
            UNIQUE KEY (`transaction_id`),
            KEY (`user_id`,`status`),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `api_request_logs` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint unsigned DEFAULT NULL,
            `user_api_key_id` bigint unsigned DEFAULT NULL,
            `api_service_id` bigint unsigned DEFAULT NULL,
            `api_source_key_id` bigint unsigned DEFAULT NULL,
            `endpoint` varchar(255) NOT NULL,
            `method` varchar(10) NOT NULL DEFAULT 'POST',
            `status` enum('success','failed','rate_limited','no_credits') NOT NULL,
            `http_status_code` int DEFAULT NULL,
            `response_time_ms` float DEFAULT NULL,
            `credits_charged` int NOT NULL DEFAULT 0,
            `ip_address` varchar(45) DEFAULT NULL,
            `error_message` text DEFAULT NULL,
            `request_params` json DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY (`user_id`,`created_at`),
            KEY (`api_service_id`,`created_at`),
            KEY (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");


        $pdo->exec("CREATE TABLE `api_key_import_batches` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `batch_id` varchar(255) NOT NULL,
            `api_service_id` bigint unsigned NOT NULL,
            `imported_by` bigint unsigned NOT NULL,
            `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free',
            `total_imported` int unsigned NOT NULL DEFAULT 0,
            `total_failed` int unsigned NOT NULL DEFAULT 0,
            `daily_limit_per_key` bigint unsigned DEFAULT NULL,
            `monthly_limit_per_key` bigint unsigned DEFAULT NULL,
            `priority` int unsigned NOT NULL DEFAULT 5,
            `status` enum('processing','completed','failed') NOT NULL DEFAULT 'processing',
            `notes` text DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`batch_id`),
            FOREIGN KEY (`api_service_id`) REFERENCES `api_services`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`imported_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `settings` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `group` varchar(255) NOT NULL DEFAULT 'general',
            `key` varchar(255) NOT NULL,
            `value` text DEFAULT NULL,
            `type` varchar(255) NOT NULL DEFAULT 'string',
            `description` text DEFAULT NULL,
            `is_public` tinyint(1) NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `migrations` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `migration` varchar(255) NOT NULL,
            `batch` int NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    }


    private function insertDefaults(\PDO $pdo): void
    {
        $settings = [
            ['general','site_name','XapiVerse','string','Platform name'],
            ['general','site_description','Fast & Affordable APIs for Developers','string','Platform description'],
            ['general','site_version','1.0.0','string','Platform version'],
            ['general','maintenance_mode','0','boolean','Enable maintenance mode'],
            ['api','default_rate_limit','60','integer','Default rate limit per minute'],
            ['api','free_credits_on_signup','1000','integer','Free credits for new developers'],
            ['api','max_keys_per_user','10','integer','Max API keys per developer'],
            ['api','key_auto_disable_errors','5','integer','Auto-disable after N errors'],
            ['api','key_cooldown_seconds','60','integer','Cooldown after error'],
            ['rotation','default_strategy','round_robin','string','Default rotation strategy'],
            ['rotation','daily_reset_time','00:00','string','Daily reset time UTC'],
            ['rotation','retry_attempts','3','integer','Retry attempts before fail'],
            ['payment','currency','USD','string','Default currency'],
            ['payment','min_purchase_amount','1.00','string','Min purchase amount'],
        ];

        $stmt = $pdo->prepare("INSERT INTO settings (`group`,`key`,`value`,`type`,`description`,`is_public`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,0,NOW(),NOW())");
        foreach ($settings as $s) {
            $stmt->execute($s);
        }

        $packages = [
            ['Starter', 1.00, 25000, 'Perfect for testing', 0, 1],
            ['Developer', 5.00, 150000, 'For small projects', 1, 2],
            ['Business', 20.00, 750000, 'For production apps', 0, 3],
            ['Enterprise', 100.00, 5000000, 'High-volume usage', 0, 4],
        ];

        $stmt = $pdo->prepare("INSERT INTO credit_packages (name,price,credits,description,is_popular,sort_order,is_active,created_at,updated_at) VALUES (?,?,?,?,?,?,1,NOW(),NOW())");
        foreach ($packages as $p) {
            $stmt->execute($p);
        }
    }


    private function insertDemoData(\PDO $pdo): void
    {
        $hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 12]);

        // Demo users
        $stmt = $pdo->prepare("INSERT INTO users (name,email,email_verified_at,password,role,is_active,created_at,updated_at) VALUES (?,?,NOW(),?,?,1,NOW(),NOW())");
        $stmt->execute(['Admin User', 'admin@xapiverse.com', $hash, 'admin']);
        $stmt->execute(['Demo Developer', 'dev@xapiverse.com', $hash, 'developer']);
        $stmt->execute(['Demo User', 'user@xapiverse.com', $hash, 'user']);

        // Demo API key for developer (user_id=2)
        $pdo->exec("INSERT INTO user_api_keys (user_id,name,api_key,prefix,credits_balance,total_used,is_active,rate_limit_per_minute,created_at,updated_at) VALUES (2,'Demo Key','xv_live_demo_key_123456789abcdef012345678','xv_live_',50000,1250,1,60,NOW(),NOW())");

        // Demo services
        $pdo->exec("INSERT INTO api_services (name,slug,description,base_url,rotation_strategy,credits_per_request,rate_limit_per_minute,is_active,is_public,sort_order,created_at,updated_at) VALUES ('TeraBox API','terabox','Get download links and HLS streaming from TeraBox.','https://api.example.com/terabox','round_robin',1,60,1,1,1,NOW(),NOW())");
        $pdo->exec("INSERT INTO api_services (name,slug,description,base_url,rotation_strategy,credits_per_request,rate_limit_per_minute,is_active,is_public,sort_order,created_at,updated_at) VALUES ('X Twitter API','twitter','Extract media and info from tweets.','https://api.example.com/twitter','least_used',1,30,1,1,2,NOW(),NOW())");
        $pdo->exec("INSERT INTO api_services (name,slug,description,base_url,rotation_strategy,credits_per_request,rate_limit_per_minute,is_active,is_public,sort_order,created_at,updated_at) VALUES ('Instagram API','instagram','Download reels and posts.','https://api.example.com/instagram','priority',2,20,0,1,3,NOW(),NOW())");

        // Demo source keys for TeraBox (service_id=1)
        $stmt = $pdo->prepare("INSERT INTO api_source_keys (api_service_id,key_type,api_key,daily_limit,used_today,used_total,priority,weight,is_active,is_exhausted,created_at,updated_at) VALUES (1,?,?,?,?,?,?,?,1,0,NOW(),NOW())");
        $stmt->execute(['master','demo_tb_master_001',10000,4320,45000,1,80]);
        $stmt->execute(['master','demo_tb_master_002',10000,6100,52000,1,80]);
        $stmt->execute(['free','demo_tb_free_001',100,87,5400,5,50]);
        $stmt->execute(['free','demo_tb_free_002',100,45,4200,5,50]);
        $stmt->execute(['free','demo_tb_free_003',100,67,3800,5,50]);
        $stmt->execute(['free','demo_tb_free_004',100,12,2300,5,50]);
        $stmt->execute(['free','demo_tb_free_005',100,91,5800,5,50]);

        // Demo source keys for Twitter (service_id=2)
        $stmt2 = $pdo->prepare("INSERT INTO api_source_keys (api_service_id,key_type,api_key,daily_limit,used_today,used_total,priority,weight,is_active,is_exhausted,created_at,updated_at) VALUES (2,?,?,?,?,?,?,?,1,0,NOW(),NOW())");
        $stmt2->execute(['master','demo_tw_master_001',5000,2100,18000,1,80]);
        $stmt2->execute(['free','demo_tw_free_001',50,38,1200,5,50]);
        $stmt2->execute(['free','demo_tw_free_002',50,22,800,5,50]);

        // Set install mode
        $pdo->exec("INSERT INTO settings (`group`,`key`,`value`,`type`,`description`,`is_public`,`created_at`,`updated_at`) VALUES ('general','install_mode','demo','string','Install mode',0,NOW(),NOW())");
    }


    private function markInstalled(string $mode): void
    {
        File::put(storage_path('installed/installed.lock'), json_encode([
            'installed_at' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
            'mode' => $mode,
        ]));
    }

    private function readEnv(): array
    {
        $env = [];
        foreach (explode("\n", File::get(base_path('.env'))) as $line) {
            $line = trim($line);
            if ($line && !str_starts_with($line, '#') && str_contains($line, '=')) {
                [$k, $v] = explode('=', $line, 2);
                $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
            }
        }
        return $env;
    }

    private function setEnv(string $key, string $value): void
    {
        $path = base_path('.env');
        $content = File::get($path);
        if (str_contains($content, $key . '=')) {
            $content = preg_replace('/^' . preg_quote($key, '/') . '=.*/m', $key . '=' . $value, $content);
        } else {
            $content .= "\n" . $key . '=' . $value;
        }
        File::put($path, $content);
    }
}
