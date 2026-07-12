<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');                          // "My Project Key"
            $table->string('api_key', 64)->unique();        // "xv_live_a8f3k2..."
            $table->string('prefix', 10);                   // "xv_live_" for quick lookup
            $table->bigInteger('credits_balance')->default(0);
            $table->bigInteger('total_used')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('allowed_services')->nullable();    // ["terabox", "twitter"] or null for all
            $table->integer('rate_limit_per_minute')->default(60);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['api_key', 'is_active']);
            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_api_keys');
    }
};
