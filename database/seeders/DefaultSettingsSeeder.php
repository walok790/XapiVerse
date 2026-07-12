<?php

namespace Database\Seeders;

use App\Models\CreditPackage;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DefaultSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // General Settings
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'XapiVerse', 'type' => 'string', 'description' => 'Platform name'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Fast & Affordable APIs for Developers', 'type' => 'string', 'description' => 'Platform description'],
            ['group' => 'general', 'key' => 'site_version', 'value' => '1.0.0', 'type' => 'string', 'description' => 'Platform version'],
            ['group' => 'general', 'key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'description' => 'Enable maintenance mode'],

            // API Settings
            ['group' => 'api', 'key' => 'default_rate_limit', 'value' => '60', 'type' => 'integer', 'description' => 'Default rate limit per minute'],
            ['group' => 'api', 'key' => 'free_credits_on_signup', 'value' => '1000', 'type' => 'integer', 'description' => 'Free credits given to new developers'],
            ['group' => 'api', 'key' => 'max_keys_per_user', 'value' => '10', 'type' => 'integer', 'description' => 'Max API keys per developer'],
            ['group' => 'api', 'key' => 'key_auto_disable_errors', 'value' => '5', 'type' => 'integer', 'description' => 'Auto-disable source key after this many errors'],
            ['group' => 'api', 'key' => 'key_cooldown_seconds', 'value' => '60', 'type' => 'integer', 'description' => 'Cooldown seconds after a source key error'],

            // Rotation Settings
            ['group' => 'rotation', 'key' => 'default_strategy', 'value' => 'round_robin', 'type' => 'string', 'description' => 'Default rotation strategy for new services'],
            ['group' => 'rotation', 'key' => 'daily_reset_time', 'value' => '00:00', 'type' => 'string', 'description' => 'Time to reset daily usage counters (UTC)'],
            ['group' => 'rotation', 'key' => 'retry_attempts', 'value' => '3', 'type' => 'integer', 'description' => 'Number of source keys to try before failing'],

            // Payment Settings
            ['group' => 'payment', 'key' => 'currency', 'value' => 'USD', 'type' => 'string', 'description' => 'Default currency'],
            ['group' => 'payment', 'key' => 'min_purchase_amount', 'value' => '1.00', 'type' => 'string', 'description' => 'Minimum purchase amount'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Default Credit Packages
        $packages = [
            ['name' => 'Starter', 'price' => 1.00, 'credits' => 25000, 'description' => 'Perfect for testing', 'is_popular' => false, 'sort_order' => 1],
            ['name' => 'Developer', 'price' => 5.00, 'credits' => 150000, 'description' => 'For small projects', 'is_popular' => true, 'sort_order' => 2],
            ['name' => 'Business', 'price' => 20.00, 'credits' => 750000, 'description' => 'For production apps', 'is_popular' => false, 'sort_order' => 3],
            ['name' => 'Enterprise', 'price' => 100.00, 'credits' => 5000000, 'description' => 'High-volume usage', 'is_popular' => false, 'sort_order' => 4],
        ];

        foreach ($packages as $package) {
            CreditPackage::updateOrCreate(
                ['name' => $package['name']],
                array_merge($package, ['is_active' => true])
            );
        }
    }
}
