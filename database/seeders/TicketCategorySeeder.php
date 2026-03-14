<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketCategory;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hardware', 'color' => '#3b82f6', 'icon' => 'bi-pc-display'],
            ['name' => 'Software', 'color' => '#8b5cf6', 'icon' => 'bi-code-square'],
        ];

        foreach ($categories as $cat) {
            TicketCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
