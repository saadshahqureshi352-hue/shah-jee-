<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pricing_plan_id')->nullable()->after('is_profile_locked')
                ->constrained('pricing_plans')->nullOnDelete();
            $table->decimal('wallet_balance', 12, 2)->default(0)->after('payment_cycle');
            $table->boolean('wallet_blocked')->default(false)->after('wallet_balance');
            $table->string('account_status')->default('pending')->after('wallet_blocked'); // pending, active, suspended, blocked
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pricing_plan_id']);
            $table->dropColumn(['pricing_plan_id', 'wallet_balance', 'wallet_blocked', 'account_status']);
        });
    }
};