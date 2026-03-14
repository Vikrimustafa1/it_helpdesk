@extends('layouts.app')

@section('page-title', 'Antrian Tiket IT')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('it.dashboard') }}">Dashboard IT</a></li>
    <li class="breadcrumb-item active" aria-current="page">Antrian Tiket</li>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('it.tickets.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Open', 'Diproses', 'Selesai', 'Closed'] as $st)
                            <option value="{{ $st }}" {{ ($filters['status'] ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Hardware', 'Jaringan', 'SIMRS'] as $kat)
                            <option value="{{ $kat }}" {{ ($filters['kategori'] ?? '') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="prioritas" class="form-label">Prioritas</label>
                    <select name="prioritas" id="prioritas" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Urgent', 'High', 'Medium', 'Low'] as $pri)
                            <option value="{{ $pri }}" {{ ($filters['prioritas'] ?? '') === $pri ? 'selected' : '' }}>{{ $pri }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="dari" class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" id="dari" class="form-control" value="{{ $filters['dari'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="sampai" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" id="sampai" class="form-control" value="{{ $filters['sampai'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" name="search" id="search" class="form-control"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Kode / unit / deskripsi">
                </div>
                <div class="col-12 text-end mt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('it.tickets.index') }}" class="btn btn-outline-secondary ms-1">
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
                    <i class="bi bi-clipboard-check"></i>
                    <p class="mb-1">Tidak ada tiket pada antrian saat ini.</p>
                    <p class="small text-muted">Semua masalah sudah tertangani atau belum ada laporan baru.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelapor</th>
                            <th>Pelaksana</th>
                            <th>Unit</th>
                            <th>Kategori</th>
                            <th>Keparahan</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>SLA</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->kode_tiket }}</td>
                                <td>{{ $ticket->user?->name ?? '-' }}</td>
                                <td>{{ $ticket->handler?->name ?? '-' }}</td>
                                <td>{{ $ticket->unit }}</td>
                                <td>{!! $ticket->kategoriIcon() !!}</td>
                                <td>{!! $ticket->keparahanBadge() !!}</td>
                                <td>{!! $ticket->prioritasBadge() !!}</td>
                                <td>{!! $ticket->statusBadge() !!}</td>
                                <td>
                                    @if($ticket->sla_deadline)
                                        @if($ticket->isOverdue())
                                            <span class="badge bg-danger">OVERDUE</span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $ticket->sla_deadline->diffForHumans() }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary mb-1">
                                        <i class="bi bi-pencil-square"></i>
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

