<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateTransactionsTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('affiliate_transactions');

        Schema::create('affiliate_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained('affiliates')->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['affiliate_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_transactions');
    }
}

