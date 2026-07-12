<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;

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

        return redirect()->route('install.admin');
    }

    /**
     * Step 4: Create admin account & import default data
     */
    public function admin()
    {
        return view('install.admin');
    }

    /**
     * Step 4: Save admin account
     */
    public function saveAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url',
        ]);

        try {
            // Create admin user
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            // Update .env with site settings
            $this->setEnvValue('APP_NAME', '"' . $request->input('site_name') . '"');
            $this->setEnvValue('APP_URL', $request->input('site_url'));

            // Seed default data
            Artisan::call('db:seed', ['--class' => 'DefaultSettingsSeeder', '--force' => true]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Setup failed: ' . $e->getMessage()]);
        }

        return redirect()->route('install.complete');
    }

    /**
     * Step 5: Installation complete
     */
    public function complete()
    {
        // Mark as installed
        File::put(storage_path('installed/installed.lock'), json_encode([
            'installed_at' => now()->toDateTimeString(),
            'version' => config('app.version', '1.0.0'),
            'php_version' => PHP_VERSION,
        ]));

        // Clear all caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return view('install.complete');
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
