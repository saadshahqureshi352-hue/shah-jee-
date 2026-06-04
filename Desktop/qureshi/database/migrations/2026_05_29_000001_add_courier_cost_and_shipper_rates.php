<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add courier_cost to courier_rate_matrices so we can track profit per order
        Schema::table('courier_rate_matrices', function (Blueprint $table) {
            if (!Schema::hasColumn('courier_rate_matrices', 'courier_cost')) {
                $table->decimal('courier_cost', 10, 2)->default(0)->after('rate');
            }
            if (!Schema::hasColumn('courier_rate_matrices', 'shipper_charge')) {
                $table->decimal('shipper_charge', 10, 2)->default(0)->after('courier_cost');
            }
            if (!Schema::hasColumn('courier_rate_matrices', 'shipper_cod_percent')) {
                $table->decimal('shipper_cod_percent', 5, 2)->default(0)->after('cod_commission_percent');
            }
        });

        // Create shipper-specific rate overrides
        Schema::create('shipper_specific_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('courier_integration_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('courier_rate_matrix_id')->nullable()->constrained('courier_rate_matrices')->cascadeOnDelete();
            $table->decimal('custom_rate', 10, 2)->default(0);
            $table->decimal('custom_cod_percent', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'courier_rate_matrix_id']);
            $table->index('user_id');
            $table->index('courier_rate_matrix_id');
        });

        // Add courier_cost and profit columns to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'courier_cost')) {
                $table->decimal('courier_cost', 10, 2)->default(0)->after('delivery_charges');
            }
            if (!Schema::hasColumn('bookings', 'shipper_charge')) {
                $table->decimal('shipper_charge', 10, 2)->default(0)->after('courier_cost');
            }
            if (!Schema::hasColumn('bookings', 'profit')) {
                $table->decimal('profit', 10, 2)->default(0)->after('shipper_charge');
            }
            if (!Schema::hasColumn('bookings', 'cod_commission')) {
                $table->decimal('cod_commission', 10, 2)->default(0)->after('profit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['courier_cost', 'shipper_charge', 'profit', 'cod_commission']);
        });

        Schema::dropIfExists('shipper_specific_rates');

        Schema::table('courier_rate_matrices', function (Blueprint $table) {
            $table->dropColumn(['courier_cost', 'shipper_charge', 'shipper_cod_percent']);
        });
    }
};