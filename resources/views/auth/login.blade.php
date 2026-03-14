<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — IT Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
* { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }

body {
    background: #060d1a;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

/* ── Animated orbs ── */
.orb {
    position: fixed; border-radius: 50%;
    filter: blur(80px); pointer-events: none;
    animation: floatOrb 14s ease-in-out infinite alternate;
}
.orb-1 { width: 550px; height: 550px; top: -180px; left: -150px;
    background: radial-gradient(circle, rgba(59,130,246,.45), transparent 65%); }
.orb-2 { width: 450px; height: 450px; bottom: -150px; right: -120px;
    background: radial-gradient(circle, rgba(139,92,246,.4), transparent 65%); animation-delay: -5s; }
.orb-3 { width: 300px; height: 300px; top: 50%; left: 50%; transform: translate(-50%,-50%);
    background: radial-gradient(circle, rgba(16,185,129,.15), transparent 70%); animation-delay: -9s; }
@keyframes floatOrb {
    0%   { transform: translate(0,0) scale(1); }
    100% { transform: translate(30px,50px) scale(1.1); }
}
.orb-3 { animation: floatOrb3 14s ease-in-out infinite alternate; }
@keyframes floatOrb3 {
    0%   { transform: translate(-50%,-50%) scale(1); }
    100% { transform: translate(calc(-50% + 20px), calc(-50% + 30px)) scale(1.1); }
}

/* Grid overlay */
.grid {
    position: fixed; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.022) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.022) 1px, transparent 1px);
    background-size: 55px 55px;
}

/* ── Wrapper ── */
.login-wrap {
    width: 100%; max-width: 960px;
    padding: 1.5rem;
    display: flex; align-items: stretch; gap: 0;
    position: relative; z-index: 1;
    animation: fadeUp .7s ease both;
}
@keyframes fadeUp { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:translateY(0); } }

/* ── Left panel (info) ── */
.left-panel {
    flex: 1;
    background: linear-gradient(145deg, rgba(30,58,138,.5) 0%, rgba(67,56,202,.35) 100%);
    border: 1px solid rgba(99,102,241,.2);
    border-right: none;
    border-radius: 1.5rem 0 0 1.5rem;
    padding: 3rem 2.5rem;
    display: flex; flex-direction: column; justify-content: space-between;
    backdrop-filter: blur(10px);
    position: relative; overflow: hidden;
}
.left-panel::before {
    content: ''; position: absolute;
    width: 300px; height: 300px; top: -80px; right: -80px;
    background: radial-gradient(circle, rgba(99,102,241,.3), transparent 65%);
    pointer-events: none;
}
.left-panel::after {
    content: ''; position: absolute;
    width: 200px; height: 200px; bottom: -60px; left: -60px;
    background: radial-gradient(circle, rgba(16,185,129,.2), transparent 65%);
    pointer-events: none;
}

.brand-mark {
    display: flex; align-items: center; gap: .75rem;
    position: relative; z-index: 1;
}
.brand-icon {
    width: 42px; height: 42px; border-radius: 11px;
    background: linear-gradient(135deg,#3b82f6,#6366f1);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; box-shadow: 0 4px 16px rgba(99,102,241,.5);
}
.brand-text { font-size: 1.05rem; font-weight: 800; color: #fff; letter-spacing: -.3px; }

.left-hero { position: relative; z-index: 1; }
.left-hero h2 {
    font-size: 1.7rem; font-weight: 800; line-height: 1.2;
    letter-spacing: -.02em; color: #fff; margin-bottom: .85rem;
}
.gradient-text {
    background: linear-gradient(135deg,#60a5fa,#a78bfa,#34d399);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.left-hero p { font-size: .85rem; color: rgba(255,255,255,.55); line-height: 1.7; }

/* Mini feature list */
.feat-list { list-style: none; padding: 0; margin: 1.5rem 0 0; }
.feat-list li {
    display: flex; align-items: center; gap: .65rem;
    font-size: .8rem; color: rgba(255,255,255,.65);
    padding: .4rem 0;
}
.feat-list li .dot {
    width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
}
.dot-blue   { background: rgba(59,130,246,.2);  color: #60a5fa; }
.dot-purple { background: rgba(139,92,246,.2);  color: #c4b5fd; }
.dot-green  { background: rgba(16,185,129,.2);  color: #34d399; }
.dot-amber  { background: rgba(245,158,11,.2);  color: #fcd34d; }

/* Category chips */
.cat-chips { display: flex; gap: .65rem; flex-wrap: wrap; position: relative; z-index: 1; }
.cat-chip {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .35rem .85rem; border-radius: 20px; font-size: .75rem; font-weight: 600;
    border: 1px solid;
}
.chip-hw { background: rgba(59,130,246,.12); border-color: rgba(59,130,246,.3); color: #93c5fd; }
.chip-sw { background: rgba(139,92,246,.12); border-color: rgba(139,92,246,.3); color: #c4b5fd; }

/* ── Right panel (form) ── */
.right-panel {
    width: 380px; flex-shrink: 0;
    background: rgba(15,22,38,.85);
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 0 1.5rem 1.5rem 0;
    padding: 3rem 2.25rem;
    backdrop-filter: blur(20px);
    display: flex; flex-direction: column; justify-content: center;
}

.form-title { font-size: 1.25rem; font-weight: 800; color: #f1f5f9; letter-spacing: -.02em; margin-bottom: .35rem; }
.form-sub   { font-size: .82rem; color: #94a3b8; margin-bottom: 2rem; }

.field-label {
    font-size: .75rem; font-weight: 600; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .07em;
    display: block; margin-bottom: .5rem;
}
.field-wrap { position: relative; margin-bottom: 1.1rem; }
.field-icon {
    position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
    color: #475569; font-size: 1rem; pointer-events: none;
    transition: color .2s;
}
.field-wrap:focus-within .field-icon { color: #3b82f6; }

.form-input {
    width: 100%; padding: .7rem .9rem .7rem 2.5rem;
    background: rgba(255,255,255,.04);
    border: 1.5px solid rgba(255,255,255,.09);
    border-radius: .75rem; color: #f1f5f9;
    font-size: .875rem; outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    -webkit-appearance: none;
}
.form-input::placeholder { color: #475569; }
.form-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.18);
    background: rgba(59,130,246,.05);
}
.form-input.is-error { border-color: #ef4444 !important; }

.toggle-pass {
    position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: #475569; cursor: pointer;
    padding: .25rem; font-size: .95rem; transition: color .2s;
}
.toggle-pass:hover { color: #94a3b8; }

/* Error alert */
.error-alert {
    background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25);
    border-radius: .75rem; padding: .75rem 1rem;
    display: flex; align-items: center; gap: .6rem;
    font-size: .8rem; color: #fca5a5; margin-bottom: 1.25rem;
}

/* Remember me */
.remember-row { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.5rem; }
.remember-check {
    width: 16px; height: 16px; border-radius: 4px; cursor: pointer;
    accent-color: #3b82f6;
}
.remember-label { font-size: .82rem; color: #94a3b8; cursor: pointer; }

/* Submit button */
.btn-submit {
    width: 100%; padding: .8rem;
    background: linear-gradient(135deg,#3b82f6,#6366f1);
    border: none; border-radius: .75rem; color: #fff;
    font-weight: 700; font-size: .9rem; letter-spacing: .02em;
    cursor: pointer; transition: all .25s;
    box-shadow: 0 6px 20px rgba(99,102,241,.4);
    display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(99,102,241,.55); }
.btn-submit:active { transform: translateY(0); }

.form-footer {
    margin-top: 1.75rem; padding-top: 1.25rem;
    border-top: 1px solid rgba(255,255,255,.06);
    font-size: .78rem; color: #64748b; text-align: center;
    line-height: 1.65;
}

/* ── Responsive ── */
@media (max-width: 767px) {
    .left-panel { display: none; }
    .right-panel { width: 100%; border-radius: 1.25rem; }
    .login-wrap { max-width: 420px; }
}
</style>
</head>
<body>

<!-- Animated BG -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>
<div class="grid"></div>

<div class="login-wrap">

    <!-- ── LEFT PANEL ── -->
    <div class="left-panel d-none d-md-flex flex-column">
        <!-- Brand -->
        <div class="brand-mark">
            <div class="brand-icon">
                <i class="bi bi-headset text-white"></i>
            </div>
            <span class="brand-text">IT Helpdesk</span>
        </div>

        <!-- Hero text -->
        <div class="left-hero my-auto">
            <h2>Portal Manajemen<br><span class="gradient-text">Gangguan IT</span></h2>
            <p>Sistem pelaporan dan penanganan tiket IT terintegrasi untuk Teknisi Hardware dan Software.</p>

            <ul class="feat-list">
                <li>
                    <div class="dot dot-blue"><i class="bi bi-ticket-perforated-fill"></i></div>
                    Buat & pantau tiket gangguan secara real-time
                </li>
                <li>
                    <div class="dot dot-purple"><i class="bi bi-people-fill"></i></div>
                    Teknisi spesialis Hardware &amp; Software
                </li>
                <li>
                    <div class="dot dot-green"><i class="bi bi-clock-history"></i></div>
                    SLA terukur dengan deadline otomatis
                </li>
                <li>
                    <div class="dot dot-amber"><i class="bi bi-star-fill"></i></div>
                    Feedback &amp; rating kualitas penanganan
                </li>
            </ul>
        </div>

        <!-- Bottom chips -->
        <div class="cat-chips">
            <span class="cat-chip chip-hw">
                <i class="bi bi-pc-display"></i> Teknisi Hardware
            </span>
            <span class="cat-chip chip-sw">
                <i class="bi bi-code-square"></i> Teknisi Software
            </span>
        </div>
    </div>

    <!-- ── RIGHT PANEL (form) ── -->
    <div class="right-panel">
        <div>
            <div class="form-title">Selamat Datang</div>
            <div class="form-sub">Masuk untuk mengakses portal IT Helpdesk</div>
        </div>

        @if($errors->any())
        <div class="error-alert">
            <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <!-- ID Karyawan -->
            <div>
                <label for="id_karyawan" class="field-label">ID Karyawan</label>
                <div class="field-wrap">
                    <i class="bi bi-person-fill field-icon"></i>
                    <input type="text"
                           name="id_karyawan"
                           id="id_karyawan"
                           class="form-input @error('id_karyawan') is-error @enderror"
                           value="{{ old('id_karyawan') }}"
                           placeholder="Masukkan ID karyawan"
                           required autofocus autocomplete="username">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="field-label">Password</label>
                <div class="field-wrap">
                    <i class="bi bi-lock-fill field-icon"></i>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-input @error('password') is-error @enderror"
                           placeholder="Masukkan password"
                           style="padding-right:2.5rem;"
                           required autocomplete="current-password">
                    <button type="button" class="toggle-pass" id="togglePass" tabindex="-1">
                        <i class="bi bi-eye" id="togglePassIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember -->
            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember" class="remember-check">
                <label for="remember" class="remember-label">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk ke Sistem
            </button>
        </form>

        <div class="form-footer">
            Tidak punya akun?<br>
            Hubungi administrator IT untuk pendaftaran.<br>
            <a href="{{ url('/') }}" style="color:#3b82f6;text-decoration:none;font-weight:500;margin-top:.4rem;display:inline-block;">
                <i class="bi bi-arrow-left me-1" style="font-size:.7rem;"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
const toggleBtn  = document.getElementById('togglePass');
const passInput  = document.getElementById('password');
const passIcon   = document.getElementById('togglePassIcon');
if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        const isPass = passInput.type === 'password';
        passInput.type     = isPass ? 'text'         : 'password';
        passIcon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
}
</script>
</body>
</html>
