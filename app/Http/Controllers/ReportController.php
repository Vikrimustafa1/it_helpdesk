<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Exports\TicketsReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman filter laporan + preview.
     * - teknisi_hardware: paksa kategori = Hardware
     * - teknisi_software: paksa kategori = Software
     * - Semua dapat filter berdasarkan nama teknisi (handler)
     */
    public function index(Request $request): View
    {
        $user            = Auth::user();
        $allowedKategori = $user->getAllowedKategori();

        $filters = [
            'dari'       => $request->query('dari'),
            'sampai'     => $request->query('sampai'),
            'status'     => $request->query('status'),
            'kategori'   => $request->query('kategori'),
            'handler_id' => $request->query('handler_id'),
        ];

        $query = Ticket::query()->with(['user', 'handler']);

        if (!empty($filters['dari'])) {
            $query->whereDate('created_at', '>=', $filters['dari']);
        }

        if (!empty($filters['sampai'])) {
            $query->whereDate('created_at', '<=', $filters['sampai']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['handler_id'])) {
            $query->where('handled_by', $filters['handler_id']);
        }

        $tickets = $query->orderBy('created_at', 'desc')->limit(100)->get();

        // Daftar teknisi untuk dropdown filter
        $teknisiList = User::whereIn('role', ['teknisi_hardware', 'teknisi_software'])
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        return view('reports.index', [
            'tickets'        => $tickets,
            'filters'        => $filters,
            'teknisiList'    => $teknisiList,
            'allowedKategori'=> $allowedKategori,
        ]);
    }

    /**
     * Export PDF berdasarkan filter.
     */
    public function exportPdf(Request $request): Response
    {
        $user            = Auth::user();
        $allowedKategori = $user->getAllowedKategori();

        $filters = [
            'dari'       => $request->input('dari'),
            'sampai'     => $request->input('sampai'),
            'status'     => $request->input('status'),
            'kategori'   => $request->input('kategori'),
            'handler_id' => $request->input('handler_id'),
        ];

        $query = Ticket::query()->with(['user', 'handler']);

        if (!empty($filters['dari'])) {
            $query->whereDate('created_at', '>=', $filters['dari']);
        }

        if (!empty($filters['sampai'])) {
            $query->whereDate('created_at', '<=', $filters['sampai']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['handler_id'])) {
            $query->where('handled_by', $filters['handler_id']);
        }

        $tickets     = $query->orderBy('created_at', 'desc')->get();
        $generatedAt = Carbon::now();

        $pdf = Pdf::loadView('reports.pdf', [
            'tickets'    => $tickets,
            'filters'    => $filters,
            'generatedAt'=> $generatedAt,
        ])->setPaper('a4', 'landscape');

        $fileName = 'laporan_tiket_' . $generatedAt->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export Excel (XLSX) menggunakan Maatwebsite Excel.
     */
    public function exportExcel(Request $request)
    {
        $user            = Auth::user();
        $allowedKategori = $user->getAllowedKategori();

        $filters = [
            'dari'       => $request->input('dari'),
            'sampai'     => $request->input('sampai'),
            'status'     => $request->input('status'),
            'kategori'   => $request->input('kategori'),
            'handler_id' => $request->input('handler_id'),
        ];

        $fileName = 'laporan_tiket_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new TicketsReportExport($filters), $fileName);
    }
}
