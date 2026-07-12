<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'credits',
        'description',
        'is_popular',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // ─── Helpers ───────────────────────────────────────────────

    public function getFormattedPrice(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedCredits(): string
    {
        return number_format($this->credits);
    }

    public function getPricePerRequest(): float
    {
        if ($this->credits <= 0) return 0;
        return $this->price / $this->credits;
    }
}
