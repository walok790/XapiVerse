<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('apiKeys');

        if ($request->input('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $user->load('apiKeys', 'transactions');
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,developer,user',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status}.");
    }

    public function addCredits(Request $request, User $user)
    {
        $request->validate([
            'credits' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $credits = $request->input('credits');

        // Add to first active API key, or create one
        $apiKey = $user->apiKeys()->where('is_active', true)->first();

        if (!$apiKey) {
            $apiKey = UserApiKey::create([
                'user_id' => $user->id,
                'name' => 'Default Key',
                'api_key' => UserApiKey::generateKey('live'),
                'prefix' => 'xv_live_',
                'credits_balance' => 0,
                'is_active' => true,
                'rate_limit_per_minute' => 60,
            ]);
        }

        $apiKey->increment('credits_balance', $credits);
        $user->increment('total_credits_purchased', $credits);

        // Log transaction
        Transaction::create([
            'user_id' => $user->id,
            'transaction_id' => 'admin_' . Str::random(16),
            'type' => 'admin_credit',
            'credits' => $credits,
            'amount' => 0,
            'status' => 'completed',
            'notes' => $request->input('notes', 'Admin credited'),
        ]);

        return back()->with('success', number_format($credits) . ' credits added to ' . $user->name);
    }
}
