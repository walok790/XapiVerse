<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'avatar',
        'bio',
        'company',
        'website',
        'total_credits_purchased',
        'total_credits_used',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function apiKeys()
    {
        return $this->hasMany(UserApiKey::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function requestLogs()
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    // ─── Helpers ───────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function getTotalCreditsBalance(): int
    {
        return $this->apiKeys()->sum('credits_balance');
    }

    public function getActiveApiKeysCount(): int
    {
        return $this->apiKeys()->where('is_active', true)->count();
    }
}
