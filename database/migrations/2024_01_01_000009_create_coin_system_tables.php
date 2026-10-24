<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Coin packages available to purchase
        Schema::create('coin_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('price_vnd');
            $table->unsignedInteger('coins_awarded');
            $table->unsignedInteger('bonus_coins')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Transactions log for buying coins
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('amount_vnd');
            $table->unsignedInteger('coins');
            $table->string('payment_method')->default('momo'); // momo, vnpay, qr_bank
            $table->string('transaction_code')->unique();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });

        // 3. Record of which chapters a user has unlocked
        Schema::create('unlocked_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->unsignedInteger('coins_spent');
            $table->timestamp('unlocked_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'chapter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unlocked_chapters');
        Schema::dropIfExists('coin_transactions');
        Schema::dropIfExists('coin_packages');
    }
};
