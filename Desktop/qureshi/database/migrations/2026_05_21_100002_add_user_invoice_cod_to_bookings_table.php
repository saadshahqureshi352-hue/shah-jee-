<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->boolean('is_cod')->default(true)->after('cod_amount');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->after('user_id')->constrained('seller_invoices')->nullOnDelete();
        });

        $firstUserId = DB::table('users')->orderBy('id')->value('id');
        if ($firstUserId) {
            DB::table('bookings')->whereNull('user_id')->update(['user_id' => $firstUserId]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_cod', 'invoice_id']);
        });
    }
};
