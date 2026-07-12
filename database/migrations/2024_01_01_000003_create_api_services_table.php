<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // "TeraBox API"
            $table->string('slug')->unique();               // "terabox"
            $table->text('description')->nullable();
            $table->string('icon')->nullable();              // Icon path or class
            $table->string('base_url');                      // Default base URL for this service
            $table->string('version')->default('v1');
            $table->enum('rotation_strategy', [
                'round_robin',
                'priority',
                'least_used',
                'weighted',
                'fill_rotate'
            ])->default('round_robin');
            $table->integer('credits_per_request')->default(1); // How many credits to deduct per request
            $table->integer('rate_limit_per_minute')->default(60);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true);     // Show in marketplace
            $table->json('endpoints')->nullable();            // Available endpoints for this service
            $table->json('headers')->nullable();              // Default headers to send
            $table->text('documentation')->nullable();        // Markdown docs
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_services');
    }
};
