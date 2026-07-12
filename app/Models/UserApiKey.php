<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UserApiKey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'api_key',
        'prefix',
        'credits_balance',
        'total_used',
        'is_active',
        'allowed_services',
        'rate_limit_per_minute',
        'last_used_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'allowed_services' => 'array',
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestLogs()
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    // ─── Helpers ───────────────────────────────────────────────

    /**
     * Generate a new unique API key
     */
    public static function generateKey(string $type = 'live'): string
    {
        $prefix = 'xv_' . $type . '_';
        $key = $prefix . Str::random(40);
        
        // Ensure uniqueness
        while (self::where('api_key', $key)->exists()) {
            $key = $prefix . Str::random(40);
        }

        return $key;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasCredits(): bool
    {
        return $this->credits_balance > 0;
    }

    public function canAccessService(string $serviceSlug): bool
    {
        // If no restrictions, allow all
        if (empty($this->allowed_services)) {
            return true;
        }

        return in_array($serviceSlug, $this->allowed_services);
    }

    public function getMaskedKey(): string
    {
        return substr($this->api_key, 0, 12) . '...' . substr($this->api_key, -6);
    }
}
