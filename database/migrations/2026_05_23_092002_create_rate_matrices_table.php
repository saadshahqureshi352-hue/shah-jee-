<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_integration_id')->constrained()->cascadeOnDelete();
            $table->string('city_zone'); // e.g., "Karachi", "Lahore", "Islamabad"
            $table->string('weight_category'); // e.g., "0-500g", "501-1kg", "1-2kg", "2-5kg", "5+kg"
            $table->decimal('rate', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['courier_integration_id', 'city_zone', 'weight_category']);
            $table->index('courier_integration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_matrices');
    }
};
