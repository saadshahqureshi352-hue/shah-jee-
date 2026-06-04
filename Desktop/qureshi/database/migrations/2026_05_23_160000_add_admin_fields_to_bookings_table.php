<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'pickup_date')) {
                $table->date('pickup_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('bookings', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('bookings', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('pickup_date');
            }
            if (!Schema::hasColumn('bookings', 'remarks')) {
                $table->text('remarks')->nullable()->after('delivered_at');
            }
            if (!Schema::hasColumn('bookings', 'description')) {
                $table->string('description')->nullable()->after('consignee_address');
            }
        });

        Schema::table('tracking_history', function (Blueprint $table) {
            if (!Schema::hasColumn('tracking_history', 'description')) {
                $table->text('description')->nullable()->after('location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_date', 'customer_address', 'delivered_at', 'remarks', 'description',
            ]);
        });

        Schema::table('tracking_history', function (Blueprint $table) {
            $table->dropColumn(['description']);
        });
    }
};