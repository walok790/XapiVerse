<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run default settings
        $this->call(DefaultSettingsSeeder::class);

        // Create default admin (for development/testing)
        User::updateOrCreate(
            ['email' => 'admin@xapiverse.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create test developer
        User::updateOrCreate(
            ['email' => 'dev@xapiverse.com'],
            [
                'name' => 'Test Developer',
                'password' => Hash::make('password'),
                'role' => 'developer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create test user
        User::updateOrCreate(
            ['email' => 'user@xapiverse.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
