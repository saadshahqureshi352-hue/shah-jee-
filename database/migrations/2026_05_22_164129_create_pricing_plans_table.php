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
            $table->string('name'); // Basic, Standard, VIP
            $table->decimal('diff_city_rate', 10, 2);
            $table->decimal('same_city_rate', 10, 2);
            $table->decimal('additional_kg_rate', 10, 2);
            $table->decimal('return_rate', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};