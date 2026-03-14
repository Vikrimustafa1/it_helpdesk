<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard IT Support.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today();

        $ticketsToday = Ticket::whereDate('created_at', $today)->count();
        $openCount    = Ticket::where('status', 'Open')->count();
        $diprosesCount = Ticket::where('status', 'Diproses')->count();
        $selesaiCount  = Ticket::where('status', 'Selesai')->count();
        $closedCount   = Ticket::where('status', 'Closed')->count();
        $criticalAktif = Ticket::where('tingkat_keparahan', 'Critical')
            ->whereNotIn('status', ['Selesai', 'Closed'])
            ->count();

        // Tahun yang tersedia (dari tahun tiket pertama s.d. tahun ini)
        $currentYear    = Carbon::now()->year;
        $firstTicketMin = Ticket::min('created_at');
        $firstYear      = $firstTicketMin ? Carbon::parse($firstTicketMin)->year : $currentYear;
        $availableYears = range($currentYear, $firstYear); // descending

        // Tahun yang dipilih
        $selectedYear = (int) $request->input('year', $currentYear);
        $selectedYear = in_array($selectedYear, $availableYears) ? $selectedYear : $currentYear;

        // Chart: tickets per bulan pada tahun yang dipilih
        $start = Carbon::create($selectedYear, 1, 1)->startOfMonth();
        $end   = Carbon::create($selectedYear, 12, 31)->endOfMonth();

        $perMonthRaw = Ticket::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym')
            ->toArray();

        $monthLabels = [];
        $monthData   = [];
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $key = $cursor->format('Y-m');
            $monthLabels[] = $cursor->translatedFormat('M');
            $monthData[]   = $perMonthRaw[$key] ?? 0;
            $cursor->addMonth();
        }

        // Donut: kategori pada tahun yang dipilih
        $kategoriData = Ticket::selectRaw('kategori, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        $kategoriLabels = array_keys($kategoriData);
        $kategoriValues = array_values($kategoriData);

        // 8 tiket aktif paling mendesak
        $urgentTickets = Ticket::whereIn('status', ['Open', 'Diproses'])
            ->orderByRaw(
                "FIELD(prioritas, 'Urgent', 'High', 'Medium', 'Low') IS NULL,
                 FIELD(prioritas, 'Urgent', 'High', 'Medium', 'Low')"
            )
            ->orderBy('created_at', 'asc')
            ->limit(8)
            ->get();

        return view('dashboard.it', [
            'ticketsToday'    => $ticketsToday,
            'openCount'       => $openCount,
            'diprosesCount'   => $diprosesCount,
            'selesaiCount'    => $selesaiCount,
            'closedCount'     => $closedCount,
            'criticalAktif'   => $criticalAktif,
            'monthLabels'     => $monthLabels,
            'monthData'       => $monthData,
            'kategoriLabels'  => $kategoriLabels,
            'kategoriValues'  => $kategoriValues,
            'urgentTickets'   => $urgentTickets,
            'availableYears'  => $availableYears,
            'selectedYear'    => $selectedYear,
        ]);
    }

    /**
     * Dashboard user (pelapor).
     */
    public function userDashboard(Request $request): View
    {
        $user = Auth::user();

        $total = Ticket::where('user_id', $user?->id)->count();
        $open = Ticket::where('user_id', $user?->id)->where('status', 'Open')->count();
        $diproses = Ticket::where('user_id', $user?->id)->where('status', 'Diproses')->count();
        $selesai = Ticket::where('user_id', $user?->id)->where('status', 'Selesai')->count();

        $latestTickets = Ticket::where('user_id', $user?->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.user', [
            'total'         => $total,
            'open'          => $open,
            'diproses'      => $diproses,
            'selesai'       => $selesai,
            'latestTickets' => $latestTickets,
        ]);
    }
}
