<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('color', 7)->default('#3b82f6'); // hex color
            $table->string('icon')->nullable();              // bootstrap-icons class
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};
