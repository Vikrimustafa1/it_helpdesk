<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: temporarily convert to VARCHAR to allow free data changes
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'user'");

        // Step 2: migrate existing it_support users to teknisi_software
        DB::statement("UPDATE users SET role = 'teknisi_software' WHERE role = 'it_support'");

        // Step 3: apply final ENUM without it_support
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user','teknisi_hardware','teknisi_software','admin') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        // Restore it_support to ENUM (does not restore existing users)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user','it_support','teknisi_hardware','teknisi_software','admin') NOT NULL DEFAULT 'user'");
    }
};
