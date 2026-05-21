<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('id');
            $table->string('origin_city')->nullable()->after('destination_city');
            $table->string('reference_no')->nullable()->after('tracking_number');
            $table->string('service_type')->default('Regular')->after('status');
        });

        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM(
            'pending', 'dispatched', 'in_transit', 'out_for_delivery',
            'delivered', 'returned', 'cancelled', 'lost'
        ) NOT NULL DEFAULT 'pending'");

        foreach (DB::table('bookings')->whereNull('tracking_number')->pluck('id') as $id) {
            DB::table('bookings')->where('id', $id)->update([
                'tracking_number' => 'SJC'.str_pad((string) $id, 10, '0', STR_PAD_LEFT),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'origin_city', 'reference_no', 'service_type']);
        });

        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM(
            'pending', 'dispatched', 'delivered', 'returned'
        ) NOT NULL DEFAULT 'pending'");
    }
};
