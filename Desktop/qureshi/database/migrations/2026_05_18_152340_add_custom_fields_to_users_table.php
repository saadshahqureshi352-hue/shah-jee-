<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('owner_name')->nullable()->after('company_name');
            $table->string('business_type')->nullable()->after('owner_name');
            $table->decimal('fixed_delivery_charges', 8, 2)->default(200.00)->after('business_type');
            $table->string('wallet_method')->nullable()->after('fixed_delivery_charges');
            $table->string('wallet_account_no')->nullable()->after('wallet_method');
            $table->string('wallet_account_title')->nullable()->after('wallet_account_no');
            $table->boolean('is_profile_locked')->default(false)->after('wallet_account_title');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'owner_name',
                'business_type',
                'fixed_delivery_charges',
                'wallet_method',
                'wallet_account_no',
                'wallet_account_title',
                'is_profile_locked'
            ]);
        });
    }
};