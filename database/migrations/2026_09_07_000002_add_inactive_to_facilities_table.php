<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Tambah status INACTIVE biar fasilitas bisa dinonaktifkan (disembunyikan dari publik)
    public function up(): void
    {
        DB::statement("ALTER TABLE facilities MODIFY status ENUM('AVAILABLE','MAINTENANCE','INACTIVE') DEFAULT 'AVAILABLE'");
    }

    public function down(): void        
    {
        DB::statement("ALTER TABLE facilities MODIFY status ENUM('AVAILABLE','MAINTENANCE') DEFAULT 'AVAILABLE'");
    }
};
