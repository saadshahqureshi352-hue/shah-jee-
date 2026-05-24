<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cod_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_integration_id')->constrained()->cascadeOnDelete();
            $table->date('reconciliation_date');
            $table->decimal('reported_cash', 15, 2); // Cash courier claims to have collected
            $table->decimal('transferred_cash', 15, 2)->default(0); // Cash actually transferred to bank
            $table->decimal('variance', 15, 2)->nullable(); // difference = reported - transferred
            $table->integer('total_cod_shipments')->default(0);
            $table->integer('successful_deliveries')->default(0);
            $table->enum('status', ['pending', 'verified', 'discrepancy', 'resolved'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('courier_integration_id');
            $table->index('reconciliation_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cod_reconciliations');
    }
};
