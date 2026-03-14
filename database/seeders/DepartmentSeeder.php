<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'IGD',        'description' => 'Instalasi Gawat Darurat'],
            ['name' => 'ICU',        'description' => 'Intensive Care Unit'],
            ['name' => 'Rawat Inap', 'description' => 'Unit Rawat Inap'],
            ['name' => 'Rawat Jalan','description' => 'Poliklinik / Rawat Jalan'],
            ['name' => 'Farmasi',    'description' => 'Instalasi Farmasi'],
            ['name' => 'Laboratorium','description' => 'Instalasi Laboratorium'],
            ['name' => 'Radiologi',  'description' => 'Instalasi Radiologi'],
            ['name' => 'Administrasi','description' => 'Bagian Administrasi & Umum'],
            ['name' => 'Keuangan',   'description' => 'Bagian Keuangan & Akuntansi'],
            ['name' => 'CSSD',       'description' => 'Central Sterile Supply Department'],
            ['name' => 'IT',         'description' => 'Departemen Teknologi Informasi'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }
    }
}
