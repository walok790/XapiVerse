<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\UserApiKey;
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
     * Step 3: Database configuration
     */
    public function database()
    {
        return view('install.database');
    }

    /**
     * Step 3: Save database config and run migrations
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

        // Update .env with database settings
        $this->setEnvValue('DB_CONNECTION', 'mysql');
        $this->setEnvValue('DB_HOST', $request->input('db_host'));
        $this->setEnvValue('DB_PORT', $request->input('db_port'));
        $this->setEnvValue('DB_DATABASE', $request->input('db_name'));
        $this->setEnvValue('DB_USERNAME', $request->input('db_user'));
        $this->setEnvValue('DB_PASSWORD', $request->input('db_password', ''));

        // Test database connection
        try {
            config([
                'database.connections.mysql.host' => $request->input('db_host'),
                'database.connections.mysql.port' => $request->input('db_port'),
                'database.connections.mysql.database' => $request->input('db_name'),
                'database.connections.mysql.username' => $request->input('db_user'),
                'database.connections.mysql.password' => $request->input('db_password', ''),
            ]);

            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Database connection failed: ' . $e->getMessage()]);
        }

        // Run migrations
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Migration failed: ' . $e->getMessage()]);
        }

        return redirect()->route('install.mode');
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

        // Store mode in session for next step
        session(['install_mode' => $mode]);

        if ($mode === 'demo') {
            // Demo mode: seed everything automatically
            return $this->setupDemoMode();
        }

        // Business mode: go to account creation
        return redirect()->route('install.accounts');
    }

    /**
     * Setup demo mode - seed all demo data and finish
     */
    private function setupDemoMode()
    {
        try {
            // Set demo mode in env
            $this->setEnvValue('APP_MODE', 'demo');
            $this->setEnvValue('APP_NAME', '"XapiVerse Demo"');

            // Seed default settings + demo data
            Artisan::call('db:seed', ['--class' => 'DefaultSettingsSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'DemoSeeder', '--force' => true]);

            // Store demo mode setting
            Setting::set('install_mode', 'demo', 'general', 'string');

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
        // Only accessible in business mode
        if (session('install_mode') !== 'business') {
            return redirect()->route('install.mode');
        }

        return view('install.accounts');
    }

    /**
     * Step 5: Save accounts
     */
    public function saveAccounts(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url',
            // Admin
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
            // Developer (optional)
            'developer_name' => 'nullable|string|max:255',
            'developer_email' => 'nullable|email|max:255',
            'developer_password' => 'nullable|string|min:8',
            // User (optional)
            'user_name' => 'nullable|string|max:255',
            'user_email' => 'nullable|email|max:255',
            'user_password' => 'nullable|string|min:8',
        ]);

        try {
            // Update .env
            $this->setEnvValue('APP_MODE', 'business');
            $this->setEnvValue('APP_NAME', '"' . $request->input('site_name') . '"');
            $this->setEnvValue('APP_URL', $request->input('site_url'));

            // Create Admin
            User::create([
                'name' => $request->input('admin_name'),
                'email' => $request->input('admin_email'),
                'password' => Hash::make($request->input('admin_password')),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            // Create Developer (if provided)
            if ($request->filled('developer_email')) {
                $dev = User::create([
                    'name' => $request->input('developer_name'),
                    'email' => $request->input('developer_email'),
                    'password' => Hash::make($request->input('developer_password')),
                    'role' => 'developer',
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]);

                // Create default API key for developer
                UserApiKey::create([
                    'user_id' => $dev->id,
                    'name' => 'Default Key',
                    'api_key' => UserApiKey::generateKey('live'),
                    'prefix' => 'xv_live_',
                    'credits_balance' => 1000,
                    'is_active' => true,
                    'rate_limit_per_minute' => 60,
                ]);
            }

            // Create User (if provided)
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

            // Seed default settings
            Artisan::call('db:seed', ['--class' => 'DefaultSettingsSeeder', '--force' => true]);

            // Store business mode
            Setting::set('install_mode', 'business', 'general', 'string');

            // Store created accounts info in session for complete page
            session(['install_accounts' => [
                'admin' => ['email' => $request->input('admin_email')],
                'developer' => $request->filled('developer_email') ? ['email' => $request->input('developer_email')] : null,
                'user' => $request->filled('user_email') ? ['email' => $request->input('user_email')] : null,
            ]]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Account setup failed: ' . $e->getMessage()]);
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

        // Demo credentials
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
            'version' => config('app.version', '1.0.0'),
            'php_version' => PHP_VERSION,
            'mode' => $mode,
        ]));

        // Clear all caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return view('install.complete', compact('mode', 'accounts', 'demoCredentials'));
    }

    /**
     * Check if a directory is writable
     */
    private function checkPermission(string $path): bool
    {
        return File::isWritable($path);
    }

    /**
     * Set or update a value in .env file
     */
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
