<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Cek dan hapus jika nama kolomnya identity_number
            if (Schema::hasColumn('reservations', 'identity_number')) {
                $table->dropColumn('identity_number');
            }

            // Cek dan hapus jika nama kolomnya nim_nip
            if (Schema::hasColumn('reservations', 'nim_nip')) {
                $table->dropColumn('nim_nip');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // $table->string('nim_nip')->nullable();
        });
    }
};