<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun admin default jika belum ada
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name'     => 'Administrator',
                'email'    => 'adminitRSI',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'unit'     => 'Manajemen IT',
                'phone'    => null,
            ]);
        }
    }
}
