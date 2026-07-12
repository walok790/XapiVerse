<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_key_import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();           // UUID for tracking
            $table->foreignId('api_service_id')->constrained('api_services')->onDelete('cascade');
            $table->foreignId('imported_by')->constrained('users')->onDelete('cascade');
            $table->enum('key_type', ['master', 'free', 'custom'])->default('free');
            $table->unsignedInteger('total_imported')->default(0);
            $table->unsignedInteger('total_failed')->default(0);
            $table->unsignedBigInteger('daily_limit_per_key')->nullable();
            $table->unsignedBigInteger('monthly_limit_per_key')->nullable();
            $table->unsignedInteger('priority')->default(5);
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_key_import_batches');
    }
};
