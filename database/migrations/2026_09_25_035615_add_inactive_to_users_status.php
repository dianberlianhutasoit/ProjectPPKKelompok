<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Use a string so INACTIVE works across database drivers.
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
