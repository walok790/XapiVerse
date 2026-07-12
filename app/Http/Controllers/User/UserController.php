<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $services = ApiService::where('is_active', true)->where('is_public', true)->orderBy('sort_order')->get();

        return view('user.dashboard', compact('user', 'services'));
    }

    public function player()
    {
        return view('user.player');
    }

    public function processLink(Request $request)
    {
        $request->validate([
            'link' => 'required|url',
        ]);

        // In production, this would call the TeraBox API via the proxy service
        // For now, return the link info for demonstration
        $link = $request->input('link');

        return response()->json([
            'success' => true,
            'data' => [
                'original_link' => $link,
                'message' => 'API integration will process this link via the rotation engine.',
                'note' => 'Connect a real TeraBox API source key in Admin Panel to enable this feature.',
            ],
        ]);
    }

    public function subscription()
    {
        return view('user.subscription');
    }

    public function profile()
    {
        return view('user.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->input('password'))]);

        return back()->with('success', 'Password changed successfully.');
    }
}
