<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('affiliate_commissions_ledger');

        Schema::create('affiliate_commissions_ledger', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('affiliate_id');

            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'clear'])->default('pending');

            $table->timestamps();

            $table->index(['affiliate_id', 'status']);
            $table->index(['merchant_id', 'status']);

            // If these tables exist, attach FKs. During migrate:fresh, they may exist in correct order.
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            // bookings & users are the backing sources for merchant/order.
            $table->foreign('order_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['order_id', 'affiliate_id'], 'affiliate_commissions_unique_order_affiliate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions_ledger');
    }
};

