<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Install\InstallController;
use App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Installation Routes (only accessible when not installed)
|--------------------------------------------------------------------------
*/
Route::middleware('install.check')->prefix('install')->group(function () {
    Route::get('/', [InstallController::class, 'requirements'])->name('install.requirements');
    Route::get('/permissions', [InstallController::class, 'permissions'])->name('install.permissions');
    Route::get('/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/database', [InstallController::class, 'saveDatabase'])->name('install.save-database');
    Route::get('/mode', [InstallController::class, 'mode'])->name('install.mode');
    Route::post('/mode', [InstallController::class, 'saveMode'])->name('install.save-mode');
    Route::get('/accounts', [InstallController::class, 'accounts'])->name('install.accounts');
    Route::post('/accounts', [InstallController::class, 'saveAccounts'])->name('install.save-accounts');
    Route::get('/complete', [InstallController::class, 'complete'])->name('install.complete');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Developer & User)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Admin Login (Separate)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Home / Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'developer' => redirect()->route('developer.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // API Services CRUD
    Route::resource('services', Admin\ServiceController::class);

    // Source Keys Management
    Route::get('/source-keys', [Admin\SourceKeyController::class, 'index'])->name('source-keys.index');
    Route::get('/source-keys/create', [Admin\SourceKeyController::class, 'create'])->name('source-keys.create');
    Route::post('/source-keys', [Admin\SourceKeyController::class, 'store'])->name('source-keys.store');
    Route::post('/source-keys/bulk-import', [Admin\SourceKeyController::class, 'bulkImport'])->name('source-keys.bulk-import');
    Route::delete('/source-keys/{sourceKey}', [Admin\SourceKeyController::class, 'destroy'])->name('source-keys.destroy');
    Route::patch('/source-keys/{sourceKey}/toggle', [Admin\SourceKeyController::class, 'toggle'])->name('source-keys.toggle');

    // Users Management
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle', [Admin\UserController::class, 'toggle'])->name('users.toggle');
    Route::post('/users/{user}/credits', [Admin\UserController::class, 'addCredits'])->name('users.add-credits');

    // Request Logs
    Route::get('/logs', [Admin\LogController::class, 'index'])->name('logs.index');

    // Settings
    Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Developer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/', function () { return view('developer.dashboard'); })->name('dashboard');
    Route::get('/api-keys', function () { return view('developer.dashboard'); })->name('api-keys.index');
    Route::get('/docs', function () { return view('developer.dashboard'); })->name('docs');
    Route::get('/credits', function () { return view('developer.dashboard'); })->name('credits');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', function () { return view('user.dashboard'); })->name('dashboard');
});
