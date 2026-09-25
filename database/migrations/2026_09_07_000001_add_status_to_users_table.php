<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tambah status akun: PENDING (nunggu), ACTIVE (boleh login), REJECTED (ditolak)
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['PENDING', 'ACTIVE', 'REJECTED','INACTIVE'])->default('PENDING')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
