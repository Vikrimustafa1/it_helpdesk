<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table): void {
            $table->id();
            $table->string('kode_tiket', 20)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('unit');
            $table->enum('kategori', ['Hardware', 'Jaringan', 'SIMRS']);
            $table->text('deskripsi');
            $table->enum('tingkat_keparahan', ['Low', 'Medium', 'High', 'Critical'])->nullable();
            $table->enum('prioritas', ['Low', 'Medium', 'High', 'Urgent'])->nullable();
            $table->enum('metode_penanganan', ['Remote', 'Onsite'])->nullable();
            $table->enum('status', ['Open', 'Diproses', 'Selesai', 'Closed'])->default('Open');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->timestamp('sla_deadline')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

