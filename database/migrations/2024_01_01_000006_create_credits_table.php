<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Credit packages available for purchase
        Schema::create('credit_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // "Starter Pack"
            $table->decimal('price', 10, 2);            // $1.00
            $table->bigInteger('credits');               // 25000
            $table->text('description')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Transaction history
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('transaction_id')->unique();  // Unique reference
            $table->enum('type', ['purchase', 'bonus', 'refund', 'admin_credit', 'admin_debit']);
            $table->bigInteger('credits');               // Amount of credits
            $table->decimal('amount', 10, 2)->default(0); // Money amount (if purchase)
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method')->nullable(); // "stripe", "paypal", "crypto"
            $table->string('payment_id')->nullable();     // External payment reference
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();             // Additional payment data
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('credit_packages');
    }
};
