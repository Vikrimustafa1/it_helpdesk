<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    {{-- Anti-FOUC: apply dark class SEBELUM render dimulai --}}
    <script>if(localStorage.getItem('theme')==='dark')document.documentElement.classList.add('dark');</script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', config('app.name')) | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg-start: #0f1f35;
            --sidebar-bg-end:   #162d4a;
            --sidebar-accent:   #3b82f6;
            --sidebar-width:    260px;
            --topbar-height:    60px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
            background: #f0f4f8;
            color: #1e293b;
        }

        a, a:hover { text-decoration: none; }

        /* ── Sidebar ─────────────────────────────────── */
        .sidebar {
            min-height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(160deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
            color: #fff;
            position: sticky;
            top: 0;
            align-self: flex-start;
            transition: transform 0.25s ease-in-out;
            box-shadow: 4px 0 20px rgba(0,0,0,.15);
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 1.25rem 1rem 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            margin-bottom: 0.75rem;
        }
        .sidebar-brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(59,130,246,.4);
        }
        .sidebar-brand-text .title  { font-size: .95rem; font-weight: 700; color: #fff; line-height:1.2; }
        .sidebar-brand-text .sub    { font-size: .7rem;  color: rgba(255,255,255,.5); }

        .sidebar-section-label {
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
            padding: .6rem 1rem .25rem;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.7);
            border-radius: .5rem;
            padding: .5rem .85rem;
            font-size: .875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .6rem;
            margin: 1px 8px;
            transition: background .15s, color .15s;
        }
        .sidebar .nav-link i { font-size: 1rem; flex-shrink: 0; }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }
        .sidebar .nav-link.active {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            color: #fff;
            box-shadow: 0 4px 12px rgba(59,130,246,.35);
        }

        .sidebar-user {
            border-top: 1px solid rgba(255,255,255,.08);
            padding: .85rem 1rem;
            font-size: .8rem;
        }
        .sidebar-avatar {
            width: 32px; height: 32px;
            background: rgba(255,255,255,.15);
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
        }

        /* ── Topbar ──────────────────────────────────── */
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e8edf3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
        }
        .topbar-title {
            font-size: .95rem;
            font-weight: 600;
            color: #1e293b;
        }
        .topbar-user-btn {
            display: flex; align-items: center; gap: .6rem;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 2rem; padding: .35rem .75rem .35rem .45rem;
            cursor: pointer; transition: background .15s, box-shadow .15s;
        }
        .topbar-user-btn:hover { background: #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .topbar-avatar {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 50%; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem;
        }
        .topbar-name  { font-size: .8rem; font-weight: 600; color: #1e293b; line-height: 1.2; }
        .topbar-role  { font-size: .7rem; color: #64748b; }

        /* ── Content ─────────────────────────────────── */
        .content-wrapper { padding: 1.75rem; }

        /* ── Cards ───────────────────────────────────── */
        .card {
            border-radius: 1rem;
            border: none;
        }
        .card-header {
            border-bottom: 1px solid #f1f5f9;
            background: #fff;
            border-radius: 1rem 1rem 0 0 !important;
            padding: .9rem 1.25rem;
        }

        /* ── Stat Cards ──────────────────────────────── */
        .stat-card {
            border-radius: 1rem;
            border: none;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: transform .18s, box-shadow .18s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,.12) !important; }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-card .stat-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; opacity: .75; }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }

        /* Stat colour variants */
        .stat-blue   { background: linear-gradient(135deg,#eff6ff,#dbeafe); color:#1e40af; }
        .stat-blue   .stat-icon { background:#bfdbfe; color:#1d4ed8; }
        .stat-orange { background: linear-gradient(135deg,#fff7ed,#fed7aa); color:#9a3412; }
        .stat-orange .stat-icon { background:#fdba74; color:#c2410c; }
        .stat-yellow { background: linear-gradient(135deg,#fefce8,#fef08a); color:#854d0e; }
        .stat-yellow .stat-icon { background:#fde047; color:#a16207; }
        .stat-green  { background: linear-gradient(135deg,#f0fdf4,#bbf7d0); color:#14532d; }
        .stat-green  .stat-icon { background:#86efac; color:#16a34a; }
        .stat-red    { background: linear-gradient(135deg,#fff1f2,#fecdd3); color:#9f1239; }
        .stat-red    .stat-icon { background:#fda4af; color:#e11d48; }
        .stat-slate  { background: linear-gradient(135deg,#f8fafc,#e2e8f0); color:#334155; }
        .stat-slate  .stat-icon { background:#cbd5e1; color:#475569; }

        /* ── Tables ──────────────────────────────────── */
        .table thead th {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: .7rem 1rem;
        }
        .table tbody td { padding: .75rem 1rem; vertical-align: middle; font-size: .875rem; }
        .table-hover tbody tr:hover { background: #f0f6ff; }

        /* ── Breadcrumb ──────────────────────────────── */
        .breadcrumb { margin-bottom: 1rem; font-size: .8rem; }
        .breadcrumb-item.active { color: #64748b; }

        /* ── Alerts ──────────────────────────────────── */
        .alert { border: none; border-radius: .75rem; font-size: .875rem; }

        /* ── Empty state ─────────────────────────────── */
        .empty-state { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
        .empty-state i { font-size: 2.5rem; margin-bottom: .75rem; display: block; }

        .page-link { border-radius: .375rem; font-size: .8rem; }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                z-index: 1040;
                transform: translateX(-100%);
                top: 0; left: 0;
            }
            .sidebar.show { transform: translateX(0); }
            .sidebar-backdrop {
                position: fixed; inset: 0;
                background: rgba(0,0,0,.4);
                z-index: 1035; display: none;
            }
            .sidebar-backdrop.show { display: block; }
        }

        /* ── Dark mode ───────────────────────────────── */
        html.dark {
            --bs-body-bg:          #0d1520;
            --bs-body-color:       #cbd5e1;
            --bs-card-bg:          #111c2d;
            --bs-card-border-color:#1e2d42;
            --bs-border-color:     #1e2d42;
            --bs-table-bg:         transparent;
            --bs-table-striped-bg: #122033;
            --bs-table-hover-bg:   #152035;
        }
        html.dark body              { background: #0d1520 !important; color: #cbd5e1; }
        html.dark .topbar           { background: #111c2d !important; border-color: #1e2d42 !important; box-shadow: 0 1px 8px rgba(0,0,0,.4); }
        html.dark .topbar-title     { color: #e2e8f0; }
        html.dark .topbar-user-btn  { background: #1a2740; border-color: #243554; color: #e2e8f0; }
        html.dark .topbar-user-btn:hover { background: #1e2f4a; }
        html.dark .topbar-user-btn .bi-chevron-down { color: #94a3b8; }
        html.dark .topbar-name      { color: #e2e8f0; }
        html.dark .topbar-role      { color: #94a3b8; }
        html.dark .content-wrapper  { background: #0d1520; }

        /* Card — semua elemen di dalam card */
        html.dark .card             { background: #111c2d !important; border-color: #1e2d42 !important; }
        html.dark .card-header      { background: #111c2d !important; border-color: #1e2d42 !important; color: #e2e8f0; }
        html.dark .card-body        { background: #111c2d !important; color: #cbd5e1; }
        html.dark .card-footer      { background: #111c2d !important; border-color: #1e2d42 !important; }

        /* Table — override semua variant Bootstrap */
        html.dark .table            { color: #cbd5e1 !important; --bs-table-bg: transparent; }
        html.dark .table > :not(caption) > * > * { background-color: #111c2d !important; color: #cbd5e1; border-color: #1e2d42; }
        html.dark .table thead th   { background: #0d1520 !important; border-color: #1e2d42 !important; color: #64748b !important; }
        html.dark .table-hover > tbody > tr:hover > * { background-color: #152035 !important; }
        html.dark .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: #122033 !important; }

        /* Stat cards — berwarna cerah di dark mode (serupa pelapor) */
        html.dark .stat-blue        { background: linear-gradient(135deg,#0f2942,#132f52) !important; color:#93c5fd; }
        html.dark .stat-blue .stat-icon  { background:rgba(59,130,246,.25); color:#60a5fa; }
        html.dark .stat-orange      { background: linear-gradient(135deg,#3d2200,#4a2a00) !important; color:#fdba74; }
        html.dark .stat-orange .stat-icon{ background:rgba(251,146,60,.25); color:#fb923c; }
        html.dark .stat-yellow      { background: linear-gradient(135deg,#3d3000,#4a3a00) !important; color:#fde047; }
        html.dark .stat-yellow .stat-icon{ background:rgba(250,204,21,.25); color:#facc15; }
        html.dark .stat-green       { background: linear-gradient(135deg,#003318,#004220) !important; color:#86efac; }
        html.dark .stat-green .stat-icon { background:rgba(34,197,94,.25); color:#4ade80; }
        html.dark .stat-red         { background: linear-gradient(135deg,#3d0008,#4a000d) !important; color:#fda4af; }
        html.dark .stat-red .stat-icon   { background:rgba(239,68,68,.25); color:#fb7185; }
        html.dark .stat-slate       { background: linear-gradient(135deg,#1a2a42,#223550) !important; color:#94a3b8; }
        html.dark .stat-slate .stat-icon { background:rgba(100,116,139,.25); color:#94a3b8; }

        /* Breadcrumb */
        html.dark .breadcrumb-item.active { color: #64748b; }
        html.dark .breadcrumb-item a { color: #60a5fa; }

        /* Form elements */
        html.dark .form-control,
        html.dark input[type="text"],
        html.dark input[type="password"],
        html.dark input[type="email"],
        html.dark input[type="file"],
        html.dark textarea           { background: #0d1520 !important; border-color: #243554 !important; color: #e2e8f0 !important; }
        html.dark .form-select       { background-color: #0d1520 !important; border-color: #243554 !important; color: #e2e8f0 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23cbd5e1' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
            background-position: right 0.75rem center !important; background-repeat: no-repeat !important; background-size: 16px 12px !important; }
        html.dark .form-select option { background: #0d1520; color: #e2e8f0; }
        html.dark .form-control:focus, html.dark .form-select:focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59,130,246,.15) !important; }
        html.dark .form-label        { color: #94a3b8; }
        html.dark .form-control::placeholder,
        html.dark textarea::placeholder { color: #475569 !important; }
        html.dark .form-control-plaintext { color: #cbd5e1; }

        /* Buttons */
        html.dark .btn-light         { background: #1a2740 !important; border-color: #243554 !important; color: #cbd5e1 !important; }
        html.dark .btn-light:hover   { background: #1e2f4a !important; color: #e2e8f0 !important; }
        html.dark .btn-light i       { color: inherit; }
        html.dark .btn-outline-secondary { border-color: #334155 !important; color: #94a3b8 !important; }
        html.dark .btn-outline-primary   { border-color: #3b82f6 !important; color: #60a5fa !important; }
        html.dark .btn-outline-danger    { border-color: #dc2626 !important; color: #f87171 !important; }

        /* Dropdown */
        html.dark .dropdown-menu     { background: #111c2d !important; border-color: #1e2d42 !important; }
        html.dark .dropdown-item     { color: #cbd5e1; }
        html.dark .dropdown-item:hover { background: #1a2740 !important; color: #e2e8f0; }
        html.dark .dropdown-divider  { border-color: #1e2d42 !important; }

        /* Alerts */
        html.dark .alert-warning    { background: #2d1d00 !important; border-color: #4a3200 !important; color: #fcd34d !important; }
        html.dark .alert-success    { background: #002210 !important; border-color: #003a1a !important; color: #86efac !important; }
        html.dark .alert-danger     { background: #1f0005 !important; border-color: #3b0008 !important; color: #fda4af !important; }
        html.dark .alert-info       { background: #001b36 !important; border-color: #003160 !important; color: #93c5fd !important; }
        html.dark .btn-close        { filter: invert(1); }

        /* List group */
        html.dark .list-group-item  { background: #111c2d !important; border-color: #1e2d42 !important; color: #cbd5e1; }

        /* Misc */
        html.dark .text-muted       { color: #e8eef4 !important; }
        html.dark .border           { border-color: #1e2d42 !important; }
        html.dark .border-bottom    { border-color: #1e2d42 !important; }
        html.dark .bg-white         { background: #111c2d !important; }
        html.dark .bg-light         { background: #152035 !important; }
        html.dark .empty-state      { color: #475569; }
        html.dark dl dt             { color: #64748b; }
        html.dark h1,html.dark h2,html.dark h3,html.dark h4,html.dark h5,html.dark h6 { color: #e2e8f0; }
        html.dark hr                { border-color: #1e2d42 !important; opacity:1; }
        html.dark .sidebar-backdrop { background: rgba(0,0,0,.6); }
        html.dark .page-link        { background: #111c2d !important; border-color: #243554 !important; color: #60a5fa !important; }
        html.dark .page-item.disabled .page-link { background: #0d1520 !important; color: #475569 !important; }
        html.dark .badge.bg-light   { background: #1e2d42 !important; color: #94a3b8 !important; }
        html.dark .badge.bg-secondary { background: #334155 !important; }
        html.dark .modal-content    { background: #111c2d !important; border-color: #1e2d42 !important; }
        html.dark .modal-header,
        html.dark .modal-footer     { border-color: #1e2d42 !important; }

        /* Smooth transition — hanya saat user klik toggle, bukan saat page load */
        .theme-ready body,
        .theme-ready .card,
        .theme-ready .card-body,
        .theme-ready .card-header,
        .theme-ready .topbar,
        .theme-ready .table,
        .theme-ready .form-control,
        .theme-ready .form-select {
            transition: background-color .2s ease, border-color .2s ease, color .15s ease;
        }

        html.dark .dark-toggle { cursor: pointer; }

        /* ── Notification Dropdown ───────────────────── */
        .notif-scroll-area {
            max-height: 320px;
            overflow-y: auto;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        /* Custom scrollbar (Webkit) */
        .notif-scroll-area::-webkit-scrollbar {
            width: 4px;
        }
        .notif-scroll-area::-webkit-scrollbar-track {
            background: transparent;
        }
        .notif-scroll-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .notif-scroll-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Notif item states */
        .notif-item        { color: #334155; }
        .notif-item:hover  { background: #f0f6ff !important; }
        .notif-item.unread { background: #eff6ff; }

        /* Dark mode: notif dropdown */
        html.dark .dropdown-menu .border-bottom,
        html.dark .notif-item { color: #cbd5e1; }
        html.dark .notif-item:hover  { background: #1a2740 !important; }
        html.dark .notif-item.unread { background: #0f2942 !important; }
        html.dark .notif-scroll-area::-webkit-scrollbar-thumb { background: #243554; }
        html.dark #notifDropdownList + div,
        html.dark [id="notifFooterLink"] { color: #60a5fa !important; }
        html.dark .dropdown-menu .border-top { border-color: #1e2d42 !important; }
        html.dark .dropdown-menu [style*="background:#f8fafc"],
        html.dark .dropdown-menu [style*="background:#fff"] {
            background: #111c2d !important;
        }

        /* ── Timeline catatan progress (show.blade.php) ── */
        html.dark .timeline-catatan-text {
            color: #cbd5e1 !important;
        }
        html.dark .timeline-badge-time {
            background: #1e2d42 !important;
            color: #94a3b8 !important;
            border-color: #243554 !important;
        }

        /* ── Feedback label kuning gelap (#854d0e) ── */
        html.dark .feedback-lbl-dark {
            color: #fde68a !important;
        }

        /* ── Tips card (create.blade.php) ── */
        html.dark .tips-card {
            background: linear-gradient(135deg, #0f2042, #1a2e58) !important;
        }
        html.dark .tips-card .fw-semibold {
            color: #93c5fd !important;
        }
        html.dark .tips-card ul {
            color: #bfdbfe !important;
        }

        /* ── Frequent chip button ── */
        html.dark .frequent-chip {
            background: #1a1a0a !important;
            border-color: #78350f !important;
            color: #fcd34d !important;
        }
        html.dark .frequent-chip span[style*="color:#9a3412"] {
            color: #fbbf24 !important;
        }

        /* ── Category card dark ── */
        html.dark .category-card .fw-semibold {
            color: #e2e8f0;
        }

        /* ── Dark override untuk elemen inline style umum ── */
        /* Teks warna gelap (#334155, #1e293b, #0f172a) yang hardcoded di card-body */
        html.dark .card-body p[style*="color:#334155"],
        html.dark .card-body p[style*="color: #334155"] {
            color: #cbd5e1 !important;
        }
        html.dark [style*="color:#1e3a8a"] {
            color: #93c5fd !important;
        }
        html.dark [style*="color:#166534"] {
            color: #86efac !important;
        }
        html.dark [style*="color:#9f1239"] {
            color: #fca5a5 !important;
        }
        html.dark [style*="color:#92400e"],
        html.dark [style*="color:#854d0e"] {
            color: #fde68a !important;
        }
        html.dark [style*="color:#1e40af"] {
            color: #93c5fd !important;
        }

    </style>

    @stack('styles')
</head>
<body>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<div class="d-flex">

    {{-- ═══════════ SIDEBAR ═══════════ --}}
    @auth
    <nav class="sidebar d-flex flex-column" id="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-hospital text-white"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="title">IT Helpdesk</div>
                <div class="sub">Rumah Sakit</div>
            </div>
        </div>

        {{-- Nav menu --}}
        <div class="flex-grow-1 py-1">
            <div class="sidebar-section-label">Menu</div>
            <ul class="nav flex-column">
                @if(auth()->user()->role === 'user')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.create') }}" class="nav-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i> Buat Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.my') }}" class="nav-link {{ request()->routeIs('tickets.my') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i> Riwayat Tiket
                        </a>
                    </li>
                @elseif(in_array(auth()->user()->role, ['teknisi_hardware', 'teknisi_software']))
                    <li class="nav-item">
                        <a href="{{ route('it.dashboard') }}" class="nav-link {{ request()->routeIs('it.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard IT
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('it.tickets.index') }}" class="nav-link {{ request()->routeIs('it.tickets.index') ? 'active' : '' }}">
                            <i class="bi bi-ticket-perforated"></i> Antrian Tiket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> Kelola User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i> Departemen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ticket-categories.index') }}" class="nav-link {{ request()->routeIs('ticket-categories.*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i> Kategori Tiket
                        </a>
                    </li>
                @elseif(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-shield-check"></i> Dashboard Admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> Kelola User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i> Departemen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.ticket-categories.index') }}" class="nav-link {{ request()->routeIs('admin.ticket-categories.*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i> Kategori Tiket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Bottom user info --}}
        <div class="sidebar-user d-flex align-items-center gap-2">
            <div class="sidebar-avatar">
                <i class="bi bi-person-fill text-white" style="font-size:.9rem;"></i>
            </div>
            <div style="min-width:0;">
                <div class="fw-semibold text-white text-truncate" style="font-size:.8rem;">{{ auth()->user()->name }}</div>
                <div style="font-size:.68rem; color:rgba(255,255,255,.45);">
                    @php
                        $roleLabel = match(auth()->user()->role) {
                            'admin'            => 'Administrator',
                            'teknisi_hardware' => 'Teknisi Hardware',
                            'teknisi_software' => 'Teknisi Software',
                            default            => 'Pelapor',
                        };
                    @endphp
                    {{ $roleLabel }}
                </div>
            </div>
        </div>

    </nav>
    @endauth

    {{-- ═══════════ MAIN ═══════════ --}}
    <div class="flex-grow-1" style="min-width:0;">

        @if(!request()->routeIs('login'))
        {{-- Topbar --}}
        <div class="topbar">
            <div class="d-flex align-items-center gap-2">
                @auth
                <button class="btn btn-sm btn-light border d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                @endauth
                <span class="topbar-title">@yield('page-title', 'IT Helpdesk')</span>
            </div>

            {{-- Dark mode toggle + auth actions (kanan topbar) --}}
            <div class="d-flex align-items-center gap-2">

                {{-- Tombol Dark Mode --}}
                <button class="btn btn-sm btn-light border dark-toggle" id="darkToggle" title="Ganti tampilan gelap/terang">
                    <i class="bi bi-moon" id="darkIcon"></i>
                </button>

                @auth
                @php
                    $unreadCount   = \App\Models\AppNotification::where('user_id', auth()->id())->whereNull('read_at')->count();
                    $notifications = \App\Models\AppNotification::where('user_id', auth()->id())->latest()->take(20)->get();
                @endphp

                {{-- Bell notifikasi — sekarang di sebelah profil --}}
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border position-relative" data-bs-toggle="dropdown" id="bellBtn">
                        <i class="bi bi-bell"></i>
                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifBadge" style="font-size:.6rem;">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                        @else
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notifBadge" style="font-size:.6rem;">0</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" style="border-radius:.75rem; width:340px; overflow:hidden;">
                        {{-- Header sticky --}}
                        <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center" style="position:sticky;top:0;z-index:2;background:#fff;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-bell-fill text-primary" style="font-size:.9rem;"></i>
                                <span class="fw-semibold" style="font-size:.85rem;">Notifikasi</span>
                                @if($unreadCount > 0)
                                <span class="badge rounded-pill bg-primary" style="font-size:.65rem;">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                @endif
                            </div>
                            <button class="btn btn-link btn-sm p-0 text-primary {{ $unreadCount > 0 ? '' : 'd-none' }}"
                                    id="markAllReadBtn" style="font-size:.75rem;">Tandai semua dibaca</button>
                        </div>
                        {{-- Scrollable list --}}
                        <div id="notifDropdownList" class="notif-scroll-area">
                        @forelse($notifications as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           class="d-block px-3 py-2 border-bottom text-decoration-none notif-item {{ $notif->read_at === null ? 'unread' : '' }}"
                           style="transition:background .15s;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle text-primary mt-1" style="font-size:.9rem; flex-shrink:0;"></i>
                                <div style="flex:1; min-width:0;">
                                    <div style="font-size:.8rem; line-height:1.4;">{{ $notif->message }}</div>
                                    <div style="font-size:.7rem; color:#94a3b8;">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>
                                @if($notif->read_at === null)
                                <div style="width:7px;height:7px;background:#3b82f6;border-radius:50%;margin-top:5px;flex-shrink:0;"></div>
                                @endif
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-4 text-muted" style="font-size:.82rem;">
                            <i class="bi bi-bell-slash d-block mb-1 fs-4"></i> Tidak ada notifikasi
                        </div>
                        @endforelse
                        </div>
                        {{-- Footer --}}
                        @if($notifications->count() > 0)
                        <div class="border-top text-center py-2" style="background:#f8fafc;">
                            <a href="{{ route('notifications.index') }}" class="text-primary" style="font-size:.78rem; font-weight:500;"
                               id="notifFooterLink">
                                <i class="bi bi-list-ul me-1"></i>Lihat semua notifikasi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- User dropdown --}}
                <div class="dropdown">
                    <div class="topbar-user-btn" data-bs-toggle="dropdown" role="button">
                        <div class="topbar-avatar"><i class="bi bi-person-fill"></i></div>
                        <div class="d-none d-sm-block">
                            <div class="topbar-name">{{ auth()->user()->name }}</div>
                            <div class="topbar-role">
                                @php
                                    $roleLabel = match(auth()->user()->role) {
                                        'admin'            => 'Administrator',
                                        'teknisi_hardware' => 'Teknisi Hardware',
                                        'teknisi_software' => 'Teknisi Software',
                                        default            => 'Pelapor',
                                    };
                                @endphp
                                {{ $roleLabel }}
                            </div>
                        </div>
                        <i class="bi bi-chevron-down text-muted" style="font-size:.7rem;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-1" style="border-radius:.75rem; min-width:200px;">
                        <li class="px-3 pt-2 pb-1">
                            <div class="fw-semibold" style="font-size:.85rem;">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ auth()->user()->email }}</div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="px-3 pb-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light text-danger w-100 mt-1">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth

                @guest
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                @endguest

            </div>
        </div>
        @endif

        {{-- Content --}}
        <main class="content-wrapper">
            @hasSection('breadcrumb')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endif

            @foreach(['success'=>'success','error'=>'danger','warning'=>'warning','info'=>'info'] as $key => $type)
            @if(session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show alert-flash" role="alert">
                <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'danger' ? 'x-circle' : 'info-circle') }}-fill me-2"></i>
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @endforeach

            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

{{-- Global modal konfirmasi aksi (hapus, dsb) --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:32px;height:32px;">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    </div>
                    <div>
                        <h6 class="modal-title mb-0" id="confirmTitle">Konfirmasi Aksi</h6>
                        <small class="text-muted" style="font-size:.78rem;">Tindakan ini tidak dapat dibatalkan.</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <p id="confirmMessage" class="mb-0" style="font-size:.9rem;">Yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer border-0 pt-1">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" id="confirmOkBtn">
                    <i class="bi bi-trash me-1"></i>Ya, hapus
                </button>
            </div>
        </div>
    </div>
    </div>
<script>
    // Auto-dismiss hanya untuk flash message (bukan alert statis)
    setTimeout(() => {
        document.querySelectorAll('.alert-flash').forEach(el => new bootstrap.Alert(el).close());
    }, 4500);

    // ── Global confirm modal untuk form dengan class .js-confirm-form ──
    (function () {
        const modalEl   = document.getElementById('confirmModal');
        if (!modalEl) return;
        const modal     = new bootstrap.Modal(modalEl);
        const titleEl   = document.getElementById('confirmTitle');
        const msgEl     = document.getElementById('confirmMessage');
        const okBtn     = document.getElementById('confirmOkBtn');
        let currentForm = null;

        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (!form.classList.contains('js-confirm-form')) return;

            // Kalau sudah pernah dikonfirmasi, biarkan submit
            if (form.dataset.confirmed === 'true') {
                form.dataset.confirmed = 'false';
                return;
            }

            e.preventDefault();
            currentForm = form;

            const title = form.dataset.confirmTitle || 'Konfirmasi Aksi';
            const msg   = form.dataset.confirmMessage || 'Yakin ingin melanjutkan tindakan ini?';
            if (titleEl) titleEl.textContent = title;
            if (msgEl)   msgEl.textContent   = msg;

            // Ubah label tombol sesuai konteks (hapus / lainnya)
            const btnText = form.dataset.confirmBtnText || 'Ya, lanjutkan';
            okBtn.innerHTML = `<i class="bi bi-check2-circle me-1"></i>${btnText}`;

            modal.show();
        }, true);

        if (okBtn) {
            okBtn.addEventListener('click', () => {
                if (!currentForm) return;
                currentForm.dataset.confirmed = 'true';
                modal.hide();
                currentForm.submit();
                currentForm = null;
            });
        }
    })();

    // ── Dark mode ──────────────────────────────────────────
    const htmlEl   = document.documentElement;
    const darkBtn  = document.getElementById('darkToggle');
    const darkIcon = document.getElementById('darkIcon');

    const applyDark = (isDark, animate) => {
        // Aktifkan transisi hanya jika animate = true (user klik, bukan page load)
        if (animate) document.body.classList.add('theme-ready');
        htmlEl.classList.toggle('dark', isDark);
        if (darkIcon) {
            darkIcon.className = isDark ? 'bi bi-sun' : 'bi bi-moon';
        }
    };

    // Restore saved preference TANPA animasi (cegah flash)
    applyDark(localStorage.getItem('theme') === 'dark', false);

    if (darkBtn) {
        darkBtn.addEventListener('click', () => {
            const nowDark = !htmlEl.classList.contains('dark');
            applyDark(nowDark, true); // dengan animasi
            localStorage.setItem('theme', nowDark ? 'dark' : 'light');
        });
    }

    // Mobile sidebar
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar   = document.getElementById('sidebar');
        const toggle    = document.getElementById('sidebarToggle');
        const backdrop  = document.getElementById('sidebarBackdrop');
        if (toggle && sidebar && backdrop) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            });
            backdrop.addEventListener('click', () => {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });
        }

        // Mark all notifications as read
        const markAllBtn = document.getElementById('markAllReadBtn');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', () => {
                fetch('{{ route("notifications.readAll") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                }).then(() => window.location.reload());
            });
        }
    });
</script>
@stack('scripts')

<script>
    // ── Notification Polling (badge & dropdown only, no page reload) ──
    @auth
    (function () {
        const POLL_MS    = 30000; // 30 detik
        const POLL_URL   = '{{ route("notifications.poll") }}';
        const CSRF       = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const READ_BASE  = '{{ url("/notifikasi") }}';

        function escHtml(s) {
            return String(s || '').replace(/[&<>"']/g, c =>
                ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])
            );
        }

        function updateBadge(count) {
            const badge = document.getElementById('notifBadge');
            const markBtn = document.getElementById('markAllReadBtn');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            }
            if (markBtn) markBtn.classList.toggle('d-none', count === 0);
        }

        function updateDropdown(notifications) {
            const list = document.getElementById('notifDropdownList');
            if (!list) return;
            if (notifications.length === 0) {
                list.innerHTML = `<div class="text-center py-4 text-muted" style="font-size:.82rem;">
                    <i class="bi bi-bell-slash d-block mb-1 fs-4"></i> Tidak ada notifikasi
                </div>`;
                return;
            }
            list.innerHTML = notifications.map(n => `
                <a href="${READ_BASE}/${n.id}/baca"
                   class="d-block px-3 py-2 border-bottom text-decoration-none notif-item ${n.read_at === null ? 'unread' : ''}"
                   style="transition:background .15s;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-info-circle text-primary mt-1" style="font-size:.9rem;flex-shrink:0;"></i>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.8rem;line-height:1.4;">${escHtml(n.message)}</div>
                            <div style="font-size:.7rem;color:#94a3b8;">${escHtml(n.time_ago)}</div>
                        </div>
                        ${n.read_at === null ? '<div style="width:7px;height:7px;background:#3b82f6;border-radius:50%;margin-top:5px;flex-shrink:0;"></div>' : ''}
                    </div>
                </a>`).join('');
        }

        function doPoll() {
            fetch(POLL_URL, {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data) return;
                updateBadge(data.unread_count);
                updateDropdown(data.notifications);
            })
            .catch(() => {}); // silent fail jika offline
        }

        setInterval(doPoll, POLL_MS);
    })();
    @endauth


</script>
</body>
</html>
