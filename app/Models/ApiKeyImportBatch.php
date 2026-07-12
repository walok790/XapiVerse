<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKeyImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'api_service_id',
        'imported_by',
        'key_type',
        'total_imported',
        'total_failed',
        'daily_limit_per_key',
        'monthly_limit_per_key',
        'priority',
        'status',
        'notes',
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function service()
    {
        return $this->belongsTo(ApiService::class, 'api_service_id');
    }

    public function importedByUser()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function keys()
    {
        return $this->hasMany(ApiSourceKey::class, 'import_batch_id', 'batch_id');
    }
}
