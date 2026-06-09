<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatesTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('affiliates');

        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            $table->string('referral_code')->unique();

            $table->decimal('available_wallet', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->decimal('lifetime_earnings', 15, 2)->default(0);
            $table->decimal('total_paid_out', 15, 2)->default(0);

            $table->string('jazzcash_number')->nullable();
            $table->string('easypaisa_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();

            $table->enum('status', ['active', 'suspended', 'deleted'])->default('active');

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
}

