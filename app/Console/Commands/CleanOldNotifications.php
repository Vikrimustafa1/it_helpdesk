<?php

namespace App\Console\Commands;

use App\Models\AppNotification;
use Illuminate\Console\Command;

class CleanOldNotifications extends Command
{
    protected $signature = 'notifications:clean {--days=30 : Hapus notifikasi lebih dari berapa hari}';

    protected $description = 'Hapus notifikasi yang sudah lebih dari N hari (default: 30 hari)';

    public function handle(): int
    {
        $days    = (int) $this->option('days');
        $cutoff  = now()->subDays($days);

        $deleted = AppNotification::where('created_at', '<', $cutoff)->delete();

        $this->info("✓ {$deleted} notifikasi lama (>{$days} hari) berhasil dihapus.");

        return self::SUCCESS;
    }
}
