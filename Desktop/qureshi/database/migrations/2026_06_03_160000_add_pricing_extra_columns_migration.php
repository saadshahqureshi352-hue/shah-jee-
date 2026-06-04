<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('pricing_plans', 'different_city_delivery')) {
                $table->decimal('different_city_delivery', 10, 2)->default(260)->after('base_delivery_charge');
            }
            if (!Schema::hasColumn('pricing_plans', 'same_city_delivery')) {
                $table->decimal('same_city_delivery', 10, 2)->default(170)->after('different_city_delivery');
            }
            if (!Schema::hasColumn('pricing_plans', 'return_charge')) {
                $table->decimal('return_charge', 10, 2)->default(150)->after('same_city_delivery');
            }
            if (!Schema::hasColumn('pricing_plans', 'additional_kg_rate')) {
                $table->decimal('additional_kg_rate', 10, 2)->default(150)->after('return_charge');
            }
        });

        Schema::table('courier_integrations', function (Blueprint $table) {
            if (!Schema::hasColumn('courier_integrations', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('courier_name');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'custom_return_charge')) {
                $table->decimal('custom_return_charge', 10, 2)->nullable()->after('pricing_plan_id');
            }
        });

        Schema::table('seller_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_invoices', 'period_start') && !Schema::hasColumn('seller_invoices', 'period_end')) {
                // Already exists
            }
            if (!Schema::hasColumn('seller_invoices', 'delivered_orders')) {
                $table->json('delivered_orders')->nullable()->after('period_end');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            if (Schema::hasColumn('pricing_plans', 'different_city_delivery')) {
                $table->dropColumn([
                    'different_city_delivery',
                    'same_city_delivery',
                    'return_charge',
                    'additional_kg_rate',
                ]);
            }
        });

        Schema::table('courier_integrations', function (Blueprint $table) {
            if (Schema::hasColumn('courier_integrations', 'logo_path')) {
                $table->dropColumn(['logo_path']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'custom_return_charge')) {
                $table->dropColumn(['custom_return_charge']);
            }
        });

        Schema::table('seller_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('seller_invoices', 'delivered_orders')) {
                $table->dropColumn(['delivered_orders']);
            }
        });
    }
};
