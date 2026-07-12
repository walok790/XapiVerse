<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:developer,user',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'is_active' => true,
        ]);

        // If developer, create a default API key with free credits
        if ($user->role === 'developer') {
            UserApiKey::create([
                'user_id' => $user->id,
                'name' => 'Default Key',
                'api_key' => UserApiKey::generateKey('live'),
                'prefix' => 'xv_live_',
                'credits_balance' => 1000, // Free starter credits
                'is_active' => true,
                'rate_limit_per_minute' => 60,
            ]);
        }

        Auth::login($user);

        return match($user->role) {
            'developer' => redirect()->route('developer.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }
}
