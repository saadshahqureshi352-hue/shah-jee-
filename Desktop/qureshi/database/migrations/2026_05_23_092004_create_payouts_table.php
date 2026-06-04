<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('payout_reference')->unique(); // e.g., PAYOUT-2026-05-001
            $table->decimal('gross_amount', 15, 2); // Total earnings
            $table->decimal('commissions_deducted', 15, 2)->default(0); // Our commission
            $table->decimal('other_charges', 15, 2)->default(0); // Late fees, etc.
            $table->decimal('net_amount', 15, 2); // What merchant receives
            $table->dateTime('period_start');
            $table->dateTime('period_end');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['bank_transfer', 'check', 'mobile_wallet'])->default('bank_transfer');
            $table->timestamp('paid_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
