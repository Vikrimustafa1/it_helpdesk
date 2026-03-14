<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketProgress;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder master data dan admin
        $this->call([
            DepartmentSeeder::class,
            TicketCategorySeeder::class,
            AdminSeeder::class,
        ]);

        // Teknisi Software account
        $teknisi = User::create([
            'name'     => 'Danang',
            'email'    => 'it123',
            'password' => Hash::make('it123'),
            'role'     => 'teknisi_software',
            'unit'     => 'IT',
            'phone'    => '0800000000',
        ]);

        // User accounts
        $user1 = User::create([
            'name'     => 'Dr. Bintang',
            'email'    => '1234567890',
            'password' => Hash::make('1234567890'),
            'role'     => 'user',
            'unit'     => 'Rawat Inap',
            'phone'    => '0811111111',
        ]);

        $user2 = User::create([
            'name'     => 'User Dua',
            'email'    => 'user2@rs.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'unit'     => 'IGD',
            'phone'    => '0822222222',
        ]);

        $users = [$user1, $user2];

        $statuses = ['Open', 'Diproses', 'Selesai', 'Closed'];
        $kategoris = ['Hardware', 'Software'];
        $severities = [null, 'Low', 'Medium', 'High', 'Critical'];
        $priorities = [null, 'Low', 'Medium', 'High', 'Urgent'];
        $methods = [null, 'Remote', 'Onsite'];

        $now = Carbon::now();

        for ($i = 1; $i <= 30; $i++) {
            $user = $users[array_rand($users)];
            $status = $statuses[array_rand($statuses)];
            $kategori = $kategoris[array_rand($kategoris)];
            $severity = $severities[array_rand($severities)];
            $priority = $priorities[array_rand($priorities)];
            $method = $methods[array_rand($methods)];

            $createdAt = $now->copy()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $ticket = new Ticket();
            $ticket->kode_tiket = Ticket::generateKodeTiket();
            $ticket->user_id = $user->id;
            $ticket->unit = $user->unit ?? 'Unit Lain';
            $ticket->kategori = $kategori;
            $ticket->deskripsi = 'Contoh masalah ' . $kategori . ' ke-' . $i;
            $ticket->status = $status;
            $ticket->tingkat_keparahan = $severity;
            $ticket->prioritas = $priority;
            $ticket->metode_penanganan = $method;
            $ticket->created_at = $createdAt;
            $ticket->updated_at = $createdAt;

            if (in_array($status, ['Diproses', 'Selesai', 'Closed'], true)) {
                $start = $createdAt->copy()->addMinutes(rand(5, 60));
                $ticket->waktu_mulai = $start;
                $ticket->handled_by = $teknisi->id;

                if (in_array($status, ['Selesai', 'Closed'], true)) {
                    $end = $start->copy()->addMinutes(rand(15, 240));
                    $ticket->waktu_selesai = $end;
                    $ticket->durasi_menit = $start->diffInMinutes($end);
                    $ticket->updated_at = $end;
                }
            }

            if ($severity !== null) {
                $ticket->setSlaDeadline();
            }

            $ticket->save();

            // Some tickets with progress entries
            $progressCount = rand(0, 3);
            for ($p = 1; $p <= $progressCount; $p++) {
                $progressTime = $ticket->created_at->copy()->addMinutes($p * rand(10, 60));

                TicketProgress::create([
                    'ticket_id'  => $ticket->id,
                    'catatan'    => 'Catatan progress ke-' . $p . ' untuk tiket ' . $ticket->kode_tiket,
                    'foto'       => null,
                    'updated_by' => $teknisi->id,
                    'created_at' => $progressTime,
                    'updated_at' => $progressTime,
                ]);
            }
        }

        // Jalankan seeder tiket 2025
        $this->call([
            TicketSeeder2025::class,
        ]);
    }
}
