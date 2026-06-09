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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('merchant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('courier_id')->constrained('couriers')->onDelete('set null')->nullable();
            $table->decimal('cod_amount', 12, 2);
            $table->decimal('delivery_charges', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->enum('status', [
                'booked',
                'dispatched',
                'delivered',
                'in_transit',
                'issue_detected',
                'ready_to_return',
                'return_confirmed',
                'returned',
            ])->default('booked');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->json('customer_address')->nullable(); // contains city etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};