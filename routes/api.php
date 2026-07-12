<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Install\InstallController;

/*
|--------------------------------------------------------------------------
| Installation AJAX endpoint (no session, no CSRF - cannot crash)
|--------------------------------------------------------------------------
*/
Route::post('/install/run-step', [InstallController::class, 'runStep'])->name('install.run-step');

/*
|--------------------------------------------------------------------------
| API Routes (v1)
| These will be built in Phase 4 - Developer Platform
|--------------------------------------------------------------------------
|
| Prefix: /api/v1
| Middleware: api.auth (ApiAuthenticate)
|
| Future endpoints:
| POST /api/v1/terabox/download
| POST /api/v1/terabox/stream
| POST /api/v1/twitter/media
| etc.
|
*/

Route::prefix('v1')->group(function () {
    // Public - health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'version' => '1.0.0',
            'timestamp' => now()->toISOString(),
        ]);
    });

    // Protected API routes (require API key)
    Route::middleware('api.auth')->group(function () {
        // Account info
        Route::get('/me', function (\Illuminate\Http\Request $request) {
            $apiKey = $request->attributes->get('api_key');
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $apiKey->user->name,
                    'email' => $apiKey->user->email,
                    'credits_remaining' => $apiKey->credits_balance,
                    'total_used' => $apiKey->total_used,
                    'rate_limit' => $apiKey->rate_limit_per_minute,
                ],
            ]);
        });

        // Generic proxy: forwards to any registered service
        // POST /api/v1/terabox/download  →  forwards to TeraBox source
        // POST /api/v1/twitter/media     →  forwards to Twitter source
        // GET  /api/v1/terabox/info      →  forwards to TeraBox source
        Route::any('/{service}/{endpoint?}', [\App\Http\Controllers\Api\V1\ProxyController::class, 'handle'])
            ->where('endpoint', '.*');
    });
});
