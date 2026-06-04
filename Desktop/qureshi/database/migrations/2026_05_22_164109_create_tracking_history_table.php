<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('status'); // pending, picked_up, in_transit, out_for_delivery, delivered, returned
            $table->string('location')->nullable();
            $table->text('remarks')->nullable();
            $table->string('updated_by')->nullable(); // system, courier_api, admin
            $table->timestamps();

            $table->index(['booking_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_history');
    }
};