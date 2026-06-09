<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipperAffiliatesTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('shipper_affiliates');

        // Optional legacy mapping; current implementation uses users.referred_by.
        Schema::create('shipper_affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipper_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('affiliate_id')->constrained('affiliates')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['shipper_id', 'affiliate_id'], 'shipper_affiliates_unique_pair');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipper_affiliates');
    }
}

