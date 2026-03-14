@extends('layouts.app')

@section('page-title', 'Detail Tiket ' . $ticket->kode_tiket)

@section('breadcrumb')
    @php $isTekniksi = in_array(auth()->user()->role, ['teknisi_hardware','teknisi_software']); @endphp
    @if($isTekniksi)
        <li class="breadcrumb-item"><a href="{{ route('it.dashboard') }}">Dashboard IT</a></li>
        <li class="breadcrumb-item"><a href="{{ route('it.tickets.index') }}">Antrian Tiket</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tickets.my') }}">Riwayat Tiket</a></li>
    @endif
    <li class="breadcrumb-item active" aria-current="page">{{ $ticket->kode_tiket }}</li>
@endsection

@push('styles')
<style>
/* ── Hero Banner ─────────────────────────── */
.ticket-hero {
    background: linear-gradient(135deg, #0f1f35 0%, #1a3358 60%, #1e3a5f 100%);
    border-radius: 1rem;
    padding: 1.5rem 1.75rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 32px rgba(15,31,53,.35);
}
.ticket-hero::before {
    content: '';
    position: absolute;
    width: 320px; height: 320px;
    background: radial-gradient(circle, rgba(59,130,246,.15) 0%, transparent 70%);
    top: -80px; right: -80px;
    pointer-events: none;
}
.ticket-hero .ticket-code {
    font-size: .75rem; font-weight: 600; letter-spacing: .1em;
    color: rgba(255,255,255,.5); text-transform: uppercase;
}
.ticket-hero .ticket-title { font-size: 1.4rem; font-weight: 700; line-height: 1.3; }
.ticket-hero .ticket-meta  { font-size: .82rem; color: rgba(255,255,255,.6); }

/* ── Info grid ───────────────────────────── */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
.info-item {
    background: #f8fafc;
    border: 1px solid #e8edf3;
    border-radius: .75rem;
    padding: .7rem .9rem;
    transition: box-shadow .15s;
}
.info-item:hover { box-shadow: 0 2px 10px rgba(0,0,0,.06); }
.info-item .lbl { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; }
.info-item .val { font-size: .875rem; font-weight: 600; color: #1e293b; margin-top: .15rem; }
html.dark .info-item { background: #0d1520; border-color: #1e2d42; }
html.dark .info-item .val { color: #e2e8f0; }

/* ── Timeline ────────────────────────────── */
.timeline-item { position: relative; padding-left: 2rem; padding-bottom: 1.5rem; }
.timeline-item:last-child { padding-bottom: 0; }
.timeline-item::before {
    content: '';
    position: absolute;
    left: .55rem; top: 1.4rem; bottom: 0;
    width: 2px;
    background: #e2e8f0;
}
.timeline-item:last-child::before { display: none; }
.timeline-dot {
    position: absolute;
    left: 0; top: .35rem;
    width: 1.1rem; height: 1.1rem;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 0 3px rgba(59,130,246,.2);
}
.timeline-dot i { font-size: .5rem; color: #fff; }
html.dark .timeline-item::before { background: #1e2d42; }

/* ── Upload zone ──────────────────────────── */
.upload-zone {
    border: 2px dashed #cbd5e1;
    border-radius: .75rem;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    position: relative;
}
.upload-zone:hover { border-color: #3b82f6; background: #f0f6ff; }
.upload-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
html.dark .upload-zone { border-color: #243554; }
html.dark .upload-zone:hover { background: #0f2238; border-color: #3b82f6; }

/* ── Photo thumb ──────────────────────────── */
.photo-thumb {
    width: 80px; height: 80px; object-fit: cover;
    border-radius: .5rem; border: 2px solid #e2e8f0;
    transition: transform .15s, box-shadow .15s;
    cursor: pointer;
}
.photo-thumb:hover { transform: scale(1.05); box-shadow: 0 4px 16px rgba(0,0,0,.15); }

/* ── SLA & durasi khusus halaman ini ─────── */
.sla-bar { height: 6px; border-radius: 3px; background: #e2e8f0; overflow: hidden; }
.sla-fill { height: 100%; border-radius: 3px; transition: width .5s ease; }

html.dark .duration-card {
    background: #052e16 !important;            /* hijau gelap lembut */
    border-color: #16a34a !important;
}
html.dark .duration-card .text-success {
    color: #bbf7d0 !important;
}

html.dark .sla-card {
    background: #0b1720 !important;            /* selaras dengan tema dark utama */
    border-color: #22c55e !important;
}
html.dark .sla-card.sla-overdue {
    background: #450a0a !important;
    border-color: #f97373 !important;
}
html.dark .sla-card .text-muted {
    color: #e5e7eb !important;
}

html.dark .desc-card {
    background: #020617 !important;
    border-color: #1e293b !important;
}
html.dark .desc-card p {
    color: #e5e7eb !important;
}

html.dark .feedback-card {
    background: linear-gradient(135deg,#451a03,#111827) !important;
    border-color: #fbbf24 !important;
}
html.dark .feedback-card .text-muted {
    color: #e5e7eb !important;
}

/* Feedback form (belum submit) — dark mode */
html.dark .feedback-form-card {
    background: linear-gradient(135deg,#052e16,#0a3d1e) !important;
    border-color: rgba(34,197,94,.5) !important;
}
html.dark .feedback-form-card .feedback-form-header {
    color: #4ade80 !important;
}
html.dark .feedback-form-card .feedback-form-question,
html.dark .feedback-form-card .feedback-form-label {
    color: #bbf7d0 !important;
}
html.dark .feedback-form-card .star-btn {
    color: #facc15 !important;
}

/* ── Star rating ──────────────────────────── */
.star-btn { transition: transform .1s; }
.star-btn:hover { transform: scale(1.2); }
</style>
@endpush

@section('content')

{{-- ══ HERO BANNER ══ --}}
<div class="ticket-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <div class="ticket-code mb-1">
                <i class="bi bi-ticket-perforated me-1"></i>{{ $ticket->kode_tiket }}
            </div>
            <div class="ticket-title mb-2">
                {{ Str::limit($ticket->deskripsi, 80) }}
            </div>
            <div class="ticket-meta d-flex flex-wrap gap-3">
                <span><i class="bi bi-person me-1"></i>{{ $ticket->user?->name ?? '-' }}</span>
                <span><i class="bi bi-building me-1"></i>{{ $ticket->unit ?? '-' }}</span>
                <span><i class="bi bi-calendar3 me-1"></i>{{ $ticket->created_at?->format('d M Y, H:i') }}</span>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-2">
            {!! $ticket->statusBadge() !!}
            @if($ticket->kategori || $ticket->ticketCategory)
            <span class="badge"
                  style="background:{{ $ticket->ticketCategory?->color ?? '#3b82f6' }}22;
                         color:{{ $ticket->ticketCategory?->color ?? '#3b82f6' }};
                         border:1px solid {{ $ticket->ticketCategory?->color ?? '#3b82f6' }}44;
                         font-size:.78rem;">
                <i class="bi {{ $ticket->ticketCategory?->icon ?? 'bi-tag' }} me-1"></i>
                {{ $ticket->getNamaKategori() }}
            </span>
            @endif
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- ══ KOLOM KIRI ══ --}}
    <div class="col-lg-5">

        {{-- Info Grid --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-primary"></i>
                <span class="fw-semibold">Informasi Tiket</span>
            </div>
            <div class="card-body">
                <div class="info-grid mb-3">
                    <div class="info-item">
                        <div class="lbl">Teknisi</div>
                        <div class="val">
                            @if($ticket->handler)
                                <i class="bi bi-person-badge me-1 text-primary"></i>{{ $ticket->handler->name }}
                            @else
                                <span class="text-muted fw-normal">Belum ditugaskan</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Prioritas</div>
                        <div class="val">{!! $ticket->prioritasBadge() !!}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Tingkat Keparahan</div>
                        <div class="val">{!! $ticket->keparahanBadge() !!}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Metode Penanganan</div>
                        <div class="val">
                            @if($ticket->metode_penanganan)
                                <span class="badge bg-info">{{ $ticket->metode_penanganan }}</span>
                            @else
                                <span class="text-muted fw-normal">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Mulai Dikerjakan</div>
                        <div class="val">{{ $ticket->waktu_mulai?->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Selesai</div>
                        <div class="val">{{ $ticket->waktu_selesai?->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                </div>

                {{-- Durasi --}}
                @if($ticket->durasi_menit)
                <div class="d-flex align-items-center gap-2 p-2 rounded mb-3 duration-card" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                    <i class="bi bi-stopwatch text-success fs-5"></i>
                    <div>
                        <div style="font-size:.7rem;color:#16a34a;font-weight:600;text-transform:uppercase;">Total Durasi</div>
                        <div class="fw-bold text-success">{{ $ticket->durasiFormatted() }}</div>
                    </div>
                </div>
                @endif

                {{-- SLA --}}
                @if($ticket->sla_deadline)
                @php
                    $isOverdue = $ticket->isOverdue();
                    $createdAt = $ticket->created_at;
                    $deadline  = $ticket->sla_deadline;
                    $now       = now();
                    $totalSecs = $deadline->diffInSeconds($createdAt);
                    $passedSecs= $now->diffInSeconds($createdAt, false);
                    $pct       = $totalSecs > 0 ? min(100, max(0, round($passedSecs / $totalSecs * 100))) : 100;
                    $barColor  = $isOverdue ? '#ef4444' : ($pct > 75 ? '#f59e0b' : '#22c55e');
                @endphp
                <div class="p-2 rounded mb-3 sla-card {{ $isOverdue ? 'sla-overdue' : '' }}" style="background:{{ $isOverdue ? '#fff1f2' : '#f0fdf4' }};border:1px solid {{ $isOverdue ? '#fecdd3' : '#bbf7d0' }};">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:.7rem;font-weight:600;text-transform:uppercase;color:{{ $isOverdue ? '#e11d48' : '#16a34a' }};">
                            <i class="bi bi-clock{{ $isOverdue ? '-history' : '' }} me-1"></i>SLA
                        </span>
                        @if($isOverdue)
                            <span class="badge bg-danger" style="font-size:.68rem;">OVERDUE</span>
                        @else
                            <span style="font-size:.75rem;color:#16a34a;font-weight:600;">{{ $deadline->diffForHumans() }}</span>
                        @endif
                    </div>
                    <div class="sla-bar">
                        <div class="sla-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
                    </div>
                    <div class="text-muted" style="font-size:.7rem;margin-top:.25rem;">Batas: {{ $deadline->format('d/m/Y H:i') }}</div>
                </div>
                @endif

                {{-- Deskripsi --}}
                <div class="p-3 rounded desc-card" style="background:#f8fafc;border:1px solid #e8edf3;">
                    <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:.4rem;">
                        <i class="bi bi-chat-text me-1"></i>Deskripsi Masalah
                    </div>
                    <p class="mb-0" style="font-size:.88rem;line-height:1.65;color:#334155;">{{ $ticket->deskripsi }}</p>
                </div>

                {{-- Foto --}}
                @php $allPhotos = $ticket->photos; @endphp
                @if($allPhotos->isNotEmpty())
                <div class="mt-3">
                    <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:.5rem;">
                        <i class="bi bi-images me-1"></i>Lampiran Foto
                        <span class="badge bg-light text-muted ms-1" style="font-size:.65rem;">{{ $allPhotos->count() }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($allPhotos as $photo)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($photo->path) }}" target="_blank">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($photo->path) }}"
                                 alt="Foto Tiket" class="photo-thumb">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- FEEDBACK SECTION --}}
                @if(in_array($ticket->status, ['Selesai', 'Closed']))
                    @if(auth()->user()->role === 'user' && $ticket->user_id === auth()->id())
                        @if($ticket->feedback)
                        <hr>
                        <div class="p-3 rounded feedback-card" style="background:linear-gradient(135deg,#fefce8,#fef9c3);border:1px solid #fde047;">
                            <div class="feedback-lbl-dark" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#854d0e;margin-bottom:.5rem;">
                                <i class="bi bi-star-fill text-warning me-1"></i>Feedback Anda
                            </div>
                            <div class="d-flex align-items-center gap-1 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $ticket->feedback->rating ? '-fill' : '' }} text-warning fs-5"></i>
                                @endfor
                                <span class="ms-2 fw-bold" style="font-size:.9rem;">{{ $ticket->feedback->rating }}/5</span>
                            </div>
                            @if($ticket->feedback->komentar)
                                <p class="mb-0 small fst-italic text-muted">"{{ $ticket->feedback->komentar }}"</p>
                            @endif
                        </div>
                        @else
                        <hr>
                        <div class="p-3 rounded feedback-form-card" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                            <div class="feedback-form-header" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#16a34a;margin-bottom:.5rem;">
                                <i class="bi bi-chat-heart me-1"></i>Beri Feedback
                            </div>
                            <p class="small feedback-form-question text-muted mb-2">Bagaimana pelayanan tim IT untuk tiket ini?</p>
                            <form method="POST" action="{{ route('tickets.feedback', $ticket->id) }}" id="feedback-form">
                                @csrf
                                <input type="hidden" name="rating" id="rating-value" value="">
                                <div class="d-flex gap-1 mb-1" id="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star fs-3 text-warning star-btn" data-val="{{ $i }}" style="cursor:pointer;"></i>
                                    @endfor
                                </div>
                                <small class="feedback-form-label text-muted d-block mb-2" id="rating-label">Pilih rating (wajib)</small>
                                <textarea name="komentar" rows="2" class="form-control form-control-sm mb-2"
                                          placeholder="Komentar tambahan (opsional)" maxlength="1000"></textarea>
                                <button type="submit" class="btn btn-sm btn-warning fw-semibold" id="feedback-submit" disabled>
                                    <i class="bi bi-send me-1"></i>Kirim Feedback
                                </button>
                            </form>
                        </div>
                        @endif
                    @elseif(in_array(auth()->user()->role, ['teknisi_hardware','teknisi_software']) && $ticket->feedback)
                    <hr>
                    <div class="p-3 rounded feedback-card" style="background:linear-gradient(135deg,#fefce8,#fef9c3);border:1px solid #fde047;">
                        <div class="feedback-lbl-dark" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#854d0e;margin-bottom:.5rem;">
                            <i class="bi bi-star-fill text-warning me-1"></i>Feedback User
                        </div>
                        <div class="d-flex align-items-center gap-1 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $ticket->feedback->rating ? '-fill' : '' }} text-warning fs-5"></i>
                            @endfor
                            <span class="ms-2 fw-bold">{{ $ticket->feedback->rating }}/5</span>
                        </div>
                        @if($ticket->feedback->komentar)
                            <p class="mb-0 small fst-italic text-muted">"{{ $ticket->feedback->komentar }}"</p>
                        @endif
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- ══ KOLOM KANAN ══ --}}
    <div class="col-lg-7">

        {{-- Timeline Progress --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i>
                <span class="fw-semibold">Timeline Progress</span>
                @if($ticket->progress->isNotEmpty())
                <span class="badge bg-primary ms-auto" style="font-size:.68rem;">{{ $ticket->progress->count() }} catatan</span>
                @endif
            </div>
            <div class="card-body">
                @if($ticket->progress->isEmpty())
                    <div class="empty-state py-3">
                        <i class="bi bi-hourglass-split fs-2 d-block mb-2 text-muted"></i>
                        <p class="fw-semibold mb-1">Belum ada catatan progress</p>
                        <p class="small text-muted mb-0">Teknisi akan menambahkan catatan saat penanganan berlangsung.</p>
                    </div>
                @else
                    <div class="pt-1">
                        @foreach($ticket->progress as $p)
                        <div class="timeline-item">
                            <div class="timeline-dot" style="background:{{ $p->role === 'user' ? 'linear-gradient(135deg,#f59e0b,#d97706)' : ($p->role === 'system' ? 'linear-gradient(135deg,#ef4444,#dc2626)' : 'linear-gradient(135deg,#3b82f6,#2563eb)') }};">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="card border-0 shadow-sm" style="border-radius:.75rem;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:28px;height:28px;background:{{ $p->role === 'user' ? 'linear-gradient(135deg,#f59e0b,#d97706)' : ($p->role === 'system' ? 'linear-gradient(135deg,#ef4444,#dc2626)' : 'linear-gradient(135deg,#3b82f6,#2563eb)') }};border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                                <i class="bi bi-{{ $p->role === 'user' ? 'person' : ($p->role === 'system' ? 'exclamation-triangle' : 'person-badge') }}-fill text-white" style="font-size:.65rem;"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold" style="font-size:.85rem;">{{ $p->updater?->name ?? 'Sistem' }}</span>
                                                @if($p->role === 'user')
                                                    <span class="badge ms-1" style="font-size:.62rem;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;">Catatan User</span>
                                                @elseif($p->role === 'system')
                                                    <span class="badge ms-1" style="font-size:.62rem;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;">Otomatis</span>
                                                @else
                                                    <span class="badge ms-1" style="font-size:.62rem;background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;">Teknisi</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-muted border timeline-badge-time" style="font-size:.7rem;">
                                            <i class="bi bi-clock me-1"></i>{{ $p->created_at?->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="mb-0 timeline-catatan-text" style="font-size:.875rem;line-height:1.6;color:#334155;">{{ $p->catatan }}</p>
                                    @if($p->foto)
                                    <div class="mt-2">
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($p->foto) }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.78rem;">
                                            <i class="bi bi-image me-1"></i>Lihat Foto Progress
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Form Tambah Progress (Teknisi: teknisi_hardware, teknisi_software) --}}
        @php
            $userRole        = auth()->user()->role;
            $isTekniksi      = in_array($userRole, ['teknisi_hardware','teknisi_software']);
            $allowedKategori = auth()->user()->getAllowedKategori();
            $canHandle       = $isTekniksi && ($allowedKategori === null || $ticket->kategori === $allowedKategori);
        @endphp
        @if($canHandle && $ticket->status !== 'Closed' && ($ticket->handled_by === null || $ticket->handled_by === auth()->id()))
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle text-success"></i>
                <span class="fw-semibold">Tambah Catatan Progress</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tickets.progress', $ticket->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="catatan" class="form-label fw-semibold" style="font-size:.875rem;">Catatan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatan" rows="3"
                                  class="form-control @error('catatan') is-invalid @enderror"
                                  placeholder="Tuliskan catatan progress penanganan..."
                                  required>{{ old('catatan') }}</textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.875rem;">Foto Progress (opsional)</label>
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="document.getElementById('foto_progress').click()">
                                <i class="bi bi-folder2-open me-1"></i>Pilih File
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success"
                                    onclick="document.getElementById('foto_progress_cam').click()">
                                <i class="bi bi-camera me-1"></i>Ambil Foto
                            </button>
                        </div>
                        <input type="file" name="foto" id="foto_progress" accept="image/png,image/jpeg"
                               class="@error('foto') is-invalid @enderror" style="display:none;"
                               onchange="previewProgressPhoto(this)">
                        <input type="file" name="foto" id="foto_progress_cam" accept="image/*" capture="environment"
                               style="display:none;"
                               onchange="previewProgressPhoto(this)">
                        <div id="preview-wrapper-progress" style="display:none;margin-top:.75rem;">
                            <img id="preview-image-progress" src="#" alt="Preview"
                                 class="rounded border" style="max-height:180px;">
                        </div>
                        @error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-plus-circle me-1"></i>Simpan Progress
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @elseif($isTekniksi && $allowedKategori !== null && $ticket->kategori !== $allowedKategori)
        <div class="alert alert-warning shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle fs-5"></i>
            <span>Tiket ini berkategori <strong>{{ $ticket->kategori }}</strong>. Anda hanya dapat menangani tiket <strong>{{ $allowedKategori }}</strong>.</span>
        </div>
        @elseif($isTekniksi && $ticket->status !== 'Closed' && $ticket->handled_by !== null && $ticket->handled_by !== auth()->id())
        <div class="alert alert-info shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-info-circle fs-5"></i>
            <span>Tiket ini sedang dikerjakan oleh <strong>{{ $ticket->handler?->name ?? 'teknisi lain' }}</strong>. Hanya teknisi yang menangani yang dapat mengubah status atau menambah catatan progress.</span>
        </div>
        @endif

        {{-- Form Catatan User (saat status Diproses) --}}
        @if(auth()->user()->role === 'user' && $ticket->user_id === auth()->id() && $ticket->status === 'Diproses')
        <div class="card shadow-sm mt-3">
            <div class="card-header d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#fef3c7,#fffbeb);">
                <i class="bi bi-chat-left-text text-warning"></i>
                <span class="fw-semibold">Tambah Catatan untuk Teknisi</span>
                <span class="badge bg-warning text-dark ms-1" style="font-size:.68rem;">Update Info</span>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Jika masalah belum terselesaikan atau ada informasi tambahan, sampaikan di sini agar teknisi mengetahuinya.
                </p>
                <form method="POST" action="{{ route('tickets.user-note', $ticket->id) }}">
                    @csrf
                    <div class="mb-3">
                        <textarea name="catatan_user" rows="3"
                                  class="form-control @error('catatan_user') is-invalid @enderror"
                                  placeholder="Contoh: Masalahnya masih berlanjut setelah restart... atau: Remote sudah bisa tapi printer masih error..."
                                  required maxlength="2000">{{ old('catatan_user') }}</textarea>
                        @error('catatan_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning fw-semibold px-4">
                            <i class="bi bi-send me-1"></i>Kirim Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Star Rating UI
const stars = document.querySelectorAll('.star-btn');
const ratingInput = document.getElementById('rating-value');
const ratingLabel = document.getElementById('rating-label');
const submitBtn   = document.getElementById('feedback-submit');
const labels = ['','Sangat Buruk','Buruk','Cukup','Baik','Sangat Baik'];

function setStars(val) {
    stars.forEach(s => {
        const v = parseInt(s.dataset.val);
        s.className = v <= val ? 'bi bi-star-fill fs-3 text-warning star-btn' : 'bi bi-star fs-3 text-warning star-btn';
    });
}

if (stars.length) {
    stars.forEach(star => {
        star.addEventListener('mouseover', function() { setStars(parseInt(this.dataset.val)); });
        star.addEventListener('mouseout',  function() { setStars(parseInt(ratingInput?.value) || 0); });
        star.addEventListener('click', function() {
            const val = parseInt(this.dataset.val);
            ratingInput.value = val;
            setStars(val);
            if (ratingLabel) ratingLabel.textContent = labels[val] || '';
            if (submitBtn)   submitBtn.disabled = false;
        });
    });
}

// ── Upload preview progress foto (named function for both file & camera inputs)
function previewProgressPhoto(input) {
    const file = input.files[0];
    const previewWrapper = document.getElementById('preview-wrapper-progress');
    const previewImg     = document.getElementById('preview-image-progress');
    if (!file) { previewWrapper.style.display = 'none'; return; }
    if (!['image/jpeg', 'image/png', 'image/webp', 'image/heic'].includes(file.type.toLowerCase()) && file.type.startsWith('image/')) {
        // allow any image (camera captures)
    } else if (!file.type.startsWith('image/')) {
        alert('Hanya file gambar yang diperbolehkan.'); input.value = ''; return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran maks 5MB.'); input.value = ''; return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewWrapper.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
