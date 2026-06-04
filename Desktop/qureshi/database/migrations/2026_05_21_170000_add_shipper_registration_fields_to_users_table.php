<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('email');
            $table->string('phone')->nullable()->after('username');
            $table->string('city')->nullable()->after('phone');
            $table->string('alternate_email')->nullable()->after('city');
            $table->string('alternate_city')->nullable()->after('alternate_email');
            $table->string('brand_name')->nullable()->after('owner_name');
            $table->string('father_name')->nullable()->after('brand_name');
            $table->string('cnic_or_passport')->nullable()->after('father_name');
            $table->string('home_address')->nullable()->after('cnic_or_passport');
            $table->date('date_of_birth')->nullable()->after('home_address');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('account_holder_name')->nullable()->after('wallet_account_title');
            $table->string('account_number')->nullable()->after('account_holder_name');
            $table->string('iban_number')->nullable()->after('account_number');
            $table->string('bank_name')->nullable()->after('iban_number');
            $table->string('payment_cycle')->default('twice_weekly')->after('bank_name');
            $table->string('cheque_photo_path')->nullable()->after('payment_cycle');
            $table->string('profile_photo_path')->nullable()->after('cheque_photo_path');
            $table->string('selfie_photo_path')->nullable()->after('profile_photo_path');
            $table->string('cnic_front_path')->nullable()->after('selfie_photo_path');
            $table->string('cnic_back_path')->nullable()->after('cnic_front_path');
            $table->json('business_photo_paths')->nullable()->after('cnic_back_path');
            $table->boolean('is_approved')->default(false)->after('business_photo_paths');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            $table->boolean('has_seen_welcome')->default(false)->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'phone', 'city', 'alternate_email', 'alternate_city',
                'brand_name', 'father_name', 'cnic_or_passport', 'home_address',
                'date_of_birth', 'gender', 'account_holder_name', 'account_number',
                'iban_number', 'bank_name', 'payment_cycle', 'cheque_photo_path',
                'profile_photo_path', 'selfie_photo_path', 'cnic_front_path',
                'cnic_back_path', 'business_photo_paths', 'is_approved', 'approved_at', 'has_seen_welcome'
            ]);
        });
    }
};