<?php

namespace App\Services;

use App\Models\ApiRequestLog;
use App\Models\ApiService;
use App\Models\ApiSourceKey;
use App\Models\UserApiKey;
use Illuminate\Support\Facades\Http;

class ApiProxyService
{
    protected KeyRotationService $rotationService;

    public function __construct(KeyRotationService $rotationService)
    {
        $this->rotationService = $rotationService;
    }

    /**
     * Handle an API request: validate, pick source key, forward, return response
     */
    public function handle(UserApiKey $userKey, ApiService $service, string $endpoint, array $params = [], string $method = 'POST'): array
    {
        $startTime = microtime(true);

        // 1. Check if user key can access this service
        if (!$userKey->canAccessService($service->slug)) {
            return $this->errorResponse('Your API key does not have access to this service.', 403);
        }

        // 2. Check credits
        $creditsNeeded = $service->credits_per_request;
        if ($userKey->credits_balance < $creditsNeeded) {
            $this->logRequest($userKey, $service, null, $endpoint, $method, 'no_credits', 0, 0, 'Insufficient credits');
            return $this->errorResponse('Insufficient credits. Need ' . $creditsNeeded . ', have ' . $userKey->credits_balance, 402);
        }

        // 3. Get next source key via rotation
        $sourceKey = $this->rotationService->getNextKey($service);

        if (!$sourceKey) {
            $this->logRequest($userKey, $service, null, $endpoint, $method, 'failed', 0, 0, 'No available source keys');
            return $this->errorResponse('Service temporarily unavailable. All source keys exhausted.', 503);
        }

        // 4. Forward request to source
        try {
            $response = $this->forwardRequest($sourceKey, $endpoint, $params, $method);
            $responseTimeMs = (microtime(true) - $startTime) * 1000;

            if ($response['success']) {
                // Success: deduct credits, mark key used
                $userKey->decrement('credits_balance', $creditsNeeded);
                $userKey->increment('total_used');
                $userKey->update(['last_used_at' => now()]);

                $this->rotationService->markSuccess($sourceKey, $responseTimeMs);
                $this->logRequest($userKey, $service, $sourceKey, $endpoint, $method, 'success', $response['status_code'] ?? 200, $responseTimeMs, null, $creditsNeeded);

                return [
                    'success' => true,
                    'data' => $response['data'] ?? null,
                    'credits_used' => $creditsNeeded,
                    'credits_remaining' => $userKey->credits_balance,
                ];
            } else {
                // Source returned error — try to failover
                $this->rotationService->markFailed($sourceKey, $response['error'] ?? 'Unknown error');
                $responseTimeMs = (microtime(true) - $startTime) * 1000;

                // Try next key (failover)
                $nextKey = $this->rotationService->getNextKey($service);
                if ($nextKey && $nextKey->id !== $sourceKey->id) {
                    $retryResponse = $this->forwardRequest($nextKey, $endpoint, $params, $method);
                    $responseTimeMs = (microtime(true) - $startTime) * 1000;

                    if ($retryResponse['success']) {
                        $userKey->decrement('credits_balance', $creditsNeeded);
                        $userKey->increment('total_used');
                        $this->rotationService->markSuccess($nextKey, $responseTimeMs);
                        $this->logRequest($userKey, $service, $nextKey, $endpoint, $method, 'success', $retryResponse['status_code'] ?? 200, $responseTimeMs, null, $creditsNeeded);

                        return [
                            'success' => true,
                            'data' => $retryResponse['data'] ?? null,
                            'credits_used' => $creditsNeeded,
                            'credits_remaining' => $userKey->credits_balance,
                        ];
                    }
                }

                $this->logRequest($userKey, $service, $sourceKey, $endpoint, $method, 'failed', $response['status_code'] ?? 500, $responseTimeMs, $response['error'] ?? 'Source error');
                return $this->errorResponse('Source API returned an error: ' . ($response['error'] ?? 'Unknown'), 502);
            }
        } catch (\Exception $e) {
            $responseTimeMs = (microtime(true) - $startTime) * 1000;
            $this->rotationService->markFailed($sourceKey, $e->getMessage());
            $this->logRequest($userKey, $service, $sourceKey, $endpoint, $method, 'failed', 0, $responseTimeMs, $e->getMessage());
            return $this->errorResponse('Request failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Forward the request to the source API
     */
    private function forwardRequest(ApiSourceKey $sourceKey, string $endpoint, array $params, string $method): array
    {
        $baseUrl = $sourceKey->getEffectiveUrl();
        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $headers = ['Accept' => 'application/json'];

        // Add source key as authorization
        $headers['Authorization'] = 'Bearer ' . $sourceKey->api_key;

        // Merge custom headers
        if ($sourceKey->headers_override) {
            $headers = array_merge($headers, $sourceKey->headers_override);
        }

        $http = Http::withHeaders($headers)->timeout(30);

        $response = match (strtoupper($method)) {
            'GET' => $http->get($url, $params),
            'POST' => $http->post($url, $params),
            'PUT' => $http->put($url, $params),
            'DELETE' => $http->delete($url, $params),
            default => $http->post($url, $params),
        };

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'status_code' => $response->status(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
            'status_code' => $response->status(),
        ];
    }

    private function logRequest(UserApiKey $userKey, ApiService $service, ?ApiSourceKey $sourceKey, string $endpoint, string $method, string $status, int $statusCode, float $responseTimeMs, ?string $error = null, int $creditsCharged = 0): void
    {
        try {
            ApiRequestLog::create([
                'user_id' => $userKey->user_id,
                'user_api_key_id' => $userKey->id,
                'api_service_id' => $service->id,
                'api_source_key_id' => $sourceKey?->id,
                'endpoint' => $endpoint,
                'method' => $method,
                'status' => $status,
                'http_status_code' => $statusCode,
                'response_time_ms' => $responseTimeMs,
                'credits_charged' => $creditsCharged,
                'ip_address' => request()->ip(),
                'error_message' => $error,
            ]);
        } catch (\Exception $e) {
            // Don't let logging errors break the response
        }
    }

    private function errorResponse(string $message, int $code): array
    {
        return [
            'success' => false,
            'error' => $message,
            'code' => $code,
        ];
    }
}
