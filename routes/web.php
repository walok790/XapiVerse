<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Install\InstallController;
use App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Installation Routes
| Flow: Requirements → Permissions → Mode → Database → Account → Login
|--------------------------------------------------------------------------
*/
Route::prefix('install')->group(function () {
    Route::get('/', [InstallController::class, 'requirements'])->name('install.requirements');
    Route::get('/permissions', [InstallController::class, 'permissions'])->name('install.permissions');
    Route::get('/mode', [InstallController::class, 'mode'])->name('install.mode');
    Route::post('/mode', [InstallController::class, 'saveMode'])->name('install.save-mode');
    Route::get('/database', [InstallController::class, 'database'])->name('install.database');
    Route::get('/database/download-sql', [InstallController::class, 'downloadSql'])->name('install.download-sql');
    Route::get('/account', [InstallController::class, 'account'])->name('install.account');
    Route::post('/account', [InstallController::class, 'saveAccount'])->name('install.save-account');
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
            default => redirect()->route('user.home'),
        };
    }
    return view('landing');
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
    Route::get('/', [App\Http\Controllers\Developer\DashboardController::class, 'index'])->name('dashboard');

    // API Keys
    Route::get('/api-keys', [App\Http\Controllers\Developer\ApiKeyController::class, 'index'])->name('api-keys.index');
    Route::post('/api-keys', [App\Http\Controllers\Developer\ApiKeyController::class, 'store'])->name('api-keys.store');
    Route::delete('/api-keys/{apiKey}', [App\Http\Controllers\Developer\ApiKeyController::class, 'destroy'])->name('api-keys.destroy');
    Route::patch('/api-keys/{apiKey}/toggle', [App\Http\Controllers\Developer\ApiKeyController::class, 'toggle'])->name('api-keys.toggle');

    // Documentation
    Route::get('/docs', [App\Http\Controllers\Developer\DocsController::class, 'index'])->name('docs');
    Route::get('/docs/{slug}', [App\Http\Controllers\Developer\DocsController::class, 'show'])->name('docs.show');

    // Credits
    Route::get('/credits', [App\Http\Controllers\Developer\CreditController::class, 'index'])->name('credits');
    Route::post('/credits/purchase', [App\Http\Controllers\Developer\CreditController::class, 'purchase'])->name('credits.purchase');
});

/*
|--------------------------------------------------------------------------
| User Routes (Iteraplay - New Dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Home (Search + Recent Watches)
    Route::get('/', [App\Http\Controllers\User\UserController::class, 'home'])->name('home');
    Route::post('/process', [App\Http\Controllers\User\UserController::class, 'processLink'])->name('process');

    // History
    Route::get('/history', [App\Http\Controllers\User\UserController::class, 'history'])->name('history');
    Route::delete('/history/{id}', [App\Http\Controllers\User\UserController::class, 'deleteHistory'])->name('history.delete');
    Route::delete('/history', [App\Http\Controllers\User\UserController::class, 'clearHistory'])->name('history.clear');

    // Bookmarks
    Route::get('/bookmarks', [App\Http\Controllers\User\UserController::class, 'bookmarks'])->name('bookmarks');
    Route::post('/bookmarks', [App\Http\Controllers\User\UserController::class, 'addBookmark'])->name('bookmarks.add');
    Route::delete('/bookmarks/{id}', [App\Http\Controllers\User\UserController::class, 'removeBookmark'])->name('bookmarks.remove');
    Route::delete('/bookmarks', [App\Http\Controllers\User\UserController::class, 'clearBookmarks'])->name('bookmarks.clear');

    // Subscription
    Route::get('/subscription', [App\Http\Controllers\User\UserController::class, 'subscription'])->name('subscription');

    // Transactions
    Route::get('/transactions', [App\Http\Controllers\User\UserController::class, 'transactions'])->name('transactions');

    // Support
    Route::get('/support', [App\Http\Controllers\User\UserController::class, 'support'])->name('support');
    Route::post('/support', [App\Http\Controllers\User\UserController::class, 'createTicket'])->name('support.create');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\User\UserController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/count', [App\Http\Controllers\User\UserController::class, 'unreadCount'])->name('notifications.count');

    // Profile
    Route::get('/profile', [App\Http\Controllers\User\UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\User\UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\User\UserController::class, 'updatePassword'])->name('profile.password');
});
