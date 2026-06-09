<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('affiliate_payouts');

        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained('affiliates')->onDelete('cascade');

            $table->decimal('amount', 15, 2);
            $table->string('method'); // jazzcash/easypaisa/bank (matches mockup)
            $table->string('transaction_id')->nullable();

            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');

            $table->timestamps();

            $table->index(['affiliate_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_payouts');
    }
};

