<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSourceKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_service_id',
        'key_type',
        'api_key',
        'base_url_override',
        'headers_override',
        'label',
        'daily_limit',
        'monthly_limit',
        'total_limit',
        'used_today',
        'used_this_month',
        'used_total',
        'priority',
        'weight',
        'is_active',
        'is_exhausted',
        'last_used_at',
        'cooldown_until',
        'last_error',
        'error_count',
        'success_count',
        'avg_response_time_ms',
        'notes',
        'import_batch_id',
    ];

    protected function casts(): array
    {
        return [
            'headers_override' => 'array',
            'is_active' => 'boolean',
            'is_exhausted' => 'boolean',
            'last_used_at' => 'datetime',
            'cooldown_until' => 'datetime',
            'daily_limit' => 'integer',
            'monthly_limit' => 'integer',
            'total_limit' => 'integer',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function service()
    {
        return $this->belongsTo(ApiService::class, 'api_service_id');
    }

    public function requestLogs()
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    // ─── Helpers ───────────────────────────────────────────────

    public function isAvailable(): bool
    {
        if (!$this->is_active || $this->is_exhausted) {
            return false;
        }

        if ($this->cooldown_until && $this->cooldown_until->isFuture()) {
            return false;
        }

        if ($this->daily_limit && $this->used_today >= $this->daily_limit) {
            return false;
        }

        if ($this->monthly_limit && $this->used_this_month >= $this->monthly_limit) {
            return false;
        }

        if ($this->total_limit && $this->used_total >= $this->total_limit) {
            return false;
        }

        return true;
    }

    public function getEffectiveUrl(): string
    {
        return $this->base_url_override ?? $this->service->base_url;
    }

    public function getDailyUsagePercentage(): float
    {
        if (!$this->daily_limit) return 0;
        return round(($this->used_today / $this->daily_limit) * 100, 1);
    }

    public function getMaskedKey(): string
    {
        $key = $this->api_key;
        if (strlen($key) <= 8) return '****';
        return substr($key, 0, 4) . '...' . substr($key, -4);
    }
}
