<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    /**
     * Step 1: Check system requirements
     */
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
            'GD' => extension_loaded('gd'),
            'DOM' => extension_loaded('dom'),
        ];

        $allPassed = !in_array(false, $requirements);

        return view('install.requirements', compact('requirements', 'allPassed'));
    }

    /**
     * Step 2: Check folder permissions
     */
    public function permissions()
    {
        $permissions = [
            'storage/app' => $this->checkPermission(storage_path('app')),
            'storage/framework' => $this->checkPermission(storage_path('framework')),
            'storage/logs' => $this->checkPermission(storage_path('logs')),
            'storage/framework/cache' => $this->checkPermission(storage_path('framework/cache')),
            'storage/framework/sessions' => $this->checkPermission(storage_path('framework/sessions')),
            'storage/framework/views' => $this->checkPermission(storage_path('framework/views')),
            'bootstrap/cache' => $this->checkPermission(base_path('bootstrap/cache')),
            '.env' => $this->checkPermission(base_path('.env')),
        ];

        $allPassed = !in_array(false, $permissions);

        return view('install.permissions', compact('permissions', 'allPassed'));
    }

    /**
     * Step 3: Choose mode (BEFORE database)
     */
    public function mode()
    {
        return view('install.mode');
    }

    /**
     * Step 3: Save mode to session
     */
    public function saveMode(Request $request)
    {
        $request->validate([
            'install_mode' => 'required|in:business,demo',
        ]);

        session(['install_mode' => $request->input('install_mode')]);

        return redirect()->route('install.database');
    }

    /**
     * Step 4: Database setup form
     */
    public function database()
    {
        $mode = session('install_mode', 'demo');
        return view('install.database', compact('mode'));
    }

    /**
     * Step 4: Connect DB, import tables, import demo if demo mode
     */
    public function saveDatabase(Request $request)
    {
        // Increase timeout for SQL import (XAMPP default is 30 seconds)
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
        $username = $request->input('db_user');
        $password = $request->input('db_password', '');
        $mode = session('install_mode', 'demo');

        // 1. Connect to MySQL server
        try {
            $pdo = new \PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\PDOException $e) {
            return back()->withErrors(['db_error' => 'Cannot connect to MySQL: ' . $e->getMessage()]);
        }

        // 2. Create database if not exists
        try {
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$dbName}`");
        } catch (\PDOException $e) {
            return back()->withErrors(['db_error' => 'Cannot create/select database: ' . $e->getMessage()]);
        }

        // 3. Import tables SQL (execute statement by statement)
        try {
            $sqlFile = base_path('database/sql/tables.sql');
            $sql = File::get($sqlFile);
            $this->executeSqlStatements($pdo, $sql);
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Table import failed: ' . $e->getMessage()]);
        }

        // 4. If DEMO mode → import demo data
        if ($mode === 'demo') {
            try {
                $demoFile = base_path('database/sql/demo_data.sql');
                $demoSql = File::get($demoFile);
                // Replace password placeholder with real bcrypt hash
                $hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 12]);
                $demoSql = str_replace('$2y$12$YourHashWillBeReplacedByInstaller', $hash, $demoSql);
                $this->executeSqlStatements($pdo, $demoSql);
            } catch (\Exception $e) {
                return back()->withErrors(['db_error' => 'Demo data import failed: ' . $e->getMessage()]);
            }
        }

        // 5. Save DB credentials to .env
        $this->setEnvValue('DB_CONNECTION', 'mysql');
        $this->setEnvValue('DB_HOST', $host);
        $this->setEnvValue('DB_PORT', $port);
        $this->setEnvValue('DB_DATABASE', $dbName);
        $this->setEnvValue('DB_USERNAME', $username);
        $this->setEnvValue('DB_PASSWORD', $password);
        $this->setEnvValue('SESSION_DRIVER', 'database');
        $this->setEnvValue('CACHE_STORE', 'database');
        $this->setEnvValue('APP_MODE', $mode);

        // 6. If DEMO → installation done, mark installed, redirect to login
        if ($mode === 'demo') {
            $this->setEnvValue('APP_NAME', '"XapiVerse Demo"');
            $this->markInstalled($mode);
            return redirect('/admin/login');
        }

        // 7. If BUSINESS → go to account creation
        return redirect()->route('install.account');
    }

    /**
     * Download SQL file for manual import
     */
    public function downloadSql()
    {
        return response()->download(base_path('database/sql/tables.sql'), 'xapiverse_tables.sql');
    }

    /**
     * Step 5 (Business only): Create super admin account
     */
    public function account()
    {
        if (session('install_mode') !== 'business') {
            return redirect()->route('install.mode');
        }
        return view('install.account');
    }

    /**
     * Step 5: Save super admin and finish
     */
    public function saveAccount(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
        ]);

        // Connect to DB using saved .env values
        try {
            $pdo = $this->getDbConnection();

            // Insert super admin
            $hash = password_hash($request->input('admin_password'), PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `created_at`, `updated_at`) VALUES (?, ?, NOW(), ?, 'admin', 1, NOW(), NOW())");
            $stmt->execute([
                $request->input('admin_name'),
                $request->input('admin_email'),
                $hash,
            ]);

            // Update site name setting
            $pdo->prepare("UPDATE `settings` SET `value` = ? WHERE `key` = 'site_name'")->execute([$request->input('site_name')]);

        } catch (\PDOException $e) {
            return back()->withErrors(['error' => 'Account creation failed: ' . $e->getMessage()]);
        }

        // Update .env
        $this->setEnvValue('APP_NAME', '"' . $request->input('site_name') . '"');

        // Mark installed and redirect to admin login
        $this->markInstalled('business');

        return redirect('/admin/login');
    }

    /**
     * Mark the application as installed
     */
    private function markInstalled(string $mode): void
    {
        File::put(storage_path('installed/installed.lock'), json_encode([
            'installed_at' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'mode' => $mode,
        ]));
    }

    /**
     * Get PDO connection from .env values
     */
    private function getDbConnection(): \PDO
    {
        $env = $this->readEnv();
        return new \PDO(
            "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']}",
            $env['DB_USERNAME'],
            $env['DB_PASSWORD'] ?? '',
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_EMULATE_PREPARES => true]
        );
    }

    /**
     * Read .env file into array
     */
    private function readEnv(): array
    {
        $env = [];
        foreach (explode("\n", File::get(base_path('.env'))) as $line) {
            $line = trim($line);
            if ($line && !str_starts_with($line, '#') && str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $env[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
            }
        }
        return $env;
    }

    /**
     * Execute SQL file by splitting into individual statements
     * This avoids ERR_CONNECTION_RESET on XAMPP/shared hosting
     */
    private function executeSqlStatements(\PDO $pdo, string $sql): void
    {
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        // Split by semicolons (but not inside quotes)
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';

        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];

            if ($inString) {
                $current .= $char;
                if ($char === $stringChar && ($i === 0 || $sql[$i - 1] !== '\\')) {
                    $inString = false;
                }
            } else {
                if ($char === '\'' || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                    $current .= $char;
                } elseif ($char === ';') {
                    $stmt = trim($current);
                    if ($stmt !== '') {
                        $statements[] = $stmt;
                    }
                    $current = '';
                } else {
                    $current .= $char;
                }
            }
        }

        // Don't forget last statement without semicolon
        $stmt = trim($current);
        if ($stmt !== '') {
            $statements[] = $stmt;
        }

        // Execute each statement
        foreach ($statements as $statement) {
            if (empty($statement)) continue;
            $pdo->exec($statement);
        }
    }

    private function checkPermission(string $path): bool
    {
        return File::isWritable($path);
    }

    private function setEnvValue(string $key, string $value): void
    {
        $envFile = base_path('.env');
        $content = File::get($envFile);

        if (str_contains($content, $key . '=')) {
            $content = preg_replace(
                '/^' . preg_quote($key, '/') . '=.*/m',
                $key . '=' . $value,
                $content
            );
        } else {
            $content .= "\n" . $key . '=' . $value;
        }

        File::put($envFile, $content);
    }
}
