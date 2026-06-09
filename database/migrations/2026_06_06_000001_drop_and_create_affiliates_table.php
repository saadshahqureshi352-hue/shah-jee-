<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // No-op: `php artisan migrate:fresh` already rebuilds the affiliate schema
        // via the authoritative overwrite migrations (2026_06_05_*).
    }

    public function down(): void
    {
        // Keep no-op to avoid breaking foreign key constraints during rollback.
    }
};

