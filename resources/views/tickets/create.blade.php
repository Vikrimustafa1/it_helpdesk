@extends('layouts.app')

@section('page-title', 'Buat Laporan Tiket')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Buat Laporan</li>
@endsection

@push('styles')
<style>
.form-hero {
    background: linear-gradient(135deg,#0f1f35 0%,#1a3358 60%,#1e3a5f 100%);
    border-radius: 1rem; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;
    color:#fff; position:relative; overflow:hidden;
    box-shadow: 0 8px 32px rgba(15,31,53,.35);
}
.form-hero::before {
    content:''; position:absolute; width:250px;height:250px;
    top:-80px;right:-60px;
    background:radial-gradient(circle,rgba(59,130,246,.2) 0%,transparent 70%);
    pointer-events:none;
}
.step-dot {
    width:26px;height:26px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:.7rem;font-weight:700;flex-shrink:0;
    background:rgba(255,255,255,.15); color:rgba(255,255,255,.5);
}
.step-dot.active { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 0 0 4px rgba(59,130,246,.3); }
.step-line { flex:1;height:2px;background:rgba(255,255,255,.12);margin:0 .35rem;border-radius:2px; }
.step-lbl  { font-size:.6rem;color:rgba(255,255,255,.55);text-align:center;margin-top:.25rem; }

/* Category cards */
.category-card {
    border:2px solid #e2e8f0; border-radius:.75rem; padding:.65rem .9rem;
    cursor:pointer; transition:all .15s; display:flex; align-items:center; gap:.6rem;
    user-select:none;
}
.category-card:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.08); }
.category-card.selected { border-color:var(--cc,#3b82f6); box-shadow:0 0 0 3px color-mix(in srgb,var(--cc,#3b82f6) 20%,transparent); }
.cat-icon { width:36px;height:36px;border-radius:.5rem;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0; }
html.dark .category-card { border-color:#1e2d42; }

/* Upload zone */
.upload-zone {
    border:2px dashed #cbd5e1; border-radius:.75rem; padding:1.5rem;
    text-align:center; cursor:pointer; transition:all .15s; position:relative;
}
.upload-zone:hover, .upload-zone.drag-over { border-color:#3b82f6; background:#f0f6ff; }
.upload-zone input[type="file"] { position:absolute;inset:0;opacity:0;cursor:pointer; }
html.dark .upload-zone { border-color:#243554; }
html.dark .upload-zone.drag-over { background:#0f2238; border-color:#3b82f6; }

.photo-preview { width:84px;height:84px;object-fit:cover;border-radius:.5rem;border:2px solid #e2e8f0;transition:transform .15s; }
.photo-preview:hover { transform:scale(1.06); }
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<div class="form-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <div style="font-size:.7rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.3rem;">
                <i class="bi bi-plus-circle me-1"></i>Buat Laporan Baru
            </div>
            <div style="font-size:1.2rem;font-weight:700;">Form Laporan Masalah IT</div>
            <div style="font-size:.8rem;color:rgba(255,255,255,.5);margin-top:.2rem;">
                Isi informasi dengan benar agar tim IT dapat menangani lebih cepat
            </div>
        </div>
        <div>
            <div class="d-flex align-items-center mb-1">
                <div><div class="step-dot active">1</div></div>
                <div class="step-line"></div>
                <div><div class="step-dot active">2</div></div>
                <div class="step-line"></div>
                <div><div class="step-dot active">3</div></div>
                <div class="step-line"></div>
                <div><div class="step-dot active">4</div></div>
            </div>
            <div class="d-flex" style="gap:.35rem;">
                <div class="step-lbl" style="width:26px;">Lokasi</div>
                <div style="flex:1;"></div>
                <div class="step-lbl" style="width:36px;">Kategori</div>
                <div style="flex:1;"></div>
                <div class="step-lbl" style="width:26px;">Desk.</div>
                <div style="flex:1;"></div>
                <div class="step-lbl" style="width:26px;">Foto</div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" id="mainForm">
    @csrf
    <div class="row g-3">

        {{-- ── KOLOM KIRI ── --}}
        <div class="col-lg-7">

            {{-- 1. Lokasi --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2">
                    <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;flex-shrink:0;">1</div>
                    <span class="fw-semibold">Unit / Lokasi</span>
                </div>
                <div class="card-body">
                    <label for="department_id" class="form-label" style="font-size:.875rem;font-weight:600;">
                        Pilih unit / departemen Anda <span class="text-danger">*</span>
                    </label>
                    <select name="department_id" id="department_id"
                            class="form-select @error('department_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('department_id', auth()->user()->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}{{ $dept->description ? ' — '.$dept->description : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- 2. Kategori --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2">
                    <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;flex-shrink:0;">2</div>
                    <span class="fw-semibold">Kategori Masalah</span>
                </div>
                <div class="card-body">
                    <label class="form-label" style="font-size:.875rem;font-weight:600;">
                        Pilih jenis masalah <span class="text-danger">*</span>
                    </label>
                    <input type="hidden" name="ticket_category_id" id="ticket_category_id"
                           value="{{ old('ticket_category_id') }}">

                    <div class="d-flex flex-column gap-2">
                        @foreach($categories as $cat)
                        <div class="category-card @if(old('ticket_category_id') == $cat->id) selected @endif"
                             style="--cc:{{ $cat->color }};"
                             data-id="{{ $cat->id }}"
                             onclick="selectCategory({{ $cat->id }}, '{{ $cat->color }}', this)">
                            <div class="cat-icon" style="background:{{ $cat->color }}18;">
                                <i class="bi {{ $cat->icon ?? 'bi-tag' }}" style="color:{{ $cat->color }};"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size:.875rem;">{{ $cat->name }}</div>
                            </div>
                            <i class="bi bi-check-circle-fill" id="check-{{ $cat->id }}"
                               style="color:{{ $cat->color }};font-size:1.1rem;display:{{ old('ticket_category_id') == $cat->id ? 'block' : 'none' }};"></i>
                        </div>
                        @endforeach
                    </div>
                    @error('ticket_category_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- 3. Deskripsi --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2 justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;flex-shrink:0;">3</div>
                        <span class="fw-semibold">Deskripsi Masalah</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnOpenTemplate"
                            style="font-size:.78rem;padding:.25rem .65rem;">
                        <i class="bi bi-layout-text-window-reverse me-1"></i>Pilih Template
                    </button>
                </div>
                <div class="card-body">
                    <textarea name="deskripsi" id="deskripsi" rows="5"
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              placeholder="Jelaskan masalah secara detail: sejak kapan terjadi, gejala yang muncul, perangkat apa, dan dampaknya..."
                              required>{{ old('deskripsi') }}</textarea>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Semakin detail, semakin cepat penanganan.</small>
                        <small class="text-muted" id="charCount">0 karakter</small>
                    </div>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

        </div>

        {{-- ── KOLOM KANAN ── --}}
        <div class="col-lg-5">

            {{-- 4. Upload Foto --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2">
                    <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;flex-shrink:0;">4</div>
                    <span class="fw-semibold">Lampiran Foto</span>
                    <span class="text-muted fw-normal" style="font-size:.78rem;">(opsional)</span>
                </div>
                <div class="card-body">
                    <div class="upload-zone" id="dropZone">
                        <input type="file" name="fotos[]" id="fotos" accept="image/png,image/jpeg" multiple
                               onchange="handleFiles(this.files)">
                        <i class="bi bi-cloud-upload fs-2 mb-2 d-block" style="color:#94a3b8;"></i>
                        <div class="fw-semibold" style="font-size:.85rem;">Klik atau seret foto ke sini</div>
                        <div class="text-muted" style="font-size:.73rem;">JPG / PNG &bull; Maks 5 foto &bull; 5 MB / foto</div>
                    </div>

                    {{-- Tombol kamera untuk mobile --}}
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success w-100"
                                onclick="document.getElementById('fotos_camera').click()">
                            <i class="bi bi-camera me-1"></i>Ambil Foto via Kamera HP
                        </button>
                        <input type="file" name="fotos[]" id="fotos_camera" accept="image/*" capture="environment"
                               style="display:none;" onchange="handleFiles(this.files)">
                    </div>

                    @error('fotos')  <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    @error('fotos.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                    <div id="preview-wrapper" style="display:none;margin-top:.75rem;">
                        <div class="d-flex flex-wrap gap-2 mb-2" id="preview-grid"></div>
                        <button type="button" id="clearPhotos" class="btn btn-sm btn-outline-danger" style="font-size:.78rem;">
                            <i class="bi bi-x me-1"></i>Hapus Semua
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="card border-0 mb-3 tips-card" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                <div class="card-body p-3">
                    <div class="fw-semibold text-primary mb-2" style="font-size:.82rem;">
                        <i class="bi bi-lightbulb me-1"></i>Tips Laporan Efektif
                    </div>
                    <ul class="mb-0" style="font-size:.78rem;color:#1e40af;padding-left:1rem;line-height:1.8;">
                        <li>Sebutkan sejak kapan masalah terjadi</li>
                        <li>Tuliskan nama perangkat yang bermasalah</li>
                        <li>Lampirkan foto error / layar jika ada</li>
                        <li>Jelaskan dampak ke pekerjaan Anda</li>
                    </ul>
                </div>
            </div>

            {{-- Submit --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            <i class="bi bi-send me-2"></i>Kirim Laporan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                    <p class="text-muted text-center mb-0 mt-2" style="font-size:.72rem;">
                        <i class="bi bi-shield-check me-1 text-success"></i>
                        Laporan langsung diterima tim IT Support
                    </p>
                </div>
            </div>

        </div>
    </div>
</form>

{{-- ══════════ MODAL TEMPLATE MASALAH ══════════ --}}
<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="padding:.85rem 1.25rem;">
                <div>
                    <h6 class="modal-title fw-semibold mb-0" id="templateModalLabel">
                        <i class="bi bi-layout-text-window-reverse me-2 text-primary"></i>Pilih Template Masalah
                    </h6>
                    <small class="text-muted">Klik baris untuk mengisi deskripsi secara otomatis</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">

                {{-- Sering Dilaporkan --}}
                @if($frequentIssues->isNotEmpty())
                <div class="px-3 pt-3 pb-2" id="sectionFrequent">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="fw-semibold" style="font-size:.82rem;">
                            <i class="bi bi-fire text-danger me-1"></i>Sering Dilaporkan
                        </span>
                        <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:.7rem;">
                            {{ $frequentIssues->count() }} masalah teratas
                        </span>
                    </div>
                    <div class="d-flex flex-wrap gap-2" id="frequentChips">
                        @foreach($frequentIssues as $issue)
                        <button type="button"
                                class="btn btn-sm frequent-chip text-start"
                                style="background:#fff7ed; border:1px solid #fed7aa; border-radius:.6rem;
                                       font-size:.78rem; max-width:280px; padding:.3rem .65rem; line-height:1.3;"
                                data-masalah="{{ $issue['masalah'] }}"
                                data-kategori="{{ $issue['kategori'] }}">
                            <span class="d-block text-truncate" style="max-width:230px;">{{ $issue['masalah'] }}</span>
                            <span style="font-size:.68rem; color:#9a3412;">
                                <i class="bi bi-bar-chart-fill me-1"></i>{{ $issue['total'] }}× dilaporkan
                            </span>
                        </button>
                        @endforeach
                    </div>
                    <hr class="my-3">
                </div>
                @endif

                {{-- Search + Filter --}}
                <div class="px-3 pb-2 d-flex gap-2 flex-wrap">
                    <div class="flex-grow-1 position-relative">
                        <i class="bi bi-search position-absolute" style="top:50%;left:.75rem;transform:translateY(-50%);color:#94a3b8;"></i>
                        <input type="text" id="tplSearch" class="form-control form-control-sm ps-4"
                               placeholder="Cari masalah... (mis. printer, wifi, login)">
                    </div>
                    <select id="tplFilter" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabel Template --}}
                <div class="px-3 pb-3">
                    <div id="tplEmpty" class="text-center py-4 text-muted" style="display:none;">
                        <i class="bi bi-search d-block fs-3 mb-1"></i>
                        Tidak ada template yang cocok.
                    </div>
                    <table class="table table-hover table-sm mb-0" id="tplTable">
                        <thead>
                            <tr>
                                <th style="width:110px;">Kategori</th>
                                <th>Deskripsi Masalah</th>
                                <th style="width:90px;"></th>
                            </tr>
                        </thead>
                        <tbody id="tplBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2">
                <small class="text-muted me-auto"><span id="tplCount">0</span> template tersedia</small>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Category card selection
function selectCategory(id, color, el) {
    // Clear all
    document.querySelectorAll('.category-card').forEach(c => {
        c.classList.remove('selected');
        const icon = c.querySelector('[id^="check-"]');
        if (icon) icon.style.display = 'none';
    });
    // Set selected
    el.classList.add('selected');
    el.style.setProperty('--cc', color);
    const chk = document.getElementById('check-' + id);
    if (chk) chk.style.display = 'block';
    document.getElementById('ticket_category_id').value = id;

    // Sinkronkan filter template dengan kategori yang dipilih
    const nameEl = el.querySelector('.fw-semibold');
    if (nameEl && typeof tplFilter !== 'undefined') {
        const catName = nameEl.textContent.trim();
        if (catName) {
            tplFilter.value = catName;
        }
    }
}

// ── Char counter
const deskEl = document.getElementById('deskripsi');
const charEl = document.getElementById('charCount');
if (deskEl && charEl) {
    deskEl.addEventListener('input', () => charEl.textContent = deskEl.value.length + ' karakter');
    charEl.textContent = deskEl.value.length + ' karakter';
}

// ── Foto upload preview
const fotosInput   = document.getElementById('fotos');
const prevWrapper  = document.getElementById('preview-wrapper');
const prevGrid     = document.getElementById('preview-grid');
const clearBtn     = document.getElementById('clearPhotos');
const dropZone     = document.getElementById('dropZone');

function handleFiles(files) {
    prevGrid.innerHTML = '';
    const arr = Array.from(files);
    if (!arr.length) { prevWrapper.style.display='none'; return; }
    if (arr.length > 5) { alert('Maksimal 5 foto.'); fotosInput.value=''; return; }
    let ok = true;
    arr.forEach(f => {
        if (!['image/jpeg','image/png'].includes(f.type)) { alert(f.name + ' bukan JPG/PNG.'); ok=false; }
        else if (f.size > 5*1024*1024) { alert(f.name + ' melebihi 5MB.'); ok=false; }
    });
    if (!ok) { fotosInput.value=''; prevWrapper.style.display='none'; return; }
    prevWrapper.style.display = 'block';
    arr.forEach(f => {
        const r = new FileReader();
        r.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'photo-preview';
            prevGrid.appendChild(img);
        };
        r.readAsDataURL(f);
    });
}

if (fotosInput) fotosInput.addEventListener('change', () => handleFiles(fotosInput.files));
if (clearBtn)   clearBtn.addEventListener('click', () => { fotosInput.value=''; prevGrid.innerHTML=''; prevWrapper.style.display='none'; });

// Drag-and-drop
if (dropZone) {
    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault(); dropZone.classList.remove('drag-over');
        handleFiles(e.dataTransfer.files);
    });
}

// ════════════════════════════════════════════════════════
// TEMPLATE MODAL LOGIC
// ════════════════════════════════════════════════════════
const FREQUENT_FROM_DB = @json($frequentIssues ?? []);

const STATIC_TEMPLATES = [
    // Hardware
    { kategori: 'Hardware', masalah: 'Komputer / laptop tidak bisa menyala sama sekali.' },
    { kategori: 'Hardware', masalah: 'Komputer menyala tapi layar tetap hitam / blank.' },
    { kategori: 'Hardware', masalah: 'Layar monitor gambar buram, berkedip, atau bergaris.' },
    { kategori: 'Hardware', masalah: 'Keyboard tidak terdeteksi atau sebagian tombol tidak berfungsi.' },
    { kategori: 'Hardware', masalah: 'Mouse tidak terdeteksi atau cursor tidak bergerak.' },
    { kategori: 'Hardware', masalah: 'Printer tidak bisa mencetak (offline / error lampu merah).' },
    { kategori: 'Hardware', masalah: 'Printer paper jam — kertas tersangkut di dalam printer.' },
    { kategori: 'Hardware', masalah: 'Printer hasil cetak buram, bergaris, atau warna tidak sesuai.' },
    { kategori: 'Hardware', masalah: 'Komputer sangat lemot dan sering not responding.' },
    { kategori: 'Hardware', masalah: 'Komputer sering restart / mati sendiri tanpa sebab jelas.' },
    { kategori: 'Hardware', masalah: 'Bunyi beep keras saat komputer dinyalakan.' },
    { kategori: 'Hardware', masalah: 'Port USB tidak berfungsi / perangkat USB tidak terdeteksi.' },
    { kategori: 'Hardware', masalah: 'UPS / stabilizer berbunyi alarm terus-menerus.' },
    { kategori: 'Hardware', masalah: 'Headset / speaker tidak berfungsi, tidak ada suara.' },
    { kategori: 'Hardware', masalah: 'Barcode scanner tidak terbaca oleh sistem.' },
    // Jaringan → Software
    { kategori: 'Software', masalah: 'Tidak bisa terhubung ke internet sama sekali.' },
    { kategori: 'Software', masalah: 'Koneksi internet sangat lambat (speedtest jauh di bawah normal).' },
    { kategori: 'Software', masalah: 'Koneksi WiFi sering putus-putus / disconnect sendiri.' },
    { kategori: 'Software', masalah: 'Kabel LAN sudah terpasang tapi tidak terdeteksi (limited / no connectivity).' },
    { kategori: 'Software', masalah: 'Tidak bisa mengakses server internal / shared folder.' },
    { kategori: 'Software', masalah: 'Tidak bisa mengakses SIMRS melalui jaringan lokal.' },
    { kategori: 'Software', masalah: 'IP address konflik dengan perangkat lain di jaringan.' },
    { kategori: 'Software', masalah: 'Port switch / panel jaringan di ruangan tidak berfungsi.' },
    { kategori: 'Software', masalah: 'Tidak bisa terhubung ke WiFi rumah sakit.' },
    { kategori: 'Software', masalah: 'Akses ke website / aplikasi tertentu diblokir / tidak bisa dibuka.' },
    { kategori: 'Software', masalah: 'VPN tidak bisa tersambung atau sering terputus.' },
    { kategori: 'Software', masalah: 'Printer jaringan (network printer) tidak terdeteksi oleh komputer.' },
    // SIMRS → Software
    { kategori: 'Software', masalah: 'Tidak bisa login ke aplikasi SIMRS (akun / password ditolak).' },
    { kategori: 'Software', masalah: 'Akun SIMRS terkunci setelah beberapa kali salah password.' },
    { kategori: 'Software', masalah: 'Data pasien / transaksi tidak tersimpan setelah klik Simpan.' },
    { kategori: 'Software', masalah: 'Aplikasi SIMRS error / crash / keluar sendiri saat digunakan.' },
    { kategori: 'Software', masalah: 'Laporan / rekap tidak bisa dicetak dari SIMRS.' },
    { kategori: 'Software', masalah: 'Tampilan SIMRS berantakan / tidak tampil dengan benar di browser.' },
    { kategori: 'Software', masalah: 'Fitur atau menu tertentu di SIMRS tidak muncul / tidak bisa diklik.' },
    { kategori: 'Software', masalah: 'Data pasien tidak ditemukan meskipun nomor rekam medis benar.' },
    { kategori: 'Software', masalah: 'Resep / tagihan tidak bisa diproses di modul farmasi / kasir.' },
    { kategori: 'Software', masalah: 'SIMRS loading sangat lama saat membuka halaman tertentu.' },
    { kategori: 'Software', masalah: 'Barcode pasien tidak terbaca di sistem SIMRS.' },
    { kategori: 'Software', masalah: 'Jadwal dokter / antrian tidak muncul di modul pendaftaran.' },
];

function buildCombinedTemplates() {
    const dbSet = new Set(FREQUENT_FROM_DB.map(x => x.masalah.trim().toLowerCase()));
    const dbRows = FREQUENT_FROM_DB.map(x => ({ kategori: x.kategori, masalah: x.masalah, total: x.total, sumber: 'db' }));
    const staticRows = STATIC_TEMPLATES
        .filter(t => !dbSet.has(t.masalah.trim().toLowerCase()))
        .map(t => ({ kategori: t.kategori, masalah: t.masalah, total: null, sumber: 'static' }));
    return dbRows.concat(staticRows);
}

const ALL_TEMPLATES = buildCombinedTemplates();

const deskripsiTextarea = document.getElementById('deskripsi');
const btnOpen  = document.getElementById('btnOpenTemplate');
const tplSearch = document.getElementById('tplSearch');
const tplFilter = document.getElementById('tplFilter');
const tplBody   = document.getElementById('tplBody');
const tplEmpty  = document.getElementById('tplEmpty');
const tplCount  = document.getElementById('tplCount');
const tplTable  = document.getElementById('tplTable');
const modalEl   = document.getElementById('templateModal');
const tplModal  = modalEl ? new bootstrap.Modal(modalEl) : null;

const badgeMap = { Hardware: 'primary', Software: 'purple' };

function pilihTemplate(masalah, kategori) {
    deskripsiTextarea.value = masalah;
    charEl.textContent = masalah.length + ' karakter';
    tplModal.hide();
    deskripsiTextarea.focus();
}

function renderTable() {
    const q   = (tplSearch.value || '').toLowerCase().trim();
    const cat = tplFilter.value;
    const filtered = ALL_TEMPLATES.filter(t => {
        const matchCat = !cat || t.kategori === cat;
        const matchQ   = !q   || t.masalah.toLowerCase().includes(q) || t.kategori.toLowerCase().includes(q);
        return matchCat && matchQ;
    });
    tplBody.innerHTML = '';
    tplCount.textContent = filtered.length;
    tplEmpty.style.display = filtered.length === 0 ? 'block' : 'none';
    tplTable.style.display = filtered.length === 0 ? 'none'  : '';
    filtered.forEach(t => {
        const tr = document.createElement('tr');
        tr.style.cursor = 'pointer';
        const hotTag = t.sumber === 'db'
            ? '<span class="badge bg-danger bg-opacity-10 text-danger ms-1" style="font-size:.65rem;"><i class="bi bi-fire"></i> ' + t.total + '×</span>'
            : '';
        tr.innerHTML =
            '<td><span class="badge bg-' + (badgeMap[t.kategori] || 'secondary') + '">' + t.kategori + '</span></td>' +
            '<td style="font-size:.875rem;">' + t.masalah + hotTag + '</td>' +
            '<td class="text-end"><button type="button" class="btn btn-sm btn-primary py-0 px-2" style="font-size:.75rem;">Pakai</button></td>';
        tr.addEventListener('click', () => pilihTemplate(t.masalah, t.kategori));
        tplBody.appendChild(tr);
    });
}

document.querySelectorAll('.frequent-chip').forEach(chip => {
    chip.addEventListener('click', function() { pilihTemplate(this.dataset.masalah, this.dataset.kategori); });
});

if (btnOpen) {
    btnOpen.addEventListener('click', () => { renderTable(); tplModal.show(); });
}
if (tplSearch) tplSearch.addEventListener('input', renderTable);
if (tplFilter) tplFilter.addEventListener('change', renderTable);
if (modalEl) {
    modalEl.addEventListener('hidden.bs.modal', () => {
        // Reset hanya pencarian; filter kategori mengikuti pilihan user terakhir
        tplSearch.value = '';
    });
}
</script>
@endpush
