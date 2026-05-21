<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedTinyInteger('quantity')->default(1)->after('weight');
            $table->string('second_phone')->nullable()->after('customer_phone');
            $table->text('consignee_address')->nullable()->after('destination_city');
            $table->string('product_name')->nullable()->after('reference_no');
            $table->text('special_instructions')->nullable()->after('product_name');
            $table->foreignId('pickup_address_id')->nullable()->after('courier_integration_id')
                ->constrained('pickup_addresses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['pickup_address_id']);
            $table->dropColumn(['quantity', 'second_phone', 'consignee_address', 'product_name', 'special_instructions', 'pickup_address_id']);
        });
    }
};
