<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    use HasFactory;

    public $timestamps = false; // Only uses created_at

    protected $fillable = [
        'user_id',
        'user_api_key_id',
        'api_service_id',
        'api_source_key_id',
        'endpoint',
        'method',
        'status',
        'http_status_code',
        'response_time_ms',
        'credits_charged',
        'ip_address',
        'error_message',
        'request_params',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'request_params' => 'array',
            'created_at' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userApiKey()
    {
        return $this->belongsTo(UserApiKey::class);
    }

    public function apiService()
    {
        return $this->belongsTo(ApiService::class);
    }

    public function sourceKey()
    {
        return $this->belongsTo(ApiSourceKey::class, 'api_source_key_id');
    }

    // ─── Helpers ───────────────────────────────────────────────

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function getStatusBadge(): string
    {
        return match($this->status) {
            'success' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'rate_limited' => 'bg-yellow-100 text-yellow-800',
            'no_credits' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
