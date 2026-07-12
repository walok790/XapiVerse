<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\UserApiKey;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function index()
    {
        $keys = auth()->user()->apiKeys()->withCount('requestLogs')->latest()->get();
        return view('developer.api-keys.index', compact('keys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate_limit_per_minute' => 'required|integer|min:1|max:1000',
        ]);

        $maxKeys = (int) (\App\Models\Setting::get('max_keys_per_user') ?? 10);
        $currentCount = auth()->user()->apiKeys()->count();

        if ($currentCount >= $maxKeys) {
            return back()->withErrors(['error' => "Maximum {$maxKeys} API keys allowed."]);
        }

        UserApiKey::create([
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
            'api_key' => UserApiKey::generateKey('live'),
            'prefix' => 'xv_live_',
            'credits_balance' => 0,
            'is_active' => true,
            'rate_limit_per_minute' => $request->input('rate_limit_per_minute'),
        ]);

        return back()->with('success', 'API key created successfully.');
    }

    public function destroy(UserApiKey $apiKey)
    {
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }
        $apiKey->delete();
        return back()->with('success', 'API key revoked.');
    }

    public function toggle(UserApiKey $apiKey)
    {
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }
        $apiKey->update(['is_active' => !$apiKey->is_active]);
        return back()->with('success', 'API key ' . ($apiKey->is_active ? 'activated' : 'deactivated') . '.');
    }
}
