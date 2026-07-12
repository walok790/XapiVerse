<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'type',
        'credits',
        'amount',
        'currency',
        'payment_method',
        'payment_id',
        'status',
        'notes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'amount' => 'decimal:2',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ───────────────────────────────────────────────

    public function isPurchase(): bool
    {
        return $this->type === 'purchase';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getFormattedAmount(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getFormattedCredits(): string
    {
        return number_format($this->credits);
    }
}
