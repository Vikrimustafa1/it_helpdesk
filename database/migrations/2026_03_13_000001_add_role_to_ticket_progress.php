<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_progress', function (Blueprint $table) {
            // 'user' = catatan dari pelapor, null = catatan teknisi
            $table->string('role')->nullable()->after('updated_by');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_progress', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
