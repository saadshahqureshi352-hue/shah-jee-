<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateWalletsTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('affiliate_wallets');

        // Kept for backward-compatibility with existing code; current dashboard uses columns on affiliates table.
        Schema::create('affiliate_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained('affiliates')->onDelete('cascade');
            $table->decimal('available_balance', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->decimal('lifetime_earnings', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['affiliate_id'], 'affiliate_wallets_unique_affiliate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_wallets');
    }
}

