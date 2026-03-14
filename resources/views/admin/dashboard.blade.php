@extends('layouts.app')

@section('page-title', 'Admin Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Admin</li>
@endsection

@push('styles')
<style>
.admin-hero {
    background: linear-gradient(135deg, #0f172a 0%, #111827 45%, #1e293b 100%);
    border-radius: 1rem; padding: 1.5rem 1.75rem; margin-bottom: 1.5rem;
    color: #fff; position: relative; overflow: hidden;
    box-shadow: 0 8px 32px rgba(15,52,96,.4);
}
.admin-hero::before {
    content:''; position:absolute; width:300px;height:300px;
    top:-100px;right:-80px;
    background:radial-gradient(circle,rgba(229,57,53,.18) 0%,transparent 65%);
    pointer-events:none;
}
.it-row { transition: background .12s; }
.it-row:hover { background:#f8fafc; }
html.dark .it-row:hover { background:#0d1520; }

/* Dark mode tweak khusus section admin hero */
html.dark .admin-hero {
    background: radial-gradient(circle at top left, #1d4ed8 0, transparent 55%),
                radial-gradient(circle at top right, #be123c 0, transparent 50%),
                linear-gradient(135deg, #020617 0%, #020617 40%, #020617 100%);
    box-shadow: 0 18px 45px rgba(0,0,0,.7);
}
html.dark .admin-hero .badge {
    background: rgba(248,250,252,.08) !important;
    color: #e5e7eb !important;
    border-color: rgba(148,163,184,.5) !important;
}
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<div class="admin-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <div style="font-size:.7rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.1em;mb-bottom:.3rem;">
                <i class="bi bi-shield-check me-1"></i>Panel Administrator
            </div>
            <div style="font-size:1.3rem;font-weight:700;margin-top:.2rem;">Selamat datang, {{ auth()->user()->name }}</div>
            <div style="font-size:.82rem;color:rgba(255,255,255,.5);margin-top:.25rem;">
                <i class="bi bi-clock me-1"></i>{{ now()->translatedFormat('l, d F Y — H:i') }} WIB
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-1 mt-1">
            <span class="badge px-3 py-2" style="background:rgba(229,57,53,.2);color:#ff7b7b;border:1px solid rgba(229,57,53,.3);font-size:.78rem;">
                <i class="bi bi-person-badge me-1"></i>Administrator
            </span>
        </div>
    </div>
</div>

{{-- ══ STAT CARDS (gaya pelapor — berwarna) ══ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card shadow-sm stat-blue">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Pelapor</div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card shadow-sm stat-green">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Teknisi</div>
                    <div class="stat-value">{{ $stats['total_it_support'] }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-pc-display-horizontal"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card shadow-sm stat-yellow">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Tiket</div>
                    <div class="stat-value">{{ $stats['total_tickets'] }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-ticket-perforated"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card shadow-sm stat-red">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">SLA Overdue</div>
                    <div class="stat-value">{{ $stats['overdue_tickets'] }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ Ticket Status Row (berwarna) ══ --}}
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-card shadow-sm stat-blue text-center py-3">
            <div class="stat-value" style="font-size:1.5rem;">{{ $stats['open_tickets'] }}</div>
            <div class="stat-label mt-1">Open</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card shadow-sm stat-orange text-center py-3">
            <div class="stat-value" style="font-size:1.5rem;">{{ $stats['diproses_tickets'] }}</div>
            <div class="stat-label mt-1">Diproses</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card shadow-sm stat-green text-center py-3">
            <div class="stat-value" style="font-size:1.5rem;">{{ $stats['selesai_tickets'] }}</div>
            <div class="stat-label mt-1">Selesai</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- ── Tiket Terbaru ── --}}
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i>
                <span class="fw-semibold">Tiket Terbaru</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.84rem;">
                        <thead style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;">
                            <tr>
                                <th class="ps-3">Kode</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Teknisi</th>
                                <th class="pe-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $t)
                            <tr class="it-row" onclick="window.location='{{ route('tickets.show', $t->id) }}'" style="cursor:pointer;">
                                <td class="ps-3">
                                    <span class="badge bg-light text-dark border" style="font-size:.72rem;">{{ $t->kode_tiket }}</span>
                                </td>
                                <td>{{ $t->user?->name ?? '-' }}</td>
                                <td>{!! $t->statusBadge() !!}</td>
                                <td>{{ $t->handler?->name ?? '-' }}</td>
                                <td class="pe-3 text-muted" style="font-size:.76rem;">{{ $t->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada tiket</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Performa Teknisi ── --}}
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart text-success"></i>
                <span class="fw-semibold">Performa Teknisi</span>
            </div>
            <div class="card-body p-0">
                @forelse($itSupportList as $its)
                <div class="it-row d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="color:#fff;font-size:.72rem;font-weight:700;">{{ strtoupper(substr($its->name,0,2)) }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.84rem;">{{ $its->name }}</div>
                        <div class="text-muted" style="font-size:.72rem;">{{ $its->total_count }} tiket total</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold" style="font-size:.9rem;color:{{ $its->open_count > 0 ? '#f59e0b' : '#22c55e' }};">
                            {{ $its->open_count }}
                        </div>
                        <div class="text-muted" style="font-size:.68rem;">aktif</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.85rem;">
                    <i class="bi bi-people d-block fs-3 mb-1"></i>Belum ada Teknisi
                </div>
                @endforelse
            </div>
            @if($itSupportList->isNotEmpty())
            <div class="card-footer text-end py-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.78rem;">
                    Lihat Semua User <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
