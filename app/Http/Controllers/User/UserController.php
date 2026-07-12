<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // ─── HOME (Search + Recent Watches) ────────────────────────
    public function home()
    {
        $user = auth()->user();
        $recentWatches = DB::table('watch_history')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $todaySearches = DB::table('watch_history')
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $downloadCount = DB::table('download_stats')
            ->where('user_id', $user->id)
            ->count();

        $bookmarkCount = DB::table('bookmarks')
            ->where('user_id', $user->id)
            ->count();

        return view('user.home', compact('user', 'recentWatches', 'todaySearches', 'downloadCount', 'bookmarkCount'));
    }

    // ─── PROCESS LINK (AJAX) ───────────────────────────────────
    public function processLink(Request $request)
    {
        $request->validate(['link' => 'required|url']);

        $user = auth()->user();
        $link = $request->input('link');

        // Save to watch history
        DB::table('watch_history')->insert([
            'user_id' => $user->id,
            'link' => $link,
            'title' => 'TeraBox Video',
            'status' => 'success',
            'created_at' => now(),
        ]);

        // Save download stat count
        DB::table('download_stats')->insert([
            'user_id' => $user->id,
            'link' => $link,
            'title' => 'TeraBox Video',
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'original_link' => $link,
                'message' => 'Video processed successfully. Connect TeraBox API source keys in Admin Panel for real streaming.',
                'note' => 'This is a demo response. In production, this returns streaming/download URLs.',
            ],
        ]);
    }

    // ─── HISTORY ───────────────────────────────────────────────
    public function history()
    {
        $history = DB::table('watch_history')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('user.history', compact('history'));
    }

    public function deleteHistory(Request $request, $id)
    {
        DB::table('watch_history')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'History entry deleted.');
    }

    public function clearHistory()
    {
        DB::table('watch_history')->where('user_id', auth()->id())->delete();
        return back()->with('success', 'All history cleared.');
    }

    // ─── BOOKMARKS ─────────────────────────────────────────────
    public function bookmarks()
    {
        $bookmarks = DB::table('bookmarks')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('user.bookmarks', compact('bookmarks'));
    }

    public function addBookmark(Request $request)
    {
        $request->validate(['link' => 'required|url']);

        DB::table('bookmarks')->insert([
            'user_id' => auth()->id(),
            'link' => $request->input('link'),
            'title' => $request->input('title', 'TeraBox Video'),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Bookmarked!']);
    }

    public function removeBookmark($id)
    {
        DB::table('bookmarks')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Bookmark removed.');
    }

    public function clearBookmarks()
    {
        DB::table('bookmarks')->where('user_id', auth()->id())->delete();
        return back()->with('success', 'All bookmarks cleared.');
    }

    // ─── SUBSCRIPTION ──────────────────────────────────────────
    public function subscription()
    {
        $user = auth()->user();
        $currentPlan = DB::table('user_subscriptions')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        return view('user.subscription', compact('currentPlan'));
    }

    // ─── TRANSACTIONS ──────────────────────────────────────────
    public function transactions()
    {
        $transactions = DB::table('transactions')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('user.transactions', compact('transactions'));
    }

    // ─── SUPPORT ───────────────────────────────────────────────
    public function support()
    {
        $tickets = DB::table('support_tickets')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.support', compact('tickets'));
    }

    public function createTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high',
        ]);

        DB::table('support_tickets')->insert([
            'user_id' => auth()->id(),
            'ticket_id' => 'TKT-' . strtoupper(Str::random(8)),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'priority' => $request->input('priority'),
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Support ticket created successfully!');
    }

    // ─── NOTIFICATIONS ─────────────────────────────────────────
    public function notifications()
    {
        $notifications = DB::table('notifications')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        // Mark all as read
        DB::table('notifications')
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('user.notifications', compact('notifications'));
    }

    // ─── PROFILE ───────────────────────────────────────────────
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

    // ─── NOTIFICATION COUNT (for nav badge) ────────────────────
    public function unreadCount()
    {
        $count = DB::table('notifications')
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
