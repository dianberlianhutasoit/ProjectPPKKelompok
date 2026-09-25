<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Longgarkan users.status dari enum menjadi string agar INACTIVE didukung
    // di semua driver (MySQL enum + SQLite CHECK constraint menolak nilai baru).
    // Sama pola dengan migration INACTIVE pada tabel facilities.
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('PENDING')->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `status` ENUM('PENDING', 'ACTIVE', 'REJECTED','INACTIVE') NOT NULL DEFAULT 'PENDING'");
        }
    }
};
