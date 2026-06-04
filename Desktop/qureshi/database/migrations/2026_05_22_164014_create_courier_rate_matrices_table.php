<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_rate_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_integration_id')->constrained()->cascadeOnDelete();
            $table->string('weight_category'); // e.g., 0-0.5kg, 0.5-1kg, 1-2kg, 2-5kg, 5-10kg, 10+
            $table->decimal('weight_from', 8, 2)->default(0);
            $table->decimal('weight_to', 8, 2)->default(0);
            $table->string('zone')->nullable(); // local, regional, national
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('cod_commission_percent', 5, 2)->default(0);
            $table->decimal('fuel_surcharge_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_rate_matrices');
    }
};