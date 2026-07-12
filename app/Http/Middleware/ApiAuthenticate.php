<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserApiKey;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticate
{
    /**
     * Authenticate API requests using Bearer token (user API key).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => 'API key is required. Pass it as: Authorization: Bearer YOUR_API_KEY',
            ], 401);
        }

        $apiKey = UserApiKey::where('api_key', $token)
            ->where('is_active', true)
            ->with('user')
            ->first();

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid or inactive API key.',
            ], 401);
        }

        // Check if user is active
        if (!$apiKey->user || !$apiKey->user->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'Account is suspended. Contact support.',
            ], 403);
        }

        // Check credits
        if ($apiKey->credits_balance <= 0) {
            return response()->json([
                'success' => false,
                'error' => 'Insufficient credits. Please top up your account.',
                'credits_remaining' => 0,
            ], 402);
        }

        // Attach to request for use in controllers
        $request->attributes->set('api_key', $apiKey);
        $request->attributes->set('api_user', $apiKey->user);

        return $next($request);
    }
}
