<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiRequestLog;
use App\Models\ApiService;
use App\Models\ApiSourceKey;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_developers' => User::where('role', 'developer')->count(),
            'active_services' => ApiService::where('is_active', true)->count(),
            'active_source_keys' => ApiSourceKey::where('is_active', true)->where('is_exhausted', false)->count(),
            'total_source_keys' => ApiSourceKey::count(),
            'exhausted_keys_today' => ApiSourceKey::where('is_exhausted', true)->count(),
            'requests_today' => ApiRequestLog::whereDate('created_at', today())->count(),
            'requests_success_today' => ApiRequestLog::whereDate('created_at', today())->where('status', 'success')->count(),
            'revenue_total' => Transaction::where('status', 'completed')->sum('amount'),
            'revenue_this_month' => Transaction::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'),
        ];

        $recentLogs = ApiRequestLog::with(['user', 'apiService'])
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLogs'));
    }
}
