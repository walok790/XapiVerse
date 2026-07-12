<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ApiRequestLog;
use App\Models\ApiService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_credits' => $user->apiKeys()->sum('credits_balance'),
            'total_used' => $user->total_credits_used,
            'active_keys' => $user->apiKeys()->where('is_active', true)->count(),
            'requests_today' => ApiRequestLog::where('user_id', $user->id)->whereDate('created_at', today())->count(),
            'requests_this_month' => ApiRequestLog::where('user_id', $user->id)->whereMonth('created_at', now()->month)->count(),
            'success_rate' => $this->getSuccessRate($user->id),
        ];

        $recentLogs = ApiRequestLog::where('user_id', $user->id)
            ->with('apiService')
            ->latest('created_at')
            ->limit(10)
            ->get();

        $services = ApiService::where('is_active', true)->where('is_public', true)->get();

        // Daily usage for last 7 days
        $dailyUsage = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyUsage[] = [
                'date' => $date->format('M d'),
                'count' => ApiRequestLog::where('user_id', $user->id)->whereDate('created_at', $date)->count(),
            ];
        }

        return view('developer.dashboard', compact('stats', 'recentLogs', 'services', 'dailyUsage'));
    }

    private function getSuccessRate(int $userId): float
    {
        $total = ApiRequestLog::where('user_id', $userId)->whereMonth('created_at', now()->month)->count();
        if ($total === 0) return 100;
        $success = ApiRequestLog::where('user_id', $userId)->whereMonth('created_at', now()->month)->where('status', 'success')->count();
        return round(($success / $total) * 100, 1);
    }
}
