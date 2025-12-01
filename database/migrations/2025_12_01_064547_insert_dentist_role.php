<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter the 'role' enum to add 'dentist'
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('patient','receptionist','admin','dentist') NOT NULL DEFAULT 'patient'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the 'role' enum back to original
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('patient','receptionist','admin') NOT NULL DEFAULT 'patient'");
    }
};
