<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alter kategori from ENUM to VARCHAR to allow 'Software'
        DB::statement("ALTER TABLE tickets MODIFY COLUMN `kategori` VARCHAR(100) NULL");

        // 2. Migrate old category names on tickets table
        DB::table('tickets')
            ->whereIn('kategori', ['Jaringan', 'SIMRS'])
            ->update(['kategori' => 'Software']);

        // 3. Add Software to ticket_categories if not exists
        $exists = DB::table('ticket_categories')->where('name', 'Software')->exists();
        if (!$exists) {
            DB::table('ticket_categories')->insert([
                'name'       => 'Software',
                'color'      => '#8b5cf6',
                'icon'       => 'bi-code-square',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Get Software category id and update ticket_category_id for old Jaringan/SIMRS
        $softwareId = DB::table('ticket_categories')->where('name', 'Software')->value('id');

        if ($softwareId) {
            $oldIds = DB::table('ticket_categories')
                ->whereIn('name', ['Jaringan', 'SIMRS'])
                ->pluck('id');

            if ($oldIds->isNotEmpty()) {
                DB::table('tickets')
                    ->whereIn('ticket_category_id', $oldIds)
                    ->update(['ticket_category_id' => $softwareId]);
            }
        }

        // 5. Remove old categories Jaringan & SIMRS
        DB::table('ticket_categories')->whereIn('name', ['Jaringan', 'SIMRS'])->delete();
    }

    public function down(): void
    {
        // Restore Jaringan & SIMRS categories
        foreach (['Jaringan' => ['#22c55e', 'bi-wifi'], 'SIMRS' => ['#f59e0b', 'bi-hospital']] as $name => $data) {
            $exists = DB::table('ticket_categories')->where('name', $name)->exists();
            if (!$exists) {
                DB::table('ticket_categories')->insert([
                    'name'       => $name,
                    'color'      => $data[0],
                    'icon'       => $data[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Restore ENUM (best effort — Software tickets become null)
        DB::statement("ALTER TABLE tickets MODIFY COLUMN `kategori` ENUM('Hardware','Jaringan','SIMRS') NULL");
    }
};
