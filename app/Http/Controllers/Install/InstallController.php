<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Setting;

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
     * Step 3: Database configuration form
     */
    public function database()
    {
        return view('install.database');
    }

    /**
     * Step 3: Connect to database and import SQL tables
     */
    public function saveDatabase(Request $request)
    {
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
        $isManual = $request->boolean('manual_import');

        // Step 1: Test connection (without selecting database first)
        try {
            $pdo = new \PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\PDOException $e) {
            return back()->withErrors(['db_error' => 'Cannot connect to MySQL server: ' . $e->getMessage()]);
        }

        // Step 2: Create database if it doesn't exist
        try {
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (\PDOException $e) {
            return back()->withErrors(['db_error' => 'Cannot create database: ' . $e->getMessage()]);
        }

        // Step 3: Connect to the database
        try {
            $pdo->exec("USE `{$dbName}`");
        } catch (\PDOException $e) {
            return back()->withErrors(['db_error' => 'Cannot select database: ' . $e->getMessage()]);
        }

        // Step 4: Import tables (skip if manual import - tables should already exist)
        if (!$isManual) {
            try {
                $sqlFile = base_path('database/sql/tables.sql');
                if (!File::exists($sqlFile)) {
                    return back()->withErrors(['db_error' => 'SQL file not found: database/sql/tables.sql']);
                }

                $sql = File::get($sqlFile);

                // Execute multi-statement SQL properly
                // PDO::exec can handle multiple statements when using MySQL
                $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
                $pdo->exec($sql);
            } catch (\PDOException $e) {
                return back()->withErrors(['db_error' => 'SQL import failed: ' . $e->getMessage()]);
            }
        } else {
            // Verify tables exist for manual import
            try {
                $result = $pdo->query("SHOW TABLES LIKE 'users'");
                if ($result->rowCount() === 0) {
                    return back()->withErrors(['db_error' => 'Tables not found! Please import the SQL file first in phpMyAdmin.']);
                }
            } catch (\PDOException $e) {
                return back()->withErrors(['db_error' => 'Cannot verify tables: ' . $e->getMessage()]);
            }
        }

        // Step 5: Update .env with database settings
        $this->setEnvValue('DB_CONNECTION', 'mysql');
        $this->setEnvValue('DB_HOST', $host);
        $this->setEnvValue('DB_PORT', $port);
        $this->setEnvValue('DB_DATABASE', $dbName);
        $this->setEnvValue('DB_USERNAME', $username);
        $this->setEnvValue('DB_PASSWORD', $password);
        $this->setEnvValue('SESSION_DRIVER', 'database');

        return redirect()->route('install.mode');
    }

    /**
     * Download SQL file for manual import
     */
    public function downloadSql()
    {
        $sqlFile = base_path('database/sql/tables.sql');
        return response()->download($sqlFile, 'xapiverse_tables.sql');
    }

    /**
     * Step 4: Choose installation mode (Business or Demo)
     */
    public function mode()
    {
        return view('install.mode');
    }

    /**
     * Step 4: Save mode selection
     */
    public function saveMode(Request $request)
    {
        $request->validate([
            'install_mode' => 'required|in:business,demo',
        ]);

        $mode = $request->input('install_mode');
        session(['install_mode' => $mode]);

        if ($mode === 'demo') {
            return $this->setupDemoMode();
        }

        return redirect()->route('install.accounts');
    }

    /**
     * Setup demo mode - import demo SQL data
     */
    private function setupDemoMode()
    {
        try {
            // Re-establish DB connection with new config
            $this->refreshDbConnection();

            // Generate password hash for demo users
            $passwordHash = Hash::make('password');

            // Import demo data SQL
            $sqlFile = base_path('database/sql/demo_data.sql');
            if (File::exists($sqlFile)) {
                $sql = File::get($sqlFile);
                // Replace placeholder hash with real bcrypt hash
                $sql = str_replace('$2y$12$YourHashWillBeReplacedByInstaller', $passwordHash, $sql);
                DB::unprepared($sql);
            }

            // Set app mode
            $this->setEnvValue('APP_MODE', 'demo');
            $this->setEnvValue('APP_NAME', '"XapiVerse Demo"');

            session(['install_mode' => 'demo']);

        } catch (\Exception $e) {
            return redirect()->route('install.mode')
                ->withErrors(['error' => 'Demo setup failed: ' . $e->getMessage()]);
        }

        return redirect()->route('install.complete');
    }

    /**
     * Step 5: Create accounts (Business mode only)
     */
    public function accounts()
    {
        if (session('install_mode') !== 'business') {
            return redirect()->route('install.mode');
        }

        return view('install.accounts');
    }

    /**
     * Step 5: Save accounts (Business mode)
     */
    public function saveAccounts(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
            'developer_name' => 'nullable|string|max:255',
            'developer_email' => 'nullable|email|max:255',
            'developer_password' => 'nullable|string|min:8',
            'user_name' => 'nullable|string|max:255',
            'user_email' => 'nullable|email|max:255',
            'user_password' => 'nullable|string|min:8',
        ]);

        try {
            // Re-establish DB connection
            $this->refreshDbConnection();

            // Update .env
            $this->setEnvValue('APP_MODE', 'business');
            $this->setEnvValue('APP_NAME', '"' . $request->input('site_name') . '"');
            $this->setEnvValue('APP_URL', $request->input('site_url'));

            // Create Super Admin
            User::create([
                'name' => $request->input('admin_name'),
                'email' => $request->input('admin_email'),
                'password' => Hash::make($request->input('admin_password')),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            // Create Developer (optional)
            if ($request->filled('developer_email')) {
                User::create([
                    'name' => $request->input('developer_name'),
                    'email' => $request->input('developer_email'),
                    'password' => Hash::make($request->input('developer_password')),
                    'role' => 'developer',
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]);
            }

            // Create User (optional)
            if ($request->filled('user_email')) {
                User::create([
                    'name' => $request->input('user_name'),
                    'email' => $request->input('user_email'),
                    'password' => Hash::make($request->input('user_password')),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]);
            }

            // Store business mode setting
            Setting::set('install_mode', 'business', 'general', 'string');

            // Store accounts info for complete page
            session(['install_accounts' => [
                'admin' => ['email' => $request->input('admin_email')],
                'developer' => $request->filled('developer_email') ? ['email' => $request->input('developer_email')] : null,
                'user' => $request->filled('user_email') ? ['email' => $request->input('user_email')] : null,
            ]]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Setup failed: ' . $e->getMessage()]);
        }

        return redirect()->route('install.complete');
    }

    /**
     * Step 6: Installation complete
     */
    public function complete()
    {
        $mode = session('install_mode', 'business');
        $accounts = session('install_accounts', []);

        $demoCredentials = [];
        if ($mode === 'demo') {
            $demoCredentials = [
                'admin' => ['email' => 'admin@xapiverse.com', 'password' => 'password'],
                'developer' => ['email' => 'dev@xapiverse.com', 'password' => 'password'],
                'user' => ['email' => 'user@xapiverse.com', 'password' => 'password'],
            ];
        }

        // Mark as installed
        File::put(storage_path('installed/installed.lock'), json_encode([
            'installed_at' => now()->toDateTimeString(),
            'version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'mode' => $mode,
        ]));

        // Clear caches
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            // Ignore cache clear errors
        }

        return view('install.complete', compact('mode', 'accounts', 'demoCredentials'));
    }

    /**
     * Refresh database connection with current .env values
     */
    private function refreshDbConnection(): void
    {
        $envPath = base_path('.env');
        $env = [];
        foreach (explode("\n", File::get($envPath)) as $line) {
            if (str_contains($line, '=') && !str_starts_with(trim($line), '#')) {
                [$key, $value] = explode('=', $line, 2);
                $env[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
            }
        }

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => $env['DB_HOST'] ?? '127.0.0.1',
            'database.connections.mysql.port' => $env['DB_PORT'] ?? '3306',
            'database.connections.mysql.database' => $env['DB_DATABASE'] ?? 'xapiverse_db',
            'database.connections.mysql.username' => $env['DB_USERNAME'] ?? 'root',
            'database.connections.mysql.password' => $env['DB_PASSWORD'] ?? '',
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');
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
