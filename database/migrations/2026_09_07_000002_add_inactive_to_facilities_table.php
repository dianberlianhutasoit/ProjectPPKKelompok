<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tambah status INACTIVE pada fasilitas
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('status')
                ->default('AVAILABLE')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('status')
                ->default('AVAILABLE')
                ->change();
        });
    }
};