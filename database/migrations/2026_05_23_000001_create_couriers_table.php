<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo_path')->nullable();
            $table->decimal('courier_rate', 10, 2); // rate paid to courier per order
            $table->decimal('merchant_rate', 10, 2); // rate charged to merchant per order
            $table->decimal('profit_per_order', 10, 2)->default(0);
            $table->enum('status', ['active', 'off'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};