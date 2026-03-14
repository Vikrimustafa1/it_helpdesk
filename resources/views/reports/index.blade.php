@extends('layouts.app')

@section('page-title', 'Laporan Tiket IT')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('it.dashboard') }}">Dashboard IT</a></li>
    <li class="breadcrumb-item active" aria-current="page">Laporan Tiket</li>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label for="dari" class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" id="dari" class="form-control" value="{{ $filters['dari'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="sampai" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" id="sampai" class="form-control" value="{{ $filters['sampai'] ?? '' }}">
                </div>
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
                    @if($allowedKategori)
                        {{-- Teknisi terkunci ke kategori mereka --}}
                        <input type="hidden" name="kategori" value="{{ $allowedKategori }}">
                        <input type="text" class="form-control" value="{{ $allowedKategori }}" readonly
                               style="background:#f8f9fa; font-weight:600;">
                    @else
                        <select name="kategori" id="kategori" class="form-select">
                            <option value="">Semua</option>
                            @foreach(['Hardware', 'Software'] as $kat)
                                <option value="{{ $kat }}" {{ ($filters['kategori'] ?? '') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-md-2">
                    <label for="handler_id" class="form-label">Teknisi</label>
                    <select name="handler_id" id="handler_id" class="form-select">
                        <option value="">Semua Teknisi</option>
                        @foreach($teknisiList as $tek)
                            <option value="{{ $tek->id }}" {{ ($filters['handler_id'] ?? '') == $tek->id ? 'selected' : '' }}>
                                {{ $tek->name }}
                                @if($tek->role === 'teknisi_hardware') (HW)
                                @elseif($tek->role === 'teknisi_software') (SW)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Preview Laporan (maks. 100 data)</h6>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('reports.pdf') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="dari" value="{{ $filters['dari'] ?? '' }}">
                    <input type="hidden" name="sampai" value="{{ $filters['sampai'] ?? '' }}">
                    <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
                    <input type="hidden" name="kategori" value="{{ $filters['kategori'] ?? '' }}">
                    <input type="hidden" name="handler_id" value="{{ $filters['handler_id'] ?? '' }}">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-filetype-pdf me-1"></i> PDF
                    </button>
                </form>
                <form method="POST" action="{{ route('reports.excel') }}">
                    @csrf
                    <input type="hidden" name="dari" value="{{ $filters['dari'] ?? '' }}">
                    <input type="hidden" name="sampai" value="{{ $filters['sampai'] ?? '' }}">
                    <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
                    <input type="hidden" name="kategori" value="{{ $filters['kategori'] ?? '' }}">
                    <input type="hidden" name="handler_id" value="{{ $filters['handler_id'] ?? '' }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($tickets->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-clipboard-x"></i>
                    <p class="mb-1">Tidak ada data tiket untuk filter yang dipilih.</p>
                    <p class="small text-muted">Silakan ubah rentang tanggal atau kriteria lainnya.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Tiket</th>
                            <th>Pelapor</th>
                            <th>Unit</th>
                            <th>Kategori</th>
                            <th>Pelaksana</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Durasi</th>
                            <th>Tanggal Masuk</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $index => $ticket)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold text-primary" style="font-size:.82rem;">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-decoration-none">
                                        {{ $ticket->kode_tiket }}
                                    </a>
                                </td>
                                <td>{{ $ticket->user?->name ?? '-' }}</td>
                                <td>{{ $ticket->unit }}</td>
                                <td>
                                    <span class="badge"
                                          style="background:{{ $ticket->kategori === 'Hardware' ? '#3b82f6' : '#8b5cf6' }}22;
                                                 color:{{ $ticket->kategori === 'Hardware' ? '#3b82f6' : '#8b5cf6' }};
                                                 border:1px solid {{ $ticket->kategori === 'Hardware' ? '#3b82f6' : '#8b5cf6' }}44;">
                                        {{ $ticket->kategori }}
                                    </span>
                                </td>
                                <td>{{ $ticket->handler?->name ?? '-' }}</td>
                                <td>{{ $ticket->prioritas ?? '-' }}</td>
                                <td>{!! $ticket->statusBadge() !!}</td>
                                <td>{{ $ticket->durasiFormatted() }}</td>
                                <td>{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
