<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'base_url',
        'version',
        'rotation_strategy',
        'credits_per_request',
        'rate_limit_per_minute',
        'is_active',
        'is_public',
        'endpoints',
        'headers',
        'documentation',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'endpoints' => 'array',
            'headers' => 'array',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function sourceKeys()
    {
        return $this->hasMany(ApiSourceKey::class);
    }

    public function requestLogs()
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    public function importBatches()
    {
        return $this->hasMany(ApiKeyImportBatch::class);
    }

    // ─── Helpers ───────────────────────────────────────────────

    public function getActiveKeysCount(): int
    {
        return $this->sourceKeys()->where('is_active', true)->where('is_exhausted', false)->count();
    }

    public function getTotalKeysCount(): int
    {
        return $this->sourceKeys()->count();
    }

    public function getExhaustedKeysCount(): int
    {
        return $this->sourceKeys()->where('is_exhausted', true)->count();
    }

    public function getTodayCapacity(): int
    {
        return $this->sourceKeys()
            ->where('is_active', true)
            ->sum('daily_limit') ?? 0;
    }

    public function getTodayUsage(): int
    {
        return $this->sourceKeys()
            ->where('is_active', true)
            ->sum('used_today');
    }
}
