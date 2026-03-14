<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Helpdesk — Sistem Pelaporan Gangguan IT</title>
    <meta name="description" content="Sistem laporan gangguan IT yang cepat, mudah, dan terorganisir. Laporkan masalah Hardware dan Software langsung ke tim teknisi.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --blue: #3b82f6;
    --dark-blue: #1e3a8a;
    --purple: #8b5cf6;
    --green: #10b981;
    --bg-dark: #060d1a;
}
* { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { background: var(--bg-dark); color: #fff; overflow-x: hidden; }

/* ── Animated BG ── */
.bg-canvas {
    position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
}
.orb {
    position: absolute; border-radius: 50%; filter: blur(90px); opacity: .55;
    animation: floatOrb 12s ease-in-out infinite alternate;
}
.orb-1 { width: 600px; height: 600px; top: -200px; left: -150px;
    background: radial-gradient(circle, #3b82f650, transparent 70%); animation-delay: 0s; }
.orb-2 { width: 500px; height: 500px; top: 20%; right: -150px;
    background: radial-gradient(circle, #8b5cf650, transparent 70%); animation-delay: -4s; }
.orb-3 { width: 400px; height: 400px; bottom: 10%; left: 30%;
    background: radial-gradient(circle, #10b98140, transparent 70%); animation-delay: -8s; }
@keyframes floatOrb {
    0%   { transform: translate(0, 0) scale(1); }
    100% { transform: translate(40px, 60px) scale(1.12); }
}

/* Grid lines overlay */
.grid-overlay {
    position: fixed; inset: 0; z-index: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 60px 60px;
}

/* ── Navbar ── */
.navbar {
    position: sticky; top: 0; z-index: 1000;
    background: rgba(6, 13, 26, 0.75);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,.07);
    padding: .85rem 0;
}
.navbar-brand { font-weight: 800; font-size: 1.15rem; letter-spacing: -.4px; color: #fff !important; }
.logo-box {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(99,102,241,.5);
    flex-shrink: 0;
}
.nav-link-item {
    color: rgba(255,255,255,.6) !important; font-size: .875rem; font-weight: 500;
    transition: color .2s; text-decoration: none;
}
.nav-link-item:hover { color: #fff !important; }
.btn-login {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border: none; border-radius: 9px; padding: .5rem 1.35rem;
    font-weight: 600; font-size: .875rem; color: #fff;
    transition: all .25s; box-shadow: 0 4px 15px rgba(99,102,241,.4);
    text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
}
.btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,.55); color: #fff; }

/* ── Hero ── */
.hero {
    min-height: 100vh; display: flex; align-items: center;
    padding: 7rem 0 5rem; position: relative; z-index: 1;
}
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(59,130,246,.12); border: 1px solid rgba(59,130,246,.3);
    color: #93c5fd; border-radius: 20px; padding: .35rem 1rem;
    font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em;
    margin-bottom: 1.75rem;
}
.hero-eyebrow .dot { width: 6px; height: 6px; background: #3b82f6; border-radius: 50%;
    box-shadow: 0 0 6px #3b82f6; animation: pulse 2s ease-in-out infinite; }
@keyframes pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(.7); } }

.hero h1 {
    font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 900; line-height: 1.1;
    letter-spacing: -.03em;
}
.gradient-text {
    background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #34d399 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.hero-desc { font-size: 1.05rem; color: #94a3b8; max-width: 520px; line-height: 1.7; margin-top: 1.25rem; }

.btn-cta-primary {
    display: inline-flex; align-items: center; gap: .5rem;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff; border-radius: 12px; padding: .85rem 2.2rem;
    font-weight: 700; font-size: .95rem; text-decoration: none;
    transition: all .25s; box-shadow: 0 8px 30px rgba(99,102,241,.45);
}
.btn-cta-primary:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(99,102,241,.6); color: #fff; }

.btn-cta-ghost {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.85); border-radius: 12px; padding: .85rem 2rem;
    font-weight: 600; font-size: .95rem; text-decoration: none;
    transition: all .25s;
}
.btn-cta-ghost:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.3); color: #fff; }

/* ── Hero Right Card ── */
.hero-card {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 1.25rem; padding: 1.5rem;
    backdrop-filter: blur(10px);
    animation: fadeUpIn .8s ease both;
}
@keyframes fadeUpIn { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

.ticket-mock {
    background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.07);
    border-radius: 1rem; padding: 1rem 1.1rem; margin-bottom: .75rem;
    transition: background .2s;
}
.ticket-mock:last-child { margin-bottom: 0; }
.ticket-mock:hover { background: rgba(255,255,255,.06); }
.badge-status {
    padding: .2rem .65rem; border-radius: 20px; font-size: .68rem; font-weight: 600;
}
.badge-open    { background: rgba(59,130,246,.2);  color: #93c5fd; }
.badge-process { background: rgba(245,158,11,.2);  color: #fcd34d; }
.badge-done    { background: rgba(16,185,129,.2);  color: #6ee7b7; }

/* ── Section headers ── */
.section-eyebrow {
    display: inline-block;
    background: rgba(59,130,246,.12); border: 1px solid rgba(59,130,246,.25);
    color: #93c5fd; border-radius: 20px; padding: .3rem .9rem;
    font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .09em;
    margin-bottom: 1rem;
}
.section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; letter-spacing: -.02em; }

/* ── Stats row ── */
.stat-pill {
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
    border-radius: 1rem; padding: 1.25rem 1.5rem; text-align: center;
    transition: all .25s;
}
.stat-pill:hover { background: rgba(255,255,255,.07); transform: translateY(-3px); }
.stat-num { font-size: 2rem; font-weight: 900; }
.stat-lbl { font-size: .78rem; color: #94a3b8; margin-top: .2rem; }

/* ── Features ── */
.feature-card {
    background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.07);
    border-radius: 1.25rem; padding: 1.75rem; height: 100%;
    transition: all .25s; position: relative; overflow: hidden;
}
.feature-card::before {
    content: ''; position: absolute; inset: 0; border-radius: 1.25rem;
    opacity: 0; transition: opacity .25s;
}
.feature-card:hover { border-color: rgba(255,255,255,.15); transform: translateY(-4px); }
.feature-card:hover::before { opacity: 1; }
.fc-blue:hover   { box-shadow: 0 20px 50px rgba(59,130,246,.18); }
.fc-purple:hover { box-shadow: 0 20px 50px rgba(139,92,246,.18); }
.fc-green:hover  { box-shadow: 0 20px 50px rgba(16,185,129,.18); }
.fc-amber:hover  { box-shadow: 0 20px 50px rgba(245,158,11,.18); }
.fc-red:hover    { box-shadow: 0 20px 50px rgba(239,68,68,.18);  }
.fc-teal:hover   { box-shadow: 0 20px 50px rgba(20,184,166,.18); }

.feature-icon-wrap {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin-bottom: 1.1rem;
}
.fi-blue   { background: rgba(59,130,246,.15);  }
.fi-purple { background: rgba(139,92,246,.15);  }
.fi-green  { background: rgba(16,185,129,.15);  }
.fi-amber  { background: rgba(245,158,11,.15);  }
.fi-red    { background: rgba(239,68,68,.15);   }
.fi-teal   { background: rgba(20,184,166,.15);  }

.feature-card h6 { font-size: .95rem; font-weight: 700; margin-bottom: .5rem; color: #f1f5f9; }
.feature-card p  { font-size: .83rem; color: #94a3b8; line-height: 1.65; margin: 0; }

/* ── Steps ── */
.steps-wrap { position: relative; }
.step-item {
    display: flex; gap: 1.25rem; align-items: flex-start; padding-bottom: 2rem; position: relative;
}
.step-item:last-child { padding-bottom: 0; }
.step-line {
    position: absolute; left: 19px; top: 44px; bottom: 0;
    width: 2px; background: linear-gradient(to bottom, #3b82f6, rgba(59,130,246,.05));
}
.step-item.tech .step-line { background: linear-gradient(to bottom, #10b981, rgba(16,185,129,.05)); }
.step-num-circle {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff; font-weight: 700; font-size: .85rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; box-shadow: 0 4px 14px rgba(99,102,241,.4);
    position: relative; z-index: 1;
}
.step-item.tech .step-num-circle {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 4px 14px rgba(16,185,129,.4);
}
.step-content { background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.07); border-radius: 1rem; padding: 1rem 1.25rem; flex: 1; }
.step-content h6 { font-size: .88rem; font-weight: 700; color: #f1f5f9; margin-bottom: .3rem; }
.step-content p  { font-size: .8rem; color: #94a3b8; line-height: 1.6; margin: 0; }

/* ── Role cards ── */
.role-card {
    border-radius: 1.25rem; padding: 1.75rem;
    border: 1px solid; position: relative; overflow: hidden;
}
.role-card::before { content: ''; position: absolute; width: 200px; height: 200px; border-radius: 50%; top: -80px; right: -60px; filter: blur(50px); opacity: .4; }
.role-hw { background: rgba(59,130,246,.06); border-color: rgba(59,130,246,.25); }
.role-hw::before { background: #3b82f6; }
.role-sw { background: rgba(139,92,246,.06); border-color: rgba(139,92,246,.25); }
.role-sw::before { background: #8b5cf6; }
.role-icon {
    width: 56px; height: 56px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; margin-bottom: 1.25rem;
}
.ri-hw { background: rgba(59,130,246,.15); }
.ri-sw { background: rgba(139,92,246,.15); }
.role-tag {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .25rem .75rem; border-radius: 20px; font-size: .72rem; font-weight: 600; margin-bottom: .4rem;
}
.rt-hw { background: rgba(59,130,246,.15); color: #93c5fd; }
.rt-sw { background: rgba(139,92,246,.15); color: #c4b5fd; }
.role-card ul { list-style: none; padding: 0; margin: 0; }
.role-card ul li { font-size: .83rem; color: #cbd5e1; padding: .3rem 0; display: flex; gap: .6rem; align-items: flex-start; }
.role-card ul li::before { content: '✓'; font-weight: 700; flex-shrink: 0; margin-top: 1px; }
.role-hw ul li::before { color: #60a5fa; }
.role-sw ul li::before { color: #c4b5fd; }

/* ── CTA Bottom ── */
.cta-section {
    background: linear-gradient(135deg, rgba(59,130,246,.12) 0%, rgba(139,92,246,.12) 100%);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 2rem; padding: 4rem 2rem; text-align: center;
    position: relative; overflow: hidden;
}
.cta-section::before {
    content: ''; position: absolute; width: 400px; height: 400px;
    top: -150px; left: 50%; transform: translateX(-50%);
    background: radial-gradient(circle, rgba(99,102,241,.25), transparent 65%);
    pointer-events: none;
}

/* ── Footer ── */
footer { background: rgba(0,0,0,.3); border-top: 1px solid rgba(255,255,255,.06); }

/* ── Divider ── */
.section-divider { border: none; height: 1px; background: rgba(255,255,255,.06); }

/* ── Scroll fade ── */
.reveal {
    opacity: 0; transform: translateY(30px);
    transition: opacity .65s ease, transform .65s ease;
}
.reveal.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
    .hero { padding: 5rem 0 3rem; }
    .hero h1 { font-size: 2rem; }
}
</style>
</head>
<body>

<!-- Animated background -->
<div class="bg-canvas">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>
<div class="grid-overlay"></div>

<!-- ── NAVBAR ── -->
<nav class="navbar navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <div class="logo-box">
                <i class="bi bi-headset text-white" style="font-size:1.05rem;"></i>
            </div>
            IT Helpdesk
        </a>
        <div class="d-flex align-items-center gap-3">
            <a href="#fitur" class="nav-link-item d-none d-md-block">Fitur</a>
            <a href="#cara-penggunaan" class="nav-link-item d-none d-md-block">Panduan</a>
            <a href="{{ route('login') }}" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>
    </div>
</nav>

<!-- ── HERO ── -->
<section class="hero" id="home">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center g-5">
            <!-- Left -->
            <div class="col-lg-6" style="animation:fadeUpIn .7s ease both;">
                <div class="hero-eyebrow">
                    <span class="dot"></span>
                    Sistem Pelaporan IT Resmi
                </div>
                <h1>Laporkan Masalah IT <br><span class="gradient-text">Lebih Cepat &amp; Terorganisir</span></h1>
                <p class="hero-desc">Platform manajemen tiket gangguan IT untuk Hardware dan Software. Laporkan masalah, pantau status, dan berikan feedback — semuanya dalam satu dashboard.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('login') }}" class="btn-cta-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                    </a>
                    <a href="#cara-penggunaan" class="btn-cta-ghost">
                        <i class="bi bi-play-circle-fill"></i> Cara Penggunaan
                    </a>
                </div>

                <!-- Mini stats -->
                <div class="row g-3 mt-4">
                    <div class="col-4">
                        <div class="stat-pill">
                            <div class="stat-num gradient-text">24/7</div>
                            <div class="stat-lbl">Layanan Aktif</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-pill">
                            <div class="stat-num gradient-text">2</div>
                            <div class="stat-lbl">Tim Teknisi</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-pill">
                            <div class="stat-num gradient-text">SLA</div>
                            <div class="stat-lbl">Terukur</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: ticket mock UI -->
            <div class="col-lg-6" style="animation:fadeUpIn .9s ease .2s both;">
                <div class="hero-card">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:8px;height:8px;border-radius:50%;background:#ef4444;"></div>
                        <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;"></div>
                        <div style="width:8px;height:8px;border-radius:50%;background:#22c55e;"></div>
                        <span style="font-size:.72rem;color:#475569;margin-left:.5rem;">Antrian Tiket — IT Helpdesk</span>
                    </div>

                    <div class="ticket-mock">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div style="font-size:.72rem;color:#64748b;">IT-20260313-001</div>
                                <div style="font-size:.85rem;font-weight:600;color:#f1f5f9;margin:.15rem 0;">Printer tidak bisa print</div>
                                <div style="font-size:.72rem;color:#64748b;"><i class="bi bi-person me-1"></i>Dr. Bintang · Rawat Inap</div>
                            </div>
                            <div class="text-end">
                                <span class="badge-status badge-process">Diproses</span>
                                <div style="font-size:.68rem;color:#475569;margin-top:.3rem;"><i class="bi bi-pc-display me-1"></i>Hardware</div>
                            </div>
                        </div>
                    </div>

                    <div class="ticket-mock">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div style="font-size:.72rem;color:#64748b;">IT-20260313-002</div>
                                <div style="font-size:.85rem;font-weight:600;color:#f1f5f9;margin:.15rem 0;">SIMRS error saat login</div>
                                <div style="font-size:.72rem;color:#64748b;"><i class="bi bi-person me-1"></i>Dr. Yulia · IGD</div>
                            </div>
                            <div class="text-end">
                                <span class="badge-status badge-open">Open</span>
                                <div style="font-size:.68rem;color:#475569;margin-top:.3rem;"><i class="bi bi-code-square me-1"></i>Software</div>
                            </div>
                        </div>
                    </div>

                    <div class="ticket-mock">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div style="font-size:.72rem;color:#64748b;">IT-20260312-018</div>
                                <div style="font-size:.85rem;font-weight:600;color:#f1f5f9;margin:.15rem 0;">Mouse tidak terdeteksi</div>
                                <div style="font-size:.72rem;color:#64748b;"><i class="bi bi-person me-1"></i>Staf Admin · Keuangan</div>
                            </div>
                            <div class="text-end">
                                <span class="badge-status badge-done">Selesai</span>
                                <div style="font-size:.68rem;color:#475569;margin-top:.3rem;"><i class="bi bi-pc-display me-1"></i>Hardware</div>
                            </div>
                        </div>
                    </div>

                    <!-- Category routing diagram -->
                    <div style="margin-top:1.25rem;padding:.9rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:.85rem;">
                        <div style="font-size:.68rem;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">Routing Otomatis</div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="background:rgba(59,130,246,.15);color:#93c5fd;padding:.25rem .75rem;border-radius:20px;font-size:.75rem;font-weight:600;">
                                <i class="bi bi-pc-display me-1"></i>Hardware
                            </span>
                            <i class="bi bi-arrow-right" style="color:#3b82f6;"></i>
                            <span style="font-size:.75rem;color:#93c5fd;font-weight:600;">Teknisi Hardware</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="background:rgba(139,92,246,.15);color:#c4b5fd;padding:.25rem .75rem;border-radius:20px;font-size:.75rem;font-weight:600;">
                                <i class="bi bi-code-square me-1"></i>Software
                            </span>
                            <i class="bi bi-arrow-right" style="color:#8b5cf6;"></i>
                            <span style="font-size:.75rem;color:#c4b5fd;font-weight:600;">Teknisi Software</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ── FITUR ── -->
<section id="fitur" class="py-6 position-relative" style="z-index:1;padding:5rem 0;">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <div class="section-eyebrow">Fitur Sistem</div>
            <h2 class="section-title">Semua yang Anda Butuhkan</h2>
            <p style="color:#64748b;margin-top:.75rem;font-size:.95rem;">Platform lengkap untuk pelaporan dan penanganan gangguan IT di instansi Anda</p>
        </div>

        <div class="row g-3">
            <div class="col-md-4 reveal">
                <div class="feature-card fc-blue">
                    <div class="feature-icon-wrap fi-blue">
                        <i class="bi bi-ticket-perforated-fill" style="color:#3b82f6;"></i>
                    </div>
                    <h6>Tiket Online</h6>
                    <p>Buat & kirim laporan gangguan dengan foto bukti seketika dari browser, tanpa install aplikasi apapun.</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="feature-card fc-purple">
                    <div class="feature-icon-wrap fi-purple">
                        <i class="bi bi-ui-checks-grid" style="color:#8b5cf6;"></i>
                    </div>
                    <h6>Tracking Real-Time</h6>
                    <p>Pantau status tiket Anda secara langsung — Open, Diproses, hingga Selesai dengan timeline detail.</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="feature-card fc-green">
                    <div class="feature-icon-wrap fi-green">
                        <i class="bi bi-people-fill" style="color:#10b981;"></i>
                    </div>
                    <h6>Teknisi Spesialis</h6>
                    <p>Tiket Hardware ditangani Teknisi Hardware, tiket Software oleh Teknisi Software — tepat sasaran dan efisien.</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="feature-card fc-amber">
                    <div class="feature-icon-wrap fi-amber">
                        <i class="bi bi-chat-left-text-fill" style="color:#f59e0b;"></i>
                    </div>
                    <h6>Catatan Dua Arah</h6>
                    <p>Pelapor dapat menambah catatan progres saat tiket sedang ditangani untuk menghindari miskomunikasi.</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="feature-card fc-red">
                    <div class="feature-icon-wrap fi-red">
                        <i class="bi bi-star-fill" style="color:#ef4444;"></i>
                    </div>
                    <h6>Feedback &amp; Rating</h6>
                    <p>Rating ≤ 2 otomatis membuka ulang tiket — <em>zero tolerance</em> masalah yang belum benar-benar selesai.</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="feature-card fc-teal">
                    <div class="feature-icon-wrap fi-teal">
                        <i class="bi bi-camera-fill" style="color:#14b8a6;"></i>
                    </div>
                    <h6>Foto via Kamera HP</h6>
                    <p>Lampirkan foto langsung dari kamera smartphone Anda tanpa perlu menyimpan dulu ke galeri.</p>
                </div>
            </div>
        </div>

        <!-- Role cards -->
        <div class="row g-3 mt-3">
            <div class="col-md-6 reveal">
                <div class="role-card role-hw">
                    <div class="role-icon ri-hw"><i class="bi bi-pc-display text-primary" style="font-size:1.6rem;"></i></div>
                    <div class="role-tag rt-hw"><i class="bi bi-tools"></i> Teknisi Hardware</div>
                    <h5 style="font-weight:700;margin:.5rem 0 1rem;font-size:1.05rem;">Spesialis Gangguan Fisik</h5>
                    <ul>
                        <li>Printer, scanner, dan perangkat cetak</li>
                        <li>Komputer, monitor, keyboard, mouse</li>
                        <li>Kabel jaringan &amp; perangkat keras lainnya</li>
                        <li>Hanya menangani tiket kategori Hardware</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 reveal">
                <div class="role-card role-sw">
                    <div class="role-icon ri-sw"><i class="bi bi-code-square" style="color:#8b5cf6;font-size:1.6rem;"></i></div>
                    <div class="role-tag rt-sw"><i class="bi bi-laptop"></i> Teknisi Software</div>
                    <h5 style="font-weight:700;margin:.5rem 0 1rem;font-size:1.05rem;">Spesialis Sistem &amp; Aplikasi</h5>
                    <ul>
                        <li>Aplikasi SIMRS &amp; sistem informasi</li>
                        <li>Koneksi jaringan &amp; internet</li>
                        <li>Error program &amp; bug sistem</li>
                        <li>Hanya menangani tiket kategori Software</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ── CARA PENGGUNAAN ── -->
<section id="cara-penggunaan" style="padding:5rem 0;position:relative;z-index:1;">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <div class="section-eyebrow">Panduan</div>
            <h2 class="section-title">Cara Penggunaan</h2>
            <p style="color:#64748b;margin-top:.75rem;font-size:.95rem;">Ikuti langkah-langkah berikut untuk mulai menggunakan sistem</p>
        </div>

        <div class="row g-5">
            <!-- Pelapor -->
            <div class="col-lg-6 reveal">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(59,130,246,.15);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-fill" style="color:#3b82f6;"></i>
                    </div>
                    <span style="font-weight:700;font-size:.95rem;">Untuk Pelapor (User)</span>
                </div>

                @php
                $stepsUser = [
                    ['icon' => 'bi-box-arrow-in-right', 'title' => 'Login ke Sistem', 'desc' => 'Masuk menggunakan akun yang telah diberikan oleh administrator.'],
                    ['icon' => 'bi-plus-circle-fill',   'title' => 'Buat Tiket Baru',  'desc' => 'Klik "Buat Laporan", pilih kategori (Hardware/Software), isi deskripsi, dan lampirkan foto jika ada.'],
                    ['icon' => 'bi-camera-fill',         'title' => 'Lampirkan Foto',   'desc' => 'Upload dari penyimpanan atau langsung ambil dari kamera HP untuk memperjelas masalah.'],
                    ['icon' => 'bi-eye-fill',            'title' => 'Pantau Status',    'desc' => 'Buka "Riwayat Tiket" untuk memantau status: Open → Diproses → Selesai.'],
                    ['icon' => 'bi-chat-left-text',      'title' => 'Tambah Catatan',   'desc' => 'Saat status "Diproses", tambahkan catatan jika masalah masih berlanjut atau ada info baru.'],
                    ['icon' => 'bi-star-fill',           'title' => 'Beri Feedback',    'desc' => 'Setelah tiket Selesai, berikan rating kepuasan 1–5. Rating ≤ 2 akan membuka tiket kembali.'],
                ];
                @endphp

                <div class="steps-wrap">
                    @foreach($stepsUser as $i => $step)
                    <div class="step-item">
                        @if(!$loop->last)<div class="step-line"></div>@endif
                        <div class="step-num-circle">{{ $i + 1 }}</div>
                        <div class="step-content">
                            <h6><i class="bi {{ $step['icon'] }} me-1" style="color:#60a5fa;font-size:.8rem;"></i>{{ $step['title'] }}</h6>
                            <p>{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Teknisi -->
            <div class="col-lg-6 reveal">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,.15);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-tools" style="color:#10b981;"></i>
                    </div>
                    <span style="font-weight:700;font-size:.95rem;">Untuk Teknisi</span>
                </div>

                @php
                $stepsTech = [
                    ['icon' => 'bi-box-arrow-in-right',         'title' => 'Login ke Sistem',     'desc' => 'Login dengan akun teknisi. Anda akan diarahkan otomatis ke Dashboard IT.'],
                    ['icon' => 'bi-list-check',                  'title' => 'Lihat Antrian Tiket', 'desc' => 'Semua tiket masuk terlihat, namun Anda hanya bisa menangani tiket sesuai spesialisasi Anda.'],
                    ['icon' => 'bi-pencil-square',               'title' => 'Update Status',       'desc' => 'Ubah status tiket, tambah catatan progres penanganan, dan lampirkan foto dokumentasi.'],
                    ['icon' => 'bi-file-earmark-bar-graph-fill', 'title' => 'Cetak Laporan',       'desc' => 'Filter laporan berdasarkan tanggal, kategori, atau nama teknisi, lalu export ke PDF/Excel.'],
                ];
                @endphp

                <div class="steps-wrap">
                    @foreach($stepsTech as $i => $step)
                    <div class="step-item tech">
                        @if(!$loop->last)<div class="step-line"></div>@endif
                        <div class="step-num-circle">{{ $i + 1 }}</div>
                        <div class="step-content">
                            <h6><i class="bi {{ $step['icon'] }} me-1" style="color:#34d399;font-size:.8rem;"></i>{{ $step['title'] }}</h6>
                            <p>{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Info box -->
                <div style="margin-top:1.5rem;padding:1.1rem 1.25rem;background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.2);border-radius:1rem;">
                    <div class="d-flex gap-2 align-items-start">
                        <i class="bi bi-info-circle-fill mt-1" style="color:#10b981;font-size:.9rem;flex-shrink:0;"></i>
                        <div>
                            <div style="font-size:.8rem;font-weight:700;color:#34d399;margin-bottom:.4rem;">Pembagian Spesialisasi</div>
                            <p style="font-size:.76rem;color:#64748b;line-height:1.65;margin:0;">
                                <strong style="color:#93c5fd;">Teknisi Hardware</strong> — Hanya bisa menangani tiket Hardware (printer, PC, dll).<br>
                                <strong style="color:#c4b5fd;">Teknisi Software</strong> — Hanya bisa menangani tiket Software (SIMRS, jaringan, dll).
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ── CTA ── -->
<section style="padding:5rem 0;position:relative;z-index:1;">
    <div class="container">
        <div class="cta-section reveal">
            <div class="position-relative" style="z-index:1;">
                <div class="section-eyebrow" style="margin-bottom:1.25rem;">Siap Memulai?</div>
                <h2 class="section-title mb-3">Mulai Laporkan Masalah IT Anda</h2>
                <p style="color:#64748b;font-size:.95rem;max-width:480px;margin:.5rem auto 2.25rem;">Login dan buat tiket sekarang — tim teknisi spesialis siap membantu Anda dengan cepat.</p>
                <a href="{{ route('login') }}" class="btn-cta-primary" style="font-size:1rem;padding:.9rem 2.5rem;">
                    <i class="bi bi-box-arrow-in-right"></i> Login ke Sistem
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer class="py-4" style="position:relative;z-index:1;">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <a class="d-flex align-items-center gap-2 text-decoration-none" href="/">
                <div class="logo-box" style="width:28px;height:28px;border-radius:7px;">
                    <i class="bi bi-headset text-white" style="font-size:.8rem;"></i>
                </div>
                <span style="font-weight:700;font-size:.875rem;color:#fff;">IT Helpdesk</span>
            </a>
            <p style="font-size:.75rem;color:#334155;margin:0;">
                &copy; {{ date('Y') }} IT Helpdesk — Sistem Pelaporan Gangguan IT
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Scroll reveal
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('visible'), i * 80);
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });
reveals.forEach(el => observer.observe(el));
</script>
</body>
</html>
