<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_integration_id')->constrained()->cascadeOnDelete();
            $table->string('key_name'); // e.g., "Trax Production", "Leopard Test"
            $table->text('api_key'); // encrypted
            $table->text('api_secret')->nullable(); // encrypted
            $table->text('account_id')->nullable(); // encrypted
            $table->text('account_title')->nullable(); // encrypted
            $table->enum('environment', ['production', 'testing'])->default('production');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index('courier_integration_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
