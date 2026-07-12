<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_api_key_id')->nullable()->constrained('user_api_keys')->nullOnDelete();
            $table->foreignId('api_service_id')->nullable()->constrained('api_services')->nullOnDelete();
            $table->foreignId('api_source_key_id')->nullable()->constrained('api_source_keys')->nullOnDelete();

            $table->string('endpoint');                    // "/terabox/download"
            $table->string('method', 10)->default('POST');
            $table->enum('status', ['success', 'failed', 'rate_limited', 'no_credits']);
            $table->integer('http_status_code')->nullable();
            $table->float('response_time_ms')->nullable();
            $table->integer('credits_charged')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('error_message')->nullable();
            $table->json('request_params')->nullable();    // Sanitized request params
            $table->timestamp('created_at')->useCurrent();

            // Indexes for analytics queries
            $table->index(['user_id', 'created_at']);
            $table->index(['api_service_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_request_logs');
    }
};
