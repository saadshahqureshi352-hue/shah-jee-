<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            // Overnight (base up to 1 KG, additional per extra KG)
            $table->decimal('overnight_base_rate', 10, 2)->default(0)->after('same_city_rate');
            $table->decimal('overnight_additional_rate', 10, 2)->default(0)->after('overnight_base_rate');

            // Detain (base up to 1 KG, additional per extra KG)
            $table->decimal('detain_base_rate', 10, 2)->default(0)->after('overnight_additional_rate');
            $table->decimal('detain_additional_rate', 10, 2)->default(0)->after('detain_base_rate');

            // Overland (base up to 1 KG, additional per extra KG)
            $table->decimal('overland_base_rate', 10, 2)->default(0)->after('detain_additional_rate');
            $table->decimal('overland_additional_rate', 10, 2)->default(0)->after('overland_base_rate');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn([
                'overnight_base_rate',
                'overnight_additional_rate',
                'detain_base_rate',
                'detain_additional_rate',
                'overland_base_rate',
                'overland_additional_rate',
            ]);
        });
    }
};
