<?php

namespace App\Services;

use App\Models\ApiService;
use App\Models\ApiSourceKey;
use Illuminate\Support\Facades\Cache;

class KeyRotationService
{
    /**
     * Get the next available source key for a service based on its rotation strategy.
     * Tries up to $maxRetries keys before giving up.
     */
    public function getNextKey(ApiService $service, int $maxRetries = 3): ?ApiSourceKey
    {
        for ($i = 0; $i < $maxRetries; $i++) {
            $key = match ($service->rotation_strategy) {
                'round_robin' => $this->roundRobin($service),
                'priority' => $this->priorityFirst($service),
                'least_used' => $this->leastUsed($service),
                'weighted' => $this->weightedRandom($service),
                'fill_rotate' => $this->fillAndRotate($service),
                default => $this->roundRobin($service),
            };

            if ($key && $key->isAvailable()) {
                return $key;
            }
        }

        return null;
    }

    /**
     * ROUND ROBIN: Pick the key used longest ago
     */
    private function roundRobin(ApiService $service): ?ApiSourceKey
    {
        return $this->baseQuery($service)
            ->orderBy('last_used_at', 'asc')
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * PRIORITY FIRST: Use highest priority keys first (1=highest)
     */
    private function priorityFirst(ApiService $service): ?ApiSourceKey
    {
        return $this->baseQuery($service)
            ->orderBy('priority', 'asc')
            ->orderBy('last_used_at', 'asc')
            ->first();
    }

    /**
     * LEAST USED: Pick the key with lowest daily usage
     */
    private function leastUsed(ApiService $service): ?ApiSourceKey
    {
        return $this->baseQuery($service)
            ->orderBy('used_today', 'asc')
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * WEIGHTED RANDOM: Pick randomly but weighted by weight field
     */
    private function weightedRandom(ApiService $service): ?ApiSourceKey
    {
        $keys = $this->baseQuery($service)->get();

        if ($keys->isEmpty()) return null;

        $totalWeight = $keys->sum('weight');
        if ($totalWeight <= 0) return $keys->first();

        $random = mt_rand(1, $totalWeight);
        $cumulative = 0;

        foreach ($keys as $key) {
            $cumulative += $key->weight;
            if ($random <= $cumulative) {
                return $key;
            }
        }

        return $keys->last();
    }

    /**
     * FILL & ROTATE: Use one key until its limit, then move to next
     */
    private function fillAndRotate(ApiService $service): ?ApiSourceKey
    {
        return $this->baseQuery($service)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * Base query: get available keys for a service
     */
    private function baseQuery(ApiService $service)
    {
        return ApiSourceKey::where('api_service_id', $service->id)
            ->where('is_active', true)
            ->where('is_exhausted', false)
            ->where(function ($q) {
                $q->whereNull('cooldown_until')
                  ->orWhere('cooldown_until', '<', now());
            })
            ->where(function ($q) {
                $q->whereNull('daily_limit')
                  ->orWhereColumn('used_today', '<', 'daily_limit');
            })
            ->where(function ($q) {
                $q->whereNull('monthly_limit')
                  ->orWhereColumn('used_this_month', '<', 'monthly_limit');
            })
            ->where(function ($q) {
                $q->whereNull('total_limit')
                  ->orWhereColumn('used_total', '<', 'total_limit');
            });
    }

    /**
     * Mark a key as successfully used
     */
    public function markSuccess(ApiSourceKey $key, float $responseTimeMs = 0): void
    {
        $key->increment('used_today');
        $key->increment('used_this_month');
        $key->increment('used_total');
        $key->increment('success_count');

        $key->update([
            'last_used_at' => now(),
            'error_count' => 0,
            'cooldown_until' => null,
            'avg_response_time_ms' => $key->success_count > 1
                ? (($key->avg_response_time_ms * ($key->success_count - 1)) + $responseTimeMs) / $key->success_count
                : $responseTimeMs,
        ]);

        // Check if daily limit reached
        if ($key->daily_limit && $key->used_today >= $key->daily_limit) {
            $key->update(['is_exhausted' => true]);
        }

        // Check if total limit reached
        if ($key->total_limit && $key->used_total >= $key->total_limit) {
            $key->update(['is_active' => false]);
        }
    }

    /**
     * Mark a key as failed
     */
    public function markFailed(ApiSourceKey $key, string $error = ''): void
    {
        $key->increment('error_count');
        $key->update([
            'last_error' => $error,
            'last_used_at' => now(),
        ]);

        $maxErrors = (int) ($this->getSetting('key_auto_disable_errors') ?? 5);
        $cooldownSeconds = (int) ($this->getSetting('key_cooldown_seconds') ?? 60);

        // Auto-disable after too many consecutive errors
        if ($key->error_count >= $maxErrors) {
            $key->update([
                'is_active' => false,
                'notes' => 'Auto-disabled: ' . $maxErrors . ' consecutive errors. Last: ' . $error,
            ]);
        } else {
            // Cooldown
            $key->update([
                'cooldown_until' => now()->addSeconds($cooldownSeconds),
            ]);
        }
    }

    private function getSetting(string $key)
    {
        return Cache::remember('setting_' . $key, 3600, function () use ($key) {
            return \App\Models\Setting::where('key', $key)->value('value');
        });
    }
}
