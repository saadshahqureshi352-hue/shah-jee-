<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('destination_city');
            $table->decimal('weight', 8, 2)->default(0.5);
            $table->decimal('cod_amount', 10, 2);
            $table->decimal('delivery_charges', 8, 2)->default(200.00);
            
            // Yeh column missing tha, ab add kar diya hai
            $table->foreignId('courier_integration_id')->nullable()->constrained()->onDelete('set null');
            
            $table->enum('status', ['pending', 'dispatched', 'delivered', 'returned'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};