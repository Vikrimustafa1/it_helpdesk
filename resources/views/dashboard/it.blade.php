@extends('layouts.app')

@section('page-title', 'Dashboard IT Teknisi')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard IT</li>
@endsection

@section('content')

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card shadow-sm stat-blue">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Tiket Hari Ini</div>
                        <div class="stat-value">{{ $ticketsToday }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card shadow-sm stat-orange">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Open</div>
                        <div class="stat-value">{{ $openCount }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card shadow-sm stat-yellow">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Diproses</div>
                        <div class="stat-value">{{ $diprosesCount }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card shadow-sm stat-green">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Selesai</div>
                        <div class="stat-value">{{ $selesaiCount }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card shadow-sm stat-slate">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Closed</div>
                        <div class="stat-value">{{ $closedCount }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-archive"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="stat-card shadow-sm stat-red">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Critical Aktif</div>
                        <div class="stat-value">{{ $criticalAktif }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Charts ── --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-semibold">Tiket per Bulan</h6>
                        <div class="text-muted" style="font-size:.75rem;">Tahun {{ $selectedYear }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" style="width:auto;font-size:.8rem;"
                                onchange="window.location.href='{{ route('it.dashboard') }}?year='+this.value">
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ $yr == $selectedYear ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                        <i class="bi bi-bar-chart-line text-primary fs-5"></i>
                    </div>
                </div>
                <div class="card-body" style="height:240px;">
                    <canvas id="ticketsPerMonthChart" style="height:100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-semibold">Kategori Tiket</h6>
                        <div class="text-muted" style="font-size:.75rem;">Tahun {{ $selectedYear }}</div>
                    </div>
                    <i class="bi bi-pie-chart text-primary fs-5"></i>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center" style="height:240px;">
                    @if(empty($kategoriValues) || array_sum($kategoriValues) === 0)
                        <div class="empty-state py-2">
                            <i class="bi bi-clipboard-data"></i>
                            <p class="mb-0 small">Belum ada data tiket.</p>
                        </div>
                    @else
                        <canvas id="kategoriDonutChart" style="max-height:200px;"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Urgent Tickets Table ── --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-semibold">Tiket Paling Mendesak</h6>
                <div class="text-muted" style="font-size:.75rem;">Tiket aktif berdasarkan prioritas</div>
            </div>
            <a href="{{ route('it.tickets.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-list-ul me-1"></i> Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            @if($urgentTickets->isEmpty())
                <div class="empty-state py-5">
                    <i class="bi bi-emoji-smile text-success"></i>
                    <p class="mb-1 fw-semibold">Semua beres!</p>
                    <p class="small text-muted">Tidak ada tiket aktif yang menunggu penanganan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelapor</th>
                                <th>Unit</th>
                                <th>Kategori</th>
                                <th>Keparahan</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>SLA</th>
                                <th>Tanggal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($urgentTickets as $ticket)
                            <tr>
                                <td class="fw-semibold text-primary" style="font-size:.8rem;">{{ $ticket->kode_tiket }}</td>
                                <td>{{ $ticket->user?->name ?? '-' }}</td>
                                <td>{{ $ticket->unit }}</td>
                                <td>{!! $ticket->kategoriIcon() !!}</td>
                                <td>{!! $ticket->keparahanBadge() !!}</td>
                                <td>{!! $ticket->prioritasBadge() !!}</td>
                                <td>{!! $ticket->statusBadge() !!}</td>
                                <td>
                                    @if($ticket->sla_deadline)
                                        @if($ticket->isOverdue())
                                            <span class="badge bg-danger"><i class="bi bi-alarm me-1"></i>OVERDUE</span>
                                        @else
                                            <span class="badge bg-success">{{ $ticket->sla_deadline->diffForHumans() }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:.8rem;">{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
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

@push('scripts')
<script>
    const monthLabels   = @json($monthLabels);
    const monthData     = @json($monthData);
    const kategoriLabels = @json($kategoriLabels);
    const kategoriValues = @json($kategoriValues);

    // Bar chart
    const ctxMonth = document.getElementById('ticketsPerMonthChart');
    if (ctxMonth && typeof Chart !== 'undefined') {
        new Chart(ctxMonth, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Jumlah Tiket',
                    data: monthData,
                    backgroundColor: 'rgba(59,130,246,.75)',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { cornerRadius: 8 } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: 'rgba(148,163,184,.25)' } // garis abu-abu halus
                    }
                }
            }
        });
    }

    // Donut chart
    const ctxKategori = document.getElementById('kategoriDonutChart');
    if (ctxKategori && typeof Chart !== 'undefined' && kategoriValues.length > 0) {
        new Chart(ctxKategori, {
            type: 'doughnut',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    data: kategoriValues,
                    backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 12, usePointStyle: true } },
                    tooltip: { cornerRadius: 8 }
                }
            }
        });
    }
</script>
@endpush
