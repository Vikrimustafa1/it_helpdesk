<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Dashboard utama admin.
     */
    public function dashboard(): View
    {
        $stats = [
            'total_users'      => User::where('role', 'user')->count(),
            'total_it_support' => User::whereIn('role', ['teknisi_hardware', 'teknisi_software'])->count(),
            'total_tickets'    => Ticket::count(),
            'open_tickets'     => Ticket::where('status', 'Open')->count(),
            'diproses_tickets' => Ticket::where('status', 'Diproses')->count(),
            'selesai_tickets'  => Ticket::whereIn('status', ['Selesai', 'Closed'])->count(),
            'overdue_tickets'  => Ticket::whereNotNull('sla_deadline')
                                        ->whereNotIn('status', ['Selesai', 'Closed'])
                                        ->where('sla_deadline', '<', now())
                                        ->count(),
        ];

        // Tiket terbaru
        $recentTickets = Ticket::with(['user', 'handler'])
            ->latest()
            ->take(8)
            ->get();

        $itSupportList = User::whereIn('role', ['teknisi_hardware', 'teknisi_software'])
            ->withCount([
                'handledTickets as open_count' => fn($q) => $q->whereIn('status', ['Open', 'Diproses']),
            ])
            ->withCount('handledTickets as total_count')
            ->orderByDesc('total_count')
            ->orderByDesc('open_count')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTickets', 'itSupportList'));
    }
}
