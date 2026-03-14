@extends('layouts.app')

@section('page-title', 'Edit Tiket ' . $ticket->kode_tiket)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('it.dashboard') }}">Dashboard IT</a></li>
    <li class="breadcrumb-item"><a href="{{ route('it.tickets.index') }}">Antrian Tiket</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $ticket->kode_tiket }}</li>
@endsection

@push('styles')
<style>
/* Hero */
.edit-hero {
    background: linear-gradient(135deg, #0f1f35 0%, #1a3358 60%, #1e3a5f 100%);
    border-radius: 1rem; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;
    color: #fff; position: relative; overflow: hidden;
    box-shadow: 0 8px 32px rgba(15,31,53,.35);
}
.edit-hero::before {
    content: ''; position: absolute;
    width: 280px; height: 280px; top: -90px; right: -70px;
    background: radial-gradient(circle, rgba(251,146,60,.15) 0%, transparent 70%);
    pointer-events: none;
}
.edit-hero .tag  { font-size:.68rem; color:rgba(255,255,255,.45); text-transform:uppercase; letter-spacing:.1em; }
.edit-hero .code { font-size:1.2rem; font-weight:700; }
.edit-hero .meta { font-size:.8rem; color:rgba(255,255,255,.5); margin-top:.25rem; }

/* Info panel items */
.info-row {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .65rem 0; border-bottom: 1px solid #f1f5f9;
}
.info-row:last-child { border-bottom: none; }
.info-row .lbl {
    flex-shrink: 0; width: 6.5rem;
    font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;
    color: #94a3b8; padding-top: .1rem;
}
.info-row .val { font-size: .875rem; color: #1e293b; font-weight: 500; }
html.dark .info-row { border-color: #1a2a3d; }
html.dark .info-row .val { color: #e2e8f0; }

/* Status option pills (visual radio group) */
.status-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: .5rem; }
.status-pill {
    border: 2px solid #e2e8f0; border-radius: .6rem; padding: .5rem .75rem;
    cursor: pointer; text-align: center; transition: all .15s; position: relative;
}
.status-pill:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
.status-pill input[type="radio"] { opacity: 0; position: absolute; width: 0; height: 0; }
.status-pill.sel-open     { border-color: #3b82f6; background: #eff6ff; }
.status-pill.sel-diproses { border-color: #f59e0b; background: #fffbeb; }
.status-pill.sel-selesai  { border-color: #22c55e; background: #f0fdf4; }
.status-pill.sel-closed   { border-color: #64748b; background: #f8fafc; }
html.dark .status-pill { border-color: #1e2d42; }
html.dark .status-pill.sel-open     { background: rgba(59,130,246,.22); color: #e2e8f0; }
html.dark .status-pill.sel-open .text-muted { color: rgba(226,232,240,.85) !important; }
html.dark .status-pill.sel-diproses { background: rgba(245,158,11,.25); color: #e2e8f0; }
html.dark .status-pill.sel-diproses .text-muted { color: rgba(226,232,240,.85) !important; }
html.dark .status-pill.sel-selesai  { background: rgba(34,197,94,.22); color: #e2e8f0; }
html.dark .status-pill.sel-selesai .text-muted { color: rgba(226,232,240,.85) !important; }
html.dark .status-pill.sel-closed   { background: rgba(100,116,139,.25); color: #e2e8f0; }
html.dark .status-pill.sel-closed .text-muted { color: rgba(226,232,240,.85) !important; }

/* Severity grid */
.sev-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: .5rem; }
.sev-pill {
    border: 2px solid #e2e8f0; border-radius: .6rem; padding: .45rem .65rem;
    cursor: pointer; text-align: center; transition: all .15s; position: relative;
}
.sev-pill input[type="radio"] { opacity:0; position:absolute; width:0; height:0; }
.sev-pill:hover { transform:translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.07); }
.sev-pill.sel { border-color: var(--sc); background: color-mix(in srgb, var(--sc) 10%, white); }
html.dark .sev-pill { border-color: #1e2d42; }
/* Dark mode: pill severity terpilih pakai bg gelap agar "SLA X jam" terbaca */
html.dark .sev-pill.sel {
    background: color-mix(in srgb, var(--sc) 28%, #1e293b);
    color: #e2e8f0;
}
html.dark .sev-pill.sel .text-muted { color: rgba(226,232,240,.85) !important; }
html.dark .sev-pill.sel .bi { color: var(--sc) !important; }

/* Status info box (catatan perubahan) */
.status-info-box { background: #fffbeb; border: 1px solid #fde68a; }
.status-info-list { color: #92400e; }
html.dark .status-info-box { background: rgba(245,158,11,.15); border-color: rgba(245,158,11,.4); }
html.dark .status-info-list { color: #fcd34d; }
html.dark .status-info-box .text-warning-emphasis { color: #fcd34d !important; }

/* Banner lock/info di dark mode */
html.dark .banner-lock {
    background: linear-gradient(135deg,rgba(254,226,226,.2),rgba(254,202,202,.15)) !important;
    border-color: rgba(248,113,113,.4) !important;
}
html.dark .banner-lock .banner-desc { color: #fca5a5 !important; }
html.dark .banner-mine {
    background: linear-gradient(135deg,rgba(220,252,231,.15),rgba(187,247,208,.1)) !important;
    border-color: rgba(34,197,94,.4) !important;
}
html.dark .banner-mine .banner-desc { color: #86efac !important; }
html.dark .banner-info {
    background: linear-gradient(135deg,rgba(219,234,254,.15),rgba(191,219,254,.1)) !important;
    border-color: rgba(59,130,246,.4) !important;
}
html.dark .banner-info .banner-desc { color: #93c5fd !important; }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<div class="edit-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <div class="tag mb-1"><i class="bi bi-pencil-square me-1"></i>Update Tiket</div>
            <div class="code">{{ $ticket->kode_tiket }}</div>
            <div class="meta d-flex flex-wrap gap-3 mt-1">
                <span><i class="bi bi-person me-1"></i>{{ $ticket->user?->name ?? '-' }}</span>
                <span><i class="bi bi-building me-1"></i>{{ $ticket->unit ?? '-' }}</span>
                <span><i class="bi bi-calendar3 me-1"></i>{{ $ticket->created_at?->format('d M Y, H:i') }}</span>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-2 mt-1">
            {!! $ticket->statusBadge() !!}
            <span class="badge"
                  style="background:{{ $ticket->ticketCategory?->color ?? '#3b82f6' }}22;
                         color:{{ $ticket->ticketCategory?->color ?? '#3b82f6' }};
                         border:1px solid {{ $ticket->ticketCategory?->color ?? '#3b82f6' }}44;
                         font-size:.75rem;">
                <i class="bi {{ $ticket->ticketCategory?->icon ?? 'bi-tag' }} me-1"></i>
                {{ $ticket->getNamaKategori() }}
            </span>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- ── KOLOM KIRI — Info Tiket ── --}}
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-primary"></i>
                <span class="fw-semibold">Ringkasan Tiket</span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="lbl">Pelapor</div>
                    <div class="val">{{ $ticket->user?->name ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="lbl">Unit</div>
                    <div class="val">{{ $ticket->unit ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="lbl">Kategori</div>
                    <div class="val">{!! $ticket->kategoriIcon() !!}</div>
                </div>
                <div class="info-row">
                    <div class="lbl">Status</div>
                    <div class="val">{!! $ticket->statusBadge() !!}</div>
                </div>
                <div class="info-row">
                    <div class="lbl">Teknisi</div>
                    <div class="val">{{ $ticket->handler?->name ?? '-' }}</div>
                </div>
                @if($ticket->sla_deadline)
                <div class="info-row">
                    <div class="lbl">Deadline SLA</div>
                    <div class="val">
                        @if($ticket->isOverdue())
                            <span class="badge bg-danger">OVERDUE</span>
                        @else
                            <span class="badge bg-success" style="font-size:.75rem;">{{ $ticket->sla_deadline->diffForHumans() }}</span>
                        @endif
                        <div class="text-muted" style="font-size:.73rem;margin-top:.15rem;">{{ $ticket->sla_deadline->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                @endif
                <div class="info-row" style="border-bottom:none;">
                    <div class="lbl">Deskripsi</div>
                    <div class="val" style="font-size:.82rem;font-weight:400;line-height:1.6;">{{ $ticket->deskripsi }}</div>
                </div>

                {{-- Foto --}}
                @if($ticket->foto)
                <hr class="my-2">
                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:.5rem;">
                    <i class="bi bi-image me-1"></i>Lampiran
                </div>
                <img src="{{ asset('storage/'.$ticket->foto) }}" alt="Foto Tiket"
                     class="img-fluid rounded border" style="max-height:180px;border-radius:.5rem !important;">
                @endif

                {{-- Detail waktu --}}
                @if($ticket->waktu_mulai || $ticket->waktu_selesai)
                <hr class="my-2">
                <div class="d-flex gap-3 flex-wrap">
                    @if($ticket->waktu_mulai)
                    <div>
                        <div style="font-size:.68rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Mulai Dikerjakan</div>
                        <div style="font-size:.82rem;font-weight:600;">{{ $ticket->waktu_mulai->format('d/m/Y H:i') }}</div>
                    </div>
                    @endif
                    @if($ticket->waktu_selesai)
                    <div>
                        <div style="font-size:.68rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Selesai</div>
                        <div style="font-size:.82rem;font-weight:600;">{{ $ticket->waktu_selesai->format('d/m/Y H:i') }}</div>
                    </div>
                    @endif
                    @if($ticket->durasi_menit)
                    <div>
                        <div style="font-size:.68rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Durasi</div>
                        <div class="fw-bold text-success" style="font-size:.82rem;">{{ $ticket->durasiFormatted() }}</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── KOLOM KANAN — Form Update ── --}}
    <div class="col-lg-7">

        {{-- ── BANNER: Lock Status ── --}}
        @if($isLocked)
        <div class="rounded-3 p-3 mb-3 d-flex align-items-start gap-3 banner-lock"
             style="background:linear-gradient(135deg,#fff1f2,#ffe4e6);border:1.5px solid #fecdd3;">
            <i class="bi bi-lock-fill text-danger fs-4 mt-1 flex-shrink-0"></i>
            <div>
                <div class="fw-bold text-danger mb-1">Tiket Dikunci — Tidak Dapat Diedit</div>
                <div class="banner-desc" style="font-size:.85rem;color:#9f1239;">
                    Tiket ini sedang dikerjakan oleh
                    <strong>{{ $ticket->handler?->name ?? 'teknisi lain' }}</strong>.
                    Perubahan status dan klasifikasi hanya dapat dilakukan oleh teknisi yang bersangkutan.
                </div>
                <div class="mt-2">
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-danger" style="font-size:.8rem;">
                        <i class="bi bi-arrow-left me-1"></i>Lihat Detail Tiket
                    </a>
                </div>
            </div>
        </div>
        @elseif($lockedByMe)
        <div class="rounded-3 p-3 mb-3 d-flex align-items-center gap-3 banner-mine"
             style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #86efac;">
            <i class="bi bi-person-check-fill text-success fs-4 flex-shrink-0"></i>
            <div>
                <div class="fw-bold text-success mb-0">Tiket Anda</div>
                <div class="banner-desc" style="font-size:.82rem;color:#166534;">Anda adalah teknisi yang menangani tiket ini.</div>
            </div>
        </div>
        @else
        {{-- Belum ada teknisi, tampilkan info --}}
        <div class="rounded-3 p-3 mb-3 d-flex align-items-center gap-3 banner-info"
             style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #93c5fd;">
            <i class="bi bi-info-circle-fill text-primary fs-4 flex-shrink-0"></i>
            <div class="banner-desc" style="font-size:.82rem;color:#1e3a8a;">
                Tiket ini belum ditugaskan. Ubah status ke <strong>Diproses</strong>
                untuk mengambil tiket ini sebagai penanganan Anda.
            </div>
        </div>
        @endif

        {{-- ── BANNER: Kategori Tidak Sesuai ── --}}
        @if(isset($categoryAllowed) && !$categoryAllowed)
        <div class="rounded-3 p-3 mb-3 d-flex align-items-start gap-3"
             style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1.5px solid #fdba74;">
            <i class="bi bi-exclamation-triangle-fill fs-4 mt-1 flex-shrink-0" style="color:#f59e0b;"></i>
            <div>
                <div class="fw-bold mb-1" style="color:#92400e;">Kategori Tiket Tidak Sesuai</div>
                <div style="font-size:.85rem;color:#92400e;">
                    Tiket ini berkategori <strong>{{ $ticket->kategori }}</strong>.
                    Anda hanya dapat menangani tiket kategori <strong>{{ $allowedKategori }}</strong>.
                    Perubahan status tidak akan disimpan.
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('tickets.update', $ticket->id) }}"
              @if($isLocked) id="lockedForm" @endif>
            @csrf @method('PUT')

            {{-- Disable semua field jika locked --}}
            @if($isLocked)
            <fieldset disabled style="opacity:.55;pointer-events:none;">
            @endif

            {{-- Tingkat Keparahan --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-speedometer2 text-danger"></i>
                    <span class="fw-semibold">Tingkat Keparahan & SLA</span>
                </div>
                <div class="card-body">
                    <label class="form-label fw-semibold" style="font-size:.875rem;">Pilih keparahan masalah</label>
                    <div class="sev-grid" id="sevGrid">
                        @php
                        $sevs = [
                            'Low'      => ['color'=>'#22c55e','icon'=>'bi-circle',           'hint'=>'SLA 24 jam'],
                            'Medium'   => ['color'=>'#3b82f6','icon'=>'bi-exclamation-circle','hint'=>'SLA 8 jam'],
                            'High'     => ['color'=>'#f59e0b','icon'=>'bi-exclamation-triangle','hint'=>'SLA 4 jam'],
                            'Critical' => ['color'=>'#ef4444','icon'=>'bi-exclamation-octagon','hint'=>'SLA 1 jam'],
                        ];
                        @endphp
                        @foreach($sevs as $sev => $cfg)
                        <label class="sev-pill {{ old('tingkat_keparahan', $ticket->tingkat_keparahan) === $sev ? 'sel' : '' }}"
                               style="--sc:{{ $cfg['color'] }};"
                               id="sevLabel{{ $sev }}">
                            <input type="radio" name="tingkat_keparahan" value="{{ $sev }}"
                                   {{ old('tingkat_keparahan', $ticket->tingkat_keparahan) === $sev ? 'checked' : '' }}
                                   onchange="highlightSev('{{ $sev }}')">
                            <i class="bi {{ $cfg['icon'] }}" style="color:{{ $cfg['color'] }};font-size:1rem;display:block;margin-bottom:.2rem;"></i>
                            <div class="fw-semibold" style="font-size:.82rem;">{{ $sev }}</div>
                            <div class="text-muted" style="font-size:.68rem;">{{ $cfg['hint'] }}</div>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <label class="sev-pill {{ !$ticket->tingkat_keparahan ? 'sel' : '' }}"
                               style="--sc:#94a3b8;display:inline-flex;align-items:center;gap:.5rem;padding:.4rem .75rem;">
                            <input type="radio" name="tingkat_keparahan" value=""
                                   {{ !$ticket->tingkat_keparahan ? 'checked' : '' }}
                                   onchange="highlightSev('')">
                            <span style="font-size:.82rem;">Belum ditentukan</span>
                        </label>
                    </div>
                    @error('tingkat_keparahan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Prioritas & Metode --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-flag text-warning"></i>
                    <span class="fw-semibold">Prioritas & Metode Penanganan</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="prioritas" class="form-label fw-semibold" style="font-size:.875rem;">Prioritas</label>
                            <select name="prioritas" id="prioritas"
                                    class="form-select @error('prioritas') is-invalid @enderror">
                                <option value="">Belum ditentukan</option>
                                @foreach(['Low','Medium','High','Urgent'] as $pri)
                                    <option value="{{ $pri }}" {{ old('prioritas', $ticket->prioritas) === $pri ? 'selected' : '' }}>
                                        {{ $pri }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prioritas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="metode_penanganan" class="form-label fw-semibold" style="font-size:.875rem;">Metode Penanganan</label>
                            <select name="metode_penanganan" id="metode_penanganan"
                                    class="form-select @error('metode_penanganan') is-invalid @enderror">
                                <option value="">Belum ditentukan</option>
                                @foreach(['Remote','Onsite'] as $mtd)
                                    <option value="{{ $mtd }}" {{ old('metode_penanganan', $ticket->metode_penanganan) === $mtd ? 'selected' : '' }}>
                                        @if($mtd === 'Remote') 🖥️ @else 🔧 @endif {{ $mtd }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metode_penanganan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left-right text-success"></i>
                    <span class="fw-semibold">Ubah Status Tiket</span>
                </div>
                <div class="card-body">
                    <input type="hidden" name="status" id="statusHidden" value="{{ old('status', $ticket->status) }}">
                    @php $currentStatus = old('status', $ticket->status); @endphp
                    <div class="status-grid mb-3">
                        @foreach(['Open','Diproses','Selesai','Closed'] as $st)
                        @php
                            $selClass = match($st) {
                                'Open'     => 'sel-open',
                                'Diproses' => 'sel-diproses',
                                'Selesai'  => 'sel-selesai',
                                'Closed'   => 'sel-closed',
                                default    => ''
                            };
                            $icon = match($st) {
                                'Open'     => 'bi-inbox',
                                'Diproses' => 'bi-gear',
                                'Selesai'  => 'bi-check-circle',
                                'Closed'   => 'bi-archive',
                                default    => 'bi-circle'
                            };
                            $hint = match($st) {
                                'Open'     => 'Menunggu penanganan',
                                'Diproses' => 'Waktu mulai tercatat',
                                'Selesai'  => 'Durasi dihitung otomatis',
                                'Closed'   => 'Tiket ditutup',
                                default    => ''
                            };
                        @endphp
                        <div class="status-pill {{ $currentStatus === $st ? $selClass : '' }}"
                             id="pill{{ $st }}" onclick="setStatus('{{ $st }}', '{{ $selClass }}')">
                            <i class="bi {{ $icon }} d-block mb-1" style="font-size:1.2rem;"></i>
                            <div class="fw-semibold" style="font-size:.85rem;">{{ $st }}</div>
                            <div class="text-muted" style="font-size:.68rem;">{{ $hint }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="rounded p-3 status-info-box" id="statusInfo">
                        <div class="fw-semibold text-warning-emphasis mb-1" style="font-size:.78rem;">
                            <i class="bi bi-info-circle me-1"></i>Catatan perubahan status
                        </div>
                        <ul class="mb-0 status-info-list" style="font-size:.78rem;padding-left:1.1rem;line-height:1.8;">
                            <li><strong>Diproses</strong>: waktu mulai & teknisi dicatat otomatis</li>
                            <li><strong>Selesai</strong>: waktu selesai & durasi dihitung otomatis</li>
                            <li><strong>Closed</strong>: tiket dikunci, tidak bisa diedit kembali</li>
                        </ul>
                    </div>
                    @error('status')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Tombol --}}
            @if($isLocked)
            </fieldset>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>{{ $isLocked ? 'Kembali ke Detail' : 'Batal' }}
                        </a>
                        @if(!$isLocked)
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary px-4" disabled>
                            <i class="bi bi-lock me-1"></i>Tiket Dikunci
                        </button>
                        @endif
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Status pill selector
function setStatus(val, cls) {
    // Clear all
    document.querySelectorAll('.status-pill').forEach(p => {
        p.className = p.className.replace(/\bsel-\w+/g, '').trim();
    });
    // Set selected
    const pill = document.getElementById('pill' + val);
    if (pill && cls) pill.classList.add(cls);
    document.getElementById('statusHidden').value = val;
}

// ── Severity pill selector
function highlightSev(val) {
    document.querySelectorAll('.sev-pill').forEach(p => {
        p.classList.remove('sel');
    });
    const sevs = ['Low','Medium','High','Critical'];
    if (val && sevs.includes(val)) {
        const lbl = document.getElementById('sevLabel' + val);
        if (lbl) lbl.classList.add('sel');
    }
}
</script>
@endpush
