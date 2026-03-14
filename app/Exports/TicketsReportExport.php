<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketsReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function __construct(protected array $filters)
    {
    }

    public function collection(): Collection
    {
        $query = Ticket::query()->with(['user', 'handler']);

        if (!empty($this->filters['dari'])) {
            $query->whereDate('created_at', '>=', $this->filters['dari']);
        }

        if (!empty($this->filters['sampai'])) {
            $query->whereDate('created_at', '<=', $this->filters['sampai']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('kategori', $this->filters['kategori']);
        }

        if (!empty($this->filters['handler_id'])) {
            $query->where('handled_by', $this->filters['handler_id']);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        return $tickets->map(function (Ticket $ticket, int $index): array {
            return [
                'no'            => $index + 1,
                'kode_tiket'    => $ticket->kode_tiket,
                'pelapor'       => $ticket->user?->name ?? '-',
                'unit'          => $ticket->unit,
                'kategori'      => $ticket->kategori,
                'pelaksana'     => $ticket->handler?->name ?? '-',
                'prioritas'     => $ticket->prioritas ?? '-',
                'status'        => $ticket->status,
                'durasi'        => $ticket->durasiFormatted(),
                'tanggal_masuk' => optional($ticket->created_at)->format('d/m/Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Tiket',
            'Pelapor',
            'Unit',
            'Kategori',
            'Pelaksana',
            'Prioritas',
            'Status',
            'Durasi',
            'Tanggal Masuk',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Bold header
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Laporan Tiket';
    }
}

