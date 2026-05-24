<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Prevent duplicate-column error if table already has this column
            if (!Schema::hasColumn('bookings', 'delivery_charges')) {
                $table->decimal('delivery_charges', 10, 2)->default(0)->after('cod_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('delivery_charges');
        });
    }
};
