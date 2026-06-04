<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('connected')->default(false);
            $table->string('pair_stub', 64)->nullable();
            $table->timestamp('linked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_alert_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('shipper_alert')->nullable();
            $table->text('consignee_alert')->nullable();
            $table->boolean('notify_me_consent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_alert_templates');
        Schema::dropIfExists('whatsapp_profiles');
    }
};
