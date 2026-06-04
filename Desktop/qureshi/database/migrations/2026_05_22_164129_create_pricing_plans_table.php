<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., VIP, Standard, Premium
            $table->text('description')->nullable();
            $table->decimal('base_delivery_charge', 10, 2)->default(0);
            $table->decimal('cod_commission_percent', 5, 2)->default(0);
            $table->decimal('weight_charge_per_kg', 10, 2)->default(0);
            $table->decimal('fuel_surcharge_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};