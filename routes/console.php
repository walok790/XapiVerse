<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\ApiSourceKey;

/*
|--------------------------------------------------------------------------
| Console Routes / Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Daily reset: Reset usage counters at midnight
Schedule::call(function () {
    ApiSourceKey::where('is_active', true)->update([
        'used_today' => 0,
        'is_exhausted' => false,
        'error_count' => 0,
        'cooldown_until' => null,
    ]);

    Artisan::call('cache:clear');
})->daily()->at('00:00')->name('daily-key-reset');

// Monthly reset: Reset monthly counters on 1st of each month
Schedule::call(function () {
    ApiSourceKey::where('is_active', true)->update([
        'used_this_month' => 0,
    ]);
})->monthlyOn(1, '00:05')->name('monthly-key-reset');

// Cleanup old logs (older than 90 days)
Schedule::call(function () {
    \App\Models\ApiRequestLog::where('created_at', '<', now()->subDays(90))->delete();
})->weekly()->name('cleanup-old-logs');
