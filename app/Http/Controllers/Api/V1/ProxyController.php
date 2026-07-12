<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ApiService;
use App\Services\ApiProxyService;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    /**
     * Generic API proxy endpoint
     * POST /api/v1/{service}/{endpoint}
     *
     * Example:
     * POST /api/v1/terabox/download  { "url": "https://terabox.com/..." }
     * POST /api/v1/twitter/media     { "tweet_url": "https://x.com/..." }
     */
    public function handle(Request $request, ApiProxyService $proxy, string $service, string $endpoint = '')
    {
        // Find the service by slug
        $apiService = ApiService::where('slug', $service)->where('is_active', true)->first();

        if (!$apiService) {
            return response()->json([
                'success' => false,
                'error' => "Service '{$service}' not found or is inactive.",
            ], 404);
        }

        // Get user API key (set by ApiAuthenticate middleware)
        $userKey = $request->attributes->get('api_key');

        // Rate limiting check
        $rateLimitKey = 'rate_limit:' . $userKey->id . ':' . now()->format('Y-m-d-H-i');
        $currentCount = (int) cache($rateLimitKey, 0);

        $rateLimit = min($userKey->rate_limit_per_minute, $apiService->rate_limit_per_minute);

        if ($currentCount >= $rateLimit) {
            return response()->json([
                'success' => false,
                'error' => 'Rate limit exceeded. Max ' . $rateLimit . ' requests per minute.',
                'retry_after' => 60 - now()->second,
            ], 429);
        }

        // Increment rate counter
        cache([$rateLimitKey => $currentCount + 1], now()->addMinutes(1));

        // Forward via proxy service
        $result = $proxy->handle(
            $userKey,
            $apiService,
            $endpoint ?: '/',
            $request->all(),
            $request->method()
        );

        $statusCode = $result['code'] ?? ($result['success'] ? 200 : 500);

        return response()->json($result, $statusCode);
    }
}
