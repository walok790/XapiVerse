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

    /**
     * AJAX endpoint: runs database setup step by step
     * Called via fetch() from the browser - each step is a separate request
     */
    public function runStep(Request $request)
    {
        $step = (int) $request->input('step', 0);
        $host = $request->input('db_host', '127.0.0.1');
        $port = $request->input('db_port', '3306');
        $dbName = $request->input('db_name', 'xapiverse_db');
        $user = $request->input('db_user', 'root');
        $pass = $request->input('db_password', '');
        $mode = session('install_mode', 'demo');

        try {
            $pdo = new \PDO("mysql:host={$host};port={$port};dbname={$dbName}", $user, $pass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'DB connection failed: ' . $e->getMessage()]);
        }

        $tables = $this->getTableQueries();
        $totalSteps = count($tables) + 2; // tables + defaults + (demo if needed)

        try {
            if ($step === 0) {
                // Drop all old tables
                $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
                $drops = ['api_request_logs','api_key_import_batches','transactions','user_api_keys','api_source_keys','credit_packages','api_services','settings','sessions','cache','cache_locks','jobs','failed_jobs','password_reset_tokens','migrations','users'];
                foreach ($drops as $t) {
                    $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
                }
                $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
                return response()->json(['success' => true, 'message' => 'Cleaned old tables', 'next' => 1, 'total' => $totalSteps]);
            }

            if ($step <= count($tables)) {
                // Create one table per step
                $idx = $step - 1;
                $tableNames = array_keys($tables);
                $pdo->exec($tables[$tableNames[$idx]]);
                return response()->json(['success' => true, 'message' => 'Created: ' . $tableNames[$idx], 'next' => $step + 1, 'total' => $totalSteps]);
            }

            if ($step === count($tables) + 1) {
                // Insert defaults
                $this->insertDefaults($pdo);
                if ($mode === 'demo') {
                    $this->insertDemoData($pdo);
                }

                // Save .env
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
                    return response()->json(['success' => true, 'message' => 'Installation complete!', 'done' => true, 'redirect' => '/admin/login']);
                }

                return response()->json(['success' => true, 'message' => 'Tables ready!', 'done' => true, 'redirect' => '/install/account']);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Step ' . $step . ' failed: ' . $e->getMessage()]);
        }

        return response()->json(['success' => false, 'error' => 'Invalid step']);
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
            $pdo = new \PDO("mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']}", $env['DB_USERNAME'], $env['DB_PASSWORD'] ?? '');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $hash = password_hash($request->input('admin_password'), PASSWORD_BCRYPT, ['cost' => 10]);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,email_verified_at,password,role,is_active,created_at,updated_at) VALUES (?,?,NOW(),?,'admin',1,NOW(),NOW())");
            $stmt->execute([$request->input('admin_name'), $request->input('admin_email'), $hash]);
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

    // ─── TABLE DEFINITIONS (each is a separate query) ──────────

    private function getTableQueries(): array
    {
        return [
            'users' => "CREATE TABLE `users` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `email` varchar(255) NOT NULL, `email_verified_at` timestamp NULL, `password` varchar(255) NOT NULL, `role` enum('admin','developer','user') NOT NULL DEFAULT 'user', `is_active` tinyint(1) NOT NULL DEFAULT 1, `avatar` varchar(255) DEFAULT NULL, `bio` text, `company` varchar(255) DEFAULT NULL, `website` varchar(255) DEFAULT NULL, `total_credits_purchased` bigint NOT NULL DEFAULT 0, `total_credits_used` bigint NOT NULL DEFAULT 0, `last_login_at` timestamp NULL, `last_login_ip` varchar(255) DEFAULT NULL, `remember_token` varchar(100) DEFAULT NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, `deleted_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'password_reset_tokens' => "CREATE TABLE `password_reset_tokens` (`email` varchar(255) NOT NULL, `token` varchar(255) NOT NULL, `created_at` timestamp NULL, PRIMARY KEY (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'sessions' => "CREATE TABLE `sessions` (`id` varchar(255) NOT NULL, `user_id` bigint unsigned DEFAULT NULL, `ip_address` varchar(45) DEFAULT NULL, `user_agent` text, `payload` longtext NOT NULL, `last_activity` int NOT NULL, PRIMARY KEY (`id`), KEY `sessions_user_id_index` (`user_id`), KEY `sessions_last_activity_index` (`last_activity`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'cache' => "CREATE TABLE `cache` (`key` varchar(255) NOT NULL, `value` mediumtext NOT NULL, `expiration` int NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'cache_locks' => "CREATE TABLE `cache_locks` (`key` varchar(255) NOT NULL, `owner` varchar(255) NOT NULL, `expiration` int NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'jobs' => "CREATE TABLE `jobs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `queue` varchar(255) NOT NULL, `payload` longtext NOT NULL, `attempts` tinyint unsigned NOT NULL, `reserved_at` int unsigned DEFAULT NULL, `available_at` int unsigned NOT NULL, `created_at` int unsigned NOT NULL, PRIMARY KEY (`id`), KEY `jobs_queue_index` (`queue`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'failed_jobs' => "CREATE TABLE `failed_jobs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `uuid` varchar(255) NOT NULL, `connection` text NOT NULL, `queue` text NOT NULL, `payload` longtext NOT NULL, `exception` longtext NOT NULL, `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY (`uuid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'api_services' => "CREATE TABLE `api_services` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `slug` varchar(255) NOT NULL, `description` text, `icon` varchar(255) DEFAULT NULL, `base_url` varchar(255) NOT NULL, `version` varchar(255) NOT NULL DEFAULT 'v1', `rotation_strategy` enum('round_robin','priority','least_used','weighted','fill_rotate') NOT NULL DEFAULT 'round_robin', `credits_per_request` int NOT NULL DEFAULT 1, `rate_limit_per_minute` int NOT NULL DEFAULT 60, `is_active` tinyint(1) NOT NULL DEFAULT 1, `is_public` tinyint(1) NOT NULL DEFAULT 1, `endpoints` json DEFAULT NULL, `headers` json DEFAULT NULL, `documentation` text, `sort_order` int NOT NULL DEFAULT 0, `created_at` timestamp NULL, `updated_at` timestamp NULL, `deleted_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY (`slug`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'api_source_keys' => "CREATE TABLE `api_source_keys` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `api_service_id` bigint unsigned NOT NULL, `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free', `api_key` text NOT NULL, `base_url_override` varchar(255) DEFAULT NULL, `headers_override` json DEFAULT NULL, `label` varchar(255) DEFAULT NULL, `daily_limit` bigint unsigned DEFAULT NULL, `monthly_limit` bigint unsigned DEFAULT NULL, `total_limit` bigint unsigned DEFAULT NULL, `used_today` bigint unsigned NOT NULL DEFAULT 0, `used_this_month` bigint unsigned NOT NULL DEFAULT 0, `used_total` bigint unsigned NOT NULL DEFAULT 0, `priority` int unsigned NOT NULL DEFAULT 5, `weight` int unsigned NOT NULL DEFAULT 50, `is_active` tinyint(1) NOT NULL DEFAULT 1, `is_exhausted` tinyint(1) NOT NULL DEFAULT 0, `last_used_at` timestamp NULL, `cooldown_until` timestamp NULL, `last_error` varchar(255) DEFAULT NULL, `error_count` int unsigned NOT NULL DEFAULT 0, `success_count` int unsigned NOT NULL DEFAULT 0, `avg_response_time_ms` float NOT NULL DEFAULT 0, `notes` text, `import_batch_id` varchar(255) DEFAULT NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), KEY `ask_svc` (`api_service_id`,`is_active`,`is_exhausted`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'user_api_keys' => "CREATE TABLE `user_api_keys` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `user_id` bigint unsigned NOT NULL, `name` varchar(255) NOT NULL, `api_key` varchar(64) NOT NULL, `prefix` varchar(10) NOT NULL, `credits_balance` bigint NOT NULL DEFAULT 0, `total_used` bigint NOT NULL DEFAULT 0, `is_active` tinyint(1) NOT NULL DEFAULT 1, `allowed_services` json DEFAULT NULL, `rate_limit_per_minute` int NOT NULL DEFAULT 60, `last_used_at` timestamp NULL, `expires_at` timestamp NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, `deleted_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY (`api_key`), KEY `uak_user` (`user_id`,`is_active`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'credit_packages' => "CREATE TABLE `credit_packages` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `price` decimal(10,2) NOT NULL, `credits` bigint NOT NULL, `description` text, `is_popular` tinyint(1) NOT NULL DEFAULT 0, `is_active` tinyint(1) NOT NULL DEFAULT 1, `sort_order` int NOT NULL DEFAULT 0, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'transactions' => "CREATE TABLE `transactions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `user_id` bigint unsigned NOT NULL, `transaction_id` varchar(255) NOT NULL, `type` enum('purchase','bonus','refund','admin_credit','admin_debit') NOT NULL, `credits` bigint NOT NULL, `amount` decimal(10,2) NOT NULL DEFAULT 0.00, `currency` varchar(3) NOT NULL DEFAULT 'USD', `payment_method` varchar(255) DEFAULT NULL, `payment_id` varchar(255) DEFAULT NULL, `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending', `notes` text, `meta` json DEFAULT NULL, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY (`transaction_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'api_request_logs' => "CREATE TABLE `api_request_logs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `user_id` bigint unsigned DEFAULT NULL, `user_api_key_id` bigint unsigned DEFAULT NULL, `api_service_id` bigint unsigned DEFAULT NULL, `api_source_key_id` bigint unsigned DEFAULT NULL, `endpoint` varchar(255) NOT NULL, `method` varchar(10) NOT NULL DEFAULT 'POST', `status` enum('success','failed','rate_limited','no_credits') NOT NULL, `http_status_code` int DEFAULT NULL, `response_time_ms` float DEFAULT NULL, `credits_charged` int NOT NULL DEFAULT 0, `ip_address` varchar(45) DEFAULT NULL, `error_message` text, `request_params` json DEFAULT NULL, `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), KEY `arl_date` (`created_at`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'api_key_import_batches' => "CREATE TABLE `api_key_import_batches` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `batch_id` varchar(255) NOT NULL, `api_service_id` bigint unsigned NOT NULL, `imported_by` bigint unsigned NOT NULL, `key_type` enum('master','free','custom') NOT NULL DEFAULT 'free', `total_imported` int unsigned NOT NULL DEFAULT 0, `total_failed` int unsigned NOT NULL DEFAULT 0, `daily_limit_per_key` bigint unsigned DEFAULT NULL, `monthly_limit_per_key` bigint unsigned DEFAULT NULL, `priority` int unsigned NOT NULL DEFAULT 5, `status` enum('processing','completed','failed') NOT NULL DEFAULT 'processing', `notes` text, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY (`batch_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'settings' => "CREATE TABLE `settings` (`id` bigint unsigned NOT NULL AUTO_INCREMENT, `group` varchar(255) NOT NULL DEFAULT 'general', `key` varchar(255) NOT NULL, `value` text, `type` varchar(255) NOT NULL DEFAULT 'string', `description` text, `is_public` tinyint(1) NOT NULL DEFAULT 0, `created_at` timestamp NULL, `updated_at` timestamp NULL, PRIMARY KEY (`id`), UNIQUE KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            'migrations' => "CREATE TABLE `migrations` (`id` int unsigned NOT NULL AUTO_INCREMENT, `migration` varchar(255) NOT NULL, `batch` int NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];
    }

    private function insertDefaults(\PDO $pdo): void
    {
        $stmt = $pdo->prepare("INSERT INTO settings (`group`,`key`,`value`,`type`,`description`,`is_public`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,0,NOW(),NOW())");
        $stmt->execute(['general','site_name','XapiVerse','string','Platform name']);
        $stmt->execute(['general','site_description','Fast & Affordable APIs','string','Platform description']);
        $stmt->execute(['general','site_version','1.0.0','string','Platform version']);
        $stmt->execute(['general','maintenance_mode','0','boolean','Maintenance mode']);
        $stmt->execute(['api','default_rate_limit','60','integer','Rate limit/min']);
        $stmt->execute(['api','free_credits_on_signup','1000','integer','Free credits']);
        $stmt->execute(['api','max_keys_per_user','10','integer','Max keys']);
        $stmt->execute(['rotation','default_strategy','round_robin','string','Default strategy']);
        $stmt->execute(['rotation','retry_attempts','3','integer','Retry attempts']);
        $stmt->execute(['payment','currency','USD','string','Currency']);

        $pkg = $pdo->prepare("INSERT INTO credit_packages (name,price,credits,description,is_popular,sort_order,is_active,created_at,updated_at) VALUES (?,?,?,?,?,?,1,NOW(),NOW())");
        $pkg->execute(['Starter',1.00,25000,'For testing',0,1]);
        $pkg->execute(['Developer',5.00,150000,'For projects',1,2]);
        $pkg->execute(['Business',20.00,750000,'Production',0,3]);
        $pkg->execute(['Enterprise',100.00,5000000,'High-volume',0,4]);
    }

    private function insertDemoData(\PDO $pdo): void
    {
        $hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 10]);
        $u = $pdo->prepare("INSERT INTO users (name,email,email_verified_at,password,role,is_active,created_at,updated_at) VALUES (?,?,NOW(),?,?,1,NOW(),NOW())");
        $u->execute(['Admin User','admin@xapiverse.com',$hash,'admin']);
        $u->execute(['Demo Developer','dev@xapiverse.com',$hash,'developer']);
        $u->execute(['Demo User','user@xapiverse.com',$hash,'user']);

        $pdo->exec("INSERT INTO user_api_keys (user_id,name,api_key,prefix,credits_balance,total_used,is_active,rate_limit_per_minute,created_at,updated_at) VALUES (2,'Demo Key','xv_live_demo_key_1234567890abcdef12345678','xv_live_',50000,1250,1,60,NOW(),NOW())");

        $pdo->exec("INSERT INTO api_services (name,slug,description,base_url,rotation_strategy,is_active,is_public,sort_order,created_at,updated_at) VALUES ('TeraBox API','terabox','Download links from TeraBox','https://api.example.com/terabox','round_robin',1,1,1,NOW(),NOW())");
        $pdo->exec("INSERT INTO api_services (name,slug,description,base_url,rotation_strategy,is_active,is_public,sort_order,created_at,updated_at) VALUES ('Twitter API','twitter','Extract media from tweets','https://api.example.com/twitter','least_used',1,1,2,NOW(),NOW())");

        $k = $pdo->prepare("INSERT INTO api_source_keys (api_service_id,key_type,api_key,daily_limit,used_today,used_total,priority,weight,is_active,is_exhausted,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,1,0,NOW(),NOW())");
        $k->execute([1,'master','demo_tb_m1',10000,4320,45000,1,80]);
        $k->execute([1,'free','demo_tb_f1',100,87,5400,5,50]);
        $k->execute([1,'free','demo_tb_f2',100,45,4200,5,50]);
        $k->execute([2,'master','demo_tw_m1',5000,2100,18000,1,80]);
        $k->execute([2,'free','demo_tw_f1',50,38,1200,5,50]);

        $pdo->exec("INSERT INTO settings (`group`,`key`,`value`,`type`,`description`,`is_public`,`created_at`,`updated_at`) VALUES ('general','install_mode','demo','string','Install mode',0,NOW(),NOW())");
    }

    private function markInstalled(string $mode): void
    {
        File::put(storage_path('installed/installed.lock'), json_encode(['installed_at' => date('Y-m-d H:i:s'), 'version' => '1.0.0', 'mode' => $mode]));
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
