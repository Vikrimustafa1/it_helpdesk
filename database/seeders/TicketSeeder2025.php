<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketProgress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder2025 extends Seeder
{
    public function run(): void
    {
        $itSupport = User::whereIn('role', ['teknisi_hardware', 'teknisi_software'])->first();
        $users     = User::where('role', 'user')->get();

        if (!$itSupport || $users->isEmpty()) {
            $this->command->warn('Tidak ada user/teknisi. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $kategoris  = ['Hardware', 'Software'];
        $statuses   = ['Open', 'Diproses', 'Selesai', 'Closed'];
        $severities = ['Low', 'Medium', 'High', 'Critical'];
        $priorities = ['Low', 'Medium', 'High', 'Urgent'];
        $methods    = ['Remote', 'Onsite'];

        // 15 tiket tersebar di bulan Jan–Nov 2025
        $months = [1, 1, 2, 3, 4, 4, 5, 6, 7, 8, 8, 9, 10, 10, 11];

        foreach ($months as $i => $month) {
            $user     = $users->random();
            $status   = $statuses[array_rand($statuses)];
            $kategori = $kategoris[array_rand($kategoris)];
            $severity = $severities[array_rand($severities)];
            $priority = $priorities[array_rand($priorities)];
            $method   = $methods[array_rand($methods)];

            $createdAt = Carbon::create(2025, $month, rand(1, 20), rand(8, 16), rand(0, 59));

            $ticket = new Ticket();
            $ticket->kode_tiket         = Ticket::generateKodeTiket();
            $ticket->user_id            = $user->id;
            $ticket->unit               = $user->unit ?? 'Unit Lain';
            $ticket->kategori           = $kategori;
            $ticket->deskripsi          = 'Data dummy 2025 — masalah ' . $kategori . ' #' . ($i + 1);
            $ticket->status             = $status;
            $ticket->tingkat_keparahan  = $severity;
            $ticket->prioritas          = $priority;
            $ticket->metode_penanganan  = $method;
            $ticket->created_at         = $createdAt;
            $ticket->updated_at         = $createdAt;

            if (in_array($status, ['Diproses', 'Selesai', 'Closed'])) {
                $start = $createdAt->copy()->addMinutes(rand(10, 90));
                $ticket->waktu_mulai = $start;
                $ticket->handled_by  = $itSupport->id;

                if (in_array($status, ['Selesai', 'Closed'])) {
                    $end = $start->copy()->addMinutes(rand(20, 180));
                    $ticket->waktu_selesai = $end;
                    $ticket->durasi_menit  = $start->diffInMinutes($end);
                    $ticket->updated_at    = $end;
                }
            }

            $ticket->setSlaDeadline();
            $ticket->save();

            // 1–2 progress notes
            $progressCount = rand(1, 2);
            for ($p = 1; $p <= $progressCount; $p++) {
                $progressTime = $createdAt->copy()->addMinutes($p * rand(15, 45));
                TicketProgress::create([
                    'ticket_id'  => $ticket->id,
                    'catatan'    => 'Progres penanganan ' . $kategori . ', tahap ' . $p,
                    'foto'       => null,
                    'updated_by' => $itSupport->id,
                    'created_at' => $progressTime,
                    'updated_at' => $progressTime,
                ]);
            }
        }

        $this->command->info('✅ 15 tiket dummy tahun 2025 berhasil dibuat.');
    }
}
