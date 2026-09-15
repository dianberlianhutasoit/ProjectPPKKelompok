<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah data pemohon dan jumlah peserta pada reservasi.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('identity_number', 50)->after('facility_id');
            $table->integer('participants')->after('identity_number');
        });
    }

    /**
     * Hapus kembali kolom tambahan.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'identity_number',
                'participants',
            ]);
        });
    }
};