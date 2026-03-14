@extends('layouts.app')

@section('page-title', 'Riwayat Tiket Saya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Riwayat Tiket</li>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('tickets.my') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Open', 'Diproses', 'Selesai', 'Closed'] as $st)
                            <option value="{{ $st }}" {{ ($filters['status'] ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Hardware', 'Jaringan', 'SIMRS'] as $kat)
                            <option value="{{ $kat }}" {{ ($filters['kategori'] ?? '') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" name="search" id="search" class="form-control"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Kode tiket / unit / deskripsi">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary w-100 mb-1">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('tickets.my') }}" class="btn btn-outline-secondary w-100 btn-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($tickets->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-clipboard-x"></i>
                    <p class="mb-1">Belum ada tiket yang sesuai dengan filter.</p>
                    <p class="small text-muted">Coba ubah filter atau buat laporan baru.</p>
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Buat Laporan Baru
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Kode Tiket</th>
                            <th>Unit</th>
                            <th>Kategori</th>
                            <th>Pelaksana</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->kode_tiket }}</td>
                                <td>{{ $ticket->unit }}</td>
                                <td>{{ $ticket->kategori }}</td>
                                <td>{{ $ticket->handler?->name ?? '-' }}</td>
                                <td>{!! $ticket->statusBadge() !!}</td>
                                <td>{!! $ticket->prioritasBadge() !!}</td>
                                <td>{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

