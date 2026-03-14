<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tiket IT Helpdesk</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 16px 20px;
            color: #000000;
        }
        .header {
            border-bottom: 2px solid #333333;
            padding-bottom: 8px;
            margin-bottom: 10px;
            width: 100%;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 11px;
        }
        .meta {
            margin-bottom: 10px;
        }
        .meta table {
            border-collapse: collapse;
        }
        .meta td {
            padding: 2px 4px 2px 0;
        }
        .meta-label {
            width: 110px;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            table-layout: fixed;
        }
        table.data-table th,
        table.data-table td {
            border: 1px solid #000000;
            padding: 4px 5px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table.data-table th {
            background-color: #d0d7de;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        table.data-table td {
            vertical-align: top;
            font-size: 9.5px;
        }
        table.data-table tbody tr {
            page-break-inside: avoid;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .small {
            font-size: 9px;
        }
        .footer {
            margin-top: 14px;
            border-top: 1px solid #333333;
            padding-top: 5px;
            font-size: 9px;
        }

        /* Lebar kolom fixed agar tidak overflow */
        .col-no       { width: 22px; }
        .col-kode     { width: 90px; }
        .col-pelapor  { width: 90px; }
        .col-unit     { width: 90px; }
        .col-kategori { width: 58px; }
        .col-pelaksana{ width: 90px; }
        .col-prioritas{ width: 52px; }
        .col-status   { width: 55px; }
        .col-durasi   { width: 60px; }
        .col-tanggal  { width: 75px; }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 55px; text-align: center; vertical-align: middle;">
                    <div style="width: 48px; height: 48px; border: 1px solid #333; font-size: 8px; text-align: center; padding-top: 16px;">
                        LOGO RS
                    </div>
                </td>
                <td style="vertical-align: middle;">
                    <div class="header-title">Laporan Tiket IT Helpdesk</div>
                    <div class="header-subtitle">Rumah Sakit &mdash; Sistem Helpdesk Teknologi Informasi</div>
                </td>
                <td class="text-right small" style="vertical-align: middle; width: 140px;">
                    Tanggal Cetak: {{ $generatedAt->format('d/m/Y H:i') }}<br>
                    Periode:
                    @if(!empty($filters['dari']) || !empty($filters['sampai']))
                        {{ $filters['dari'] ?? '-' }} s/d {{ $filters['sampai'] ?? '-' }}
                    @else
                        Semua
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- META INFO --}}
    <div class="meta">
        <table>
            <tr>
                <td class="meta-label">Status</td>
                <td>: {{ !empty($filters['status']) ? $filters['status'] : 'Semua' }}</td>
                <td style="width: 30px;"></td>
                <td class="meta-label">Kategori</td>
                <td>: {{ !empty($filters['kategori']) ? $filters['kategori'] : 'Semua' }}</td>
                <td style="width: 30px;"></td>
                <td class="meta-label">Jumlah Tiket</td>
                <td>: {{ $tickets->count() }}</td>
            </tr>
        </table>
    </div>

    {{-- DATA TABLE --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-kode">Kode Tiket</th>
                <th class="col-pelapor">Pelapor</th>
                <th class="col-unit">Unit</th>
                <th class="col-kategori">Kategori</th>
                <th class="col-pelaksana">Pelaksana</th>
                <th class="col-prioritas">Prioritas</th>
                <th class="col-status">Status</th>
                <th class="col-durasi">Durasi</th>
                <th class="col-tanggal">Tanggal Masuk</th>
            </tr>
        </thead>
        <tbody>
        @forelse($tickets as $index => $ticket)
            <tr>
                <td class="text-center col-no">{{ $index + 1 }}</td>
                <td class="col-kode">{{ $ticket->kode_tiket }}</td>
                <td class="col-pelapor">{{ $ticket->user?->name ?? '-' }}</td>
                <td class="col-unit">{{ $ticket->unit }}</td>
                <td class="col-kategori text-center">{{ $ticket->kategori }}</td>
                <td class="col-pelaksana">{{ $ticket->handler?->name ?? '-' }}</td>
                <td class="col-prioritas text-center">{{ $ticket->prioritas ?? '-' }}</td>
                <td class="col-status text-center">{{ $ticket->status }}</td>
                <td class="col-durasi text-center">{{ $ticket->durasiFormatted() }}</td>
                <td class="col-tanggal">{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data tiket.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <div>Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }}</div>
        <div>Total tiket pada laporan ini: {{ $tickets->count() }}</div>
    </div>
</body>
</html>
