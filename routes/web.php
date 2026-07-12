<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Install\InstallController;

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
    Route::get('/admin', [InstallController::class, 'admin'])->name('install.admin');
    Route::post('/admin', [InstallController::class, 'saveAdmin'])->name('install.save-admin');
    Route::get('/complete', [InstallController::class, 'complete'])->name('install.complete');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
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
    Route::get('/', function () { return view('admin.dashboard'); })->name('dashboard');

    // Placeholder routes for navigation (will be fully built in Phase 2)
    Route::get('/services', function () { return view('admin.dashboard'); })->name('services.index');
    Route::get('/source-keys', function () { return view('admin.dashboard'); })->name('source-keys.index');
    Route::get('/users', function () { return view('admin.dashboard'); })->name('users.index');
    Route::get('/logs', function () { return view('admin.dashboard'); })->name('logs.index');
    Route::get('/settings', function () { return view('admin.dashboard'); })->name('settings.index');
});

/*
|--------------------------------------------------------------------------
| Developer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/', function () { return view('developer.dashboard'); })->name('dashboard');

    // Placeholder routes for navigation (will be fully built in Phase 4)
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
