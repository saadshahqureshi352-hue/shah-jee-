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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pricing_plan_id')
                ->nullable()
                ->constrained('pricing_plans')
                ->onDelete('set null');

            $table->decimal('custom_return_rate', 10, 2)->nullable();
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
            $table->string('business_name')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pricing_plan_id']);
            $table->dropColumn(['pricing_plan_id', 'custom_return_rate', 'status', 'business_name', 'city', 'phone']);
        });
    }
};