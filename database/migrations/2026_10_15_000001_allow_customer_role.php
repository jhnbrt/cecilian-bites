<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        // On MySQL the original role column may be ENUM; widen it safely.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','cashier','customer') NOT NULL DEFAULT 'customer'");
        }
    }
    public function down(): void {
        // Keep customer roles on rollback to avoid destructive data loss.
    }
};
