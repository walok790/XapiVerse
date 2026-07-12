<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_source_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_service_id')->constrained('api_services')->onDelete('cascade');
            $table->enum('key_type', ['master', 'free', 'custom'])->default('free');
            $table->text('api_key');                          // The actual source API key (encrypted)
            $table->string('base_url_override')->nullable();  // Override service base URL
            $table->json('headers_override')->nullable();     // Override default headers
            $table->string('label')->nullable();              // Admin label/note

            // Limits
            $table->unsignedBigInteger('daily_limit')->nullable();      // Max requests per day
            $table->unsignedBigInteger('monthly_limit')->nullable();    // Max requests per month
            $table->unsignedBigInteger('total_limit')->nullable();      // Lifetime max requests

            // Usage counters
            $table->unsignedBigInteger('used_today')->default(0);
            $table->unsignedBigInteger('used_this_month')->default(0);
            $table->unsignedBigInteger('used_total')->default(0);

            // Rotation config
            $table->unsignedInteger('priority')->default(5);   // 1=highest, 10=lowest
            $table->unsignedInteger('weight')->default(50);    // For weighted rotation (1-100)

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_exhausted')->default(false);   // Daily limit reached
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('cooldown_until')->nullable();    // Don't use until this time

            // Health tracking
            $table->string('last_error')->nullable();
            $table->unsignedInteger('error_count')->default(0);
            $table->unsignedInteger('success_count')->default(0);
            $table->float('avg_response_time_ms')->default(0);

            // Meta
            $table->text('notes')->nullable();
            $table->string('import_batch_id')->nullable();     // Track bulk imports
            $table->timestamps();

            // Indexes for rotation queries
            $table->index(['api_service_id', 'is_active', 'is_exhausted']);
            $table->index(['api_service_id', 'priority', 'last_used_at']);
            $table->index(['api_service_id', 'used_today']);
            $table->index('import_batch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_source_keys');
    }
};
