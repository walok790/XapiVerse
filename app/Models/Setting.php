<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    // ─── Static Helpers ────────────────────────────────────────

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember('setting_' . $key, 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) return $default;

        return match($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'string'): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        self::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'group' => $group, 'type' => $type]
        );

        Cache::forget('setting_' . $key);
    }

    /**
     * Get all settings for a group
     */
    public static function getGroup(string $group): array
    {
        return self::where('group', $group)->pluck('value', 'key')->toArray();
    }
}
