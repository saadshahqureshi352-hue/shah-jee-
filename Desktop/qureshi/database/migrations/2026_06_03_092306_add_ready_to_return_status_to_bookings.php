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
        // bookings.status currently uses an enum with limited values.
        // We recreate the enum with the new statuses required by the admin dashboard.
        DB::statement("
            ALTER TABLE bookings
            MODIFY status ENUM('pending','picked_up','dispatched','in_transit','out_for_delivery','delivered','returned','cancelled','issue','ready_to_return','return_confirmed')
            NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE bookings
            MODIFY status ENUM('pending','picked_up','dispatched','in_transit','out_for_delivery','delivered','returned','cancelled','issue')
            NOT NULL DEFAULT 'pending'
        ");
    }
};
