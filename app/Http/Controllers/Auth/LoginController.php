<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form for developers & users
     */
    public function showLoginForm()
    {
        $isDemo = $this->isDemoMode();
        $demoCredentials = [];

        if ($isDemo) {
            $demoCredentials = [
                'developer' => ['email' => 'dev@xapiverse.com', 'password' => 'password'],
                'user' => ['email' => 'user@xapiverse.com', 'password' => 'password'],
            ];
        }

        return view('auth.login', compact('isDemo', 'demoCredentials'));
    }

    /**
     * Show separate admin login form
     */
    public function showAdminLoginForm()
    {
        $isDemo = $this->isDemoMode();
        $demoCredentials = [];

        if ($isDemo) {
            $demoCredentials = [
                'admin' => ['email' => 'admin@xapiverse.com', 'password' => 'password'],
            ];
        }

        return view('auth.admin-login', compact('isDemo', 'demoCredentials'));
    }

    /**
     * Handle login for developers & users
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended.']);
            }

            // Don't allow admin login from user/dev form
            if ($user->role === 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Admin must login at /admin/login']);
            }

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            $request->session()->regenerate();

            return match($user->role) {
                'developer' => redirect()->intended(route('developer.dashboard')),
                default => redirect()->intended(route('user.dashboard')),
            };
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle admin login
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended.']);
            }

            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'This login is for administrators only.']);
            }

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $role = Auth::user()->role ?? null;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('admin.login');
        }
        return redirect()->route('login');
    }

    private function isDemoMode(): bool
    {
        try {
            return Setting::get('install_mode') === 'demo';
        } catch (\Exception $e) {
            return env('APP_MODE') === 'demo';
        }
    }
}
