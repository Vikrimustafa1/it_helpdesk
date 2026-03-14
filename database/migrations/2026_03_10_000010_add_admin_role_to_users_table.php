<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan nilai 'admin' ke enum role pada tabel users.
     * MySQL enum harus di-ALTER secara manual karena Blueprint tidak bisa
     * memodifikasi enum yang sudah ada tanpa mengganti seluruh kolom.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user','teknisi_hardware','teknisi_software','admin') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        // Kembalikan ke enum semula (pastikan tidak ada user dengan role='admin' terlebih dahulu)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user') NOT NULL DEFAULT 'user'");
    }
};
