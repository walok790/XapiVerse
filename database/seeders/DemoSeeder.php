<?php

namespace Database\Seeders;

use App\Models\ApiService;
use App\Models\ApiSourceKey;
use App\Models\User;
use App\Models\UserApiKey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Seed demo data for demonstration mode.
     */
    public function run(): void
    {
        // Create demo users
        $admin = User::updateOrCreate(
            ['email' => 'admin@xapiverse.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $developer = User::updateOrCreate(
            ['email' => 'dev@xapiverse.com'],
            [
                'name' => 'Demo Developer',
                'password' => Hash::make('password'),
                'role' => 'developer',
                'is_active' => true,
                'email_verified_at' => now(),
                'company' => 'Demo Company',
                'website' => 'https://example.com',
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@xapiverse.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create developer API key
        UserApiKey::updateOrCreate(
            ['user_id' => $developer->id, 'name' => 'Demo Key'],
            [
                'api_key' => 'xv_live_demo_key_123456789abcdef0123456789abcdef',
                'prefix' => 'xv_live_',
                'credits_balance' => 50000,
                'total_used' => 1250,
                'is_active' => true,
                'rate_limit_per_minute' => 60,
            ]
        );

        // Create demo API services
        $terabox = ApiService::updateOrCreate(
            ['slug' => 'terabox'],
            [
                'name' => 'TeraBox API',
                'description' => 'Get download links, HLS streaming up to 4K, multi-language subtitles, and file metadata from TeraBox links.',
                'base_url' => 'https://api.example.com/terabox',
                'version' => 'v1',
                'rotation_strategy' => 'round_robin',
                'credits_per_request' => 1,
                'rate_limit_per_minute' => 60,
                'is_active' => true,
                'is_public' => true,
                'endpoints' => [
                    ['method' => 'POST', 'path' => '/download', 'description' => 'Get download link'],
                    ['method' => 'POST', 'path' => '/stream', 'description' => 'Get HLS streaming URL'],
                    ['method' => 'POST', 'path' => '/info', 'description' => 'Get file metadata'],
                ],
                'sort_order' => 1,
            ]
        );

        $twitter = ApiService::updateOrCreate(
            ['slug' => 'twitter'],
            [
                'name' => 'X (Twitter) API',
                'description' => 'Extract media, engagement stats, and author info from X (Twitter) tweets.',
                'base_url' => 'https://api.example.com/twitter',
                'version' => 'v1',
                'rotation_strategy' => 'least_used',
                'credits_per_request' => 1,
                'rate_limit_per_minute' => 30,
                'is_active' => true,
                'is_public' => true,
                'endpoints' => [
                    ['method' => 'POST', 'path' => '/media', 'description' => 'Extract media from tweet'],
                    ['method' => 'POST', 'path' => '/info', 'description' => 'Get tweet information'],
                ],
                'sort_order' => 2,
            ]
        );

        $instagram = ApiService::updateOrCreate(
            ['slug' => 'instagram'],
            [
                'name' => 'Instagram API',
                'description' => 'Download reels, stories, posts and profile information from Instagram.',
                'base_url' => 'https://api.example.com/instagram',
                'version' => 'v1',
                'rotation_strategy' => 'priority',
                'credits_per_request' => 2,
                'rate_limit_per_minute' => 20,
                'is_active' => false,
                'is_public' => true,
                'endpoints' => [
                    ['method' => 'POST', 'path' => '/reels', 'description' => 'Download reels'],
                    ['method' => 'POST', 'path' => '/posts', 'description' => 'Download posts'],
                    ['method' => 'POST', 'path' => '/profile', 'description' => 'Get profile info'],
                ],
                'sort_order' => 3,
            ]
        );

        // Create demo source keys for TeraBox
        for ($i = 1; $i <= 10; $i++) {
            ApiSourceKey::create([
                'api_service_id' => $terabox->id,
                'key_type' => $i <= 2 ? 'master' : 'free',
                'api_key' => 'demo_terabox_key_' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'daily_limit' => $i <= 2 ? 10000 : 100,
                'monthly_limit' => $i <= 2 ? 300000 : 3000,
                'used_today' => rand(0, $i <= 2 ? 5000 : 80),
                'used_this_month' => rand(100, $i <= 2 ? 150000 : 2000),
                'used_total' => rand(1000, 50000),
                'priority' => $i <= 2 ? 1 : 5,
                'weight' => $i <= 2 ? 80 : 50,
                'is_active' => true,
                'is_exhausted' => false,
                'success_count' => rand(500, 10000),
                'avg_response_time_ms' => rand(100, 800) / 10,
            ]);
        }

        // Create demo source keys for Twitter
        for ($i = 1; $i <= 5; $i++) {
            ApiSourceKey::create([
                'api_service_id' => $twitter->id,
                'key_type' => $i <= 1 ? 'master' : 'free',
                'api_key' => 'demo_twitter_key_' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'daily_limit' => $i <= 1 ? 5000 : 50,
                'used_today' => rand(0, $i <= 1 ? 2000 : 40),
                'used_total' => rand(500, 20000),
                'priority' => $i <= 1 ? 1 : 5,
                'is_active' => true,
                'is_exhausted' => false,
            ]);
        }
    }
}
