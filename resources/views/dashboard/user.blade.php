@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')

    {{-- ── Welcome banner ── --}}
    <div class="card shadow-sm mb-4" style="background:linear-gradient(120deg,#0f1f35,#1e4d7b); border-radius:1rem; border:none;">
        <div class="card-body py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="text-white fw-semibold fs-6">Halo, {{ auth()->user()->name }} 👋</div>
                <div style="color:rgba(255,255,255,.6); font-size:.82rem;">Berikut ringkasan tiket IT Anda hari ini.</div>
            </div>
            <a href="{{ route('tickets.create') }}" class="btn btn-sm fw-semibold" style="background:#3b82f6;color:#fff;border-radius:.6rem;padding:.5rem 1.1rem;box-shadow:0 4px 12px rgba(59,130,246,.4);">
                <i class="bi bi-plus-circle me-1"></i> Buat Laporan Baru
            </a>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card shadow-sm stat-blue">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Tiket</div>
                        <div class="stat-value">{{ $total }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-collection"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card shadow-sm stat-orange">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Open</div>
                        <div class="stat-value">{{ $open }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-folder2-open"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card shadow-sm stat-yellow">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Diproses</div>
                        <div class="stat-value">{{ $diproses }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-tools"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card shadow-sm stat-green">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Selesai</div>
                        <div class="stat-value">{{ $selesai }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Recent Tickets ── --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-semibold">Tiket Terbaru Anda</h6>
                <div class="text-muted" style="font-size:.75rem;">5 tiket terakhir yang Anda buat</div>
            </div>
            <a href="{{ route('tickets.my') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-clock-history me-1"></i> Riwayat Lengkap
            </a>
        </div>
        <div class="card-body p-0">
            @if($latestTickets->isEmpty())
                <div class="empty-state py-5">
                    <i class="bi bi-clipboard-x"></i>
                    <p class="mb-1 fw-semibold">Belum ada tiket</p>
                    <p class="small text-muted">Klik "Buat Laporan Baru" untuk melaporkan masalah IT.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode Tiket</th>
                                <th>Unit</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($latestTickets as $ticket)
                            <tr>
                                <td class="fw-semibold text-primary" style="font-size:.82rem;">{{ $ticket->kode_tiket }}</td>
                                <td>{{ $ticket->unit }}</td>
                                <td>{!! $ticket->kategoriIcon() !!}</td>
                                <td>{!! $ticket->statusBadge() !!}</td>
                                <td class="text-muted" style="font-size:.82rem;">{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
