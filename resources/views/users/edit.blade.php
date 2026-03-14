@extends('layouts.app')

@section('page-title', 'Edit User')

@section('breadcrumb')
    @php $isAdmin = auth()->user()->role === 'admin'; @endphp
    <li class="breadcrumb-item">
        <a href="{{ $isAdmin ? route('admin.users.index') : route('users.index') }}">Manajemen User</a>
    </li>
    <li class="breadcrumb-item active">Edit {{ $user->name }}</li>
@endsection

@section('content')
<style>
/* ── Modern Edit Form ── */
.edit-card {
    border: none;
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
}
.dark-mode .edit-card {
    background: #1e2a3a;
    box-shadow: 0 4px 24px rgba(0,0,0,.3);
}

/* Header */
.edit-card-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 60%, #6366f1 100%);
    padding: 2rem 2rem 1.5rem;
    position: relative;
    overflow: hidden;
}
.edit-card-header::before {
    content: '';
    position: absolute;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
    top: -60px; right: -60px;
}
.edit-card-header::after {
    content: '';
    position: absolute;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
    bottom: -30px; left: 40px;
}

.user-avatar {
    width: 64px; height: 64px;
    border-radius: 16px;
    background: rgba(255,255,255,.2);
    border: 2px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; color: #fff;
    flex-shrink: 0;
}
.header-title { color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0; }
.header-subtitle { color: rgba(255,255,255,.7); font-size: .8rem; margin-top: .2rem; }
.header-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff; border-radius: 20px;
    padding: .25rem .75rem; font-size: .72rem; font-weight: 600;
    margin-top: .5rem;
}

/* Body */
.edit-card-body { padding: 2rem; }
@media (max-width: 576px) {
    .edit-card-body { padding: 1.25rem; }
    .edit-card-header { padding: 1.5rem 1.25rem 1.25rem; }
}

/* Section label */
.form-section-title {
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em;
    color: #94a3b8; margin-bottom: 1rem; margin-top: .25rem;
    display: flex; align-items: center; gap: .5rem;
}
.form-section-title::after {
    content: ''; flex: 1; height: 1px; background: #e2e8f0;
}
.dark-mode .form-section-title::after { background: #2d3f55; }

/* Input group icon */
.input-icon-wrap { position: relative; }
.input-icon-wrap .field-icon {
    position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: .9rem; z-index: 4; pointer-events: none;
}
.input-icon-wrap .form-control,
.input-icon-wrap .form-select {
    padding-left: 2.4rem;
    border-radius: .75rem;
    border-color: #e2e8f0;
    transition: border-color .2s, box-shadow .2s;
}
.dark-mode .input-icon-wrap .form-control,
.dark-mode .input-icon-wrap .form-select {
    background: #243447; border-color: #2d3f55; color: #e2e8f0;
}
.input-icon-wrap .form-control:focus,
.input-icon-wrap .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}

/* Password toggle */
.input-icon-wrap .pw-toggle {
    position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
    color: #94a3b8; cursor: pointer; z-index: 4;
    background: none; border: none; padding: 0; line-height: 1;
    transition: color .2s;
}
.input-icon-wrap .pw-toggle:hover { color: #3b82f6; }

/* Form label */
.form-label {
    font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .4rem;
}
.dark-mode .form-label { color: #cbd5e1; }

/* Role locked badge */
.role-locked-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem .9rem; border-radius: .75rem;
    font-size: .82rem; font-weight: 600;
    border: 1px solid;
}

/* Buttons */
.btn-save {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border: none; border-radius: .75rem; padding: .65rem 1.75rem;
    font-weight: 600; color: #fff;
    transition: all .25s; box-shadow: 0 4px 14px rgba(99,102,241,.35);
}
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,.5); color: #fff; }
.btn-cancel {
    border-radius: .75rem; padding: .65rem 1.35rem; font-weight: 600;
    transition: all .2s;
}

/* Hint text */
.field-hint { font-size: .72rem; color: #94a3b8; margin-top: .3rem; }
</style>

<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="edit-card card">

            {{-- ── Header ── --}}
            <div class="edit-card-header">
                <div class="d-flex align-items-center gap-3" style="position:relative;z-index:1;">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <p class="header-title">{{ $user->name }}</p>
                        <p class="header-subtitle">{{ $user->email }}</p>
                        <span class="header-badge">
                            @if($user->role === 'admin')
                                <i class="bi bi-shield-check"></i> Admin
                            @elseif($user->role === 'teknisi_hardware')
                                <i class="bi bi-pc-display"></i> Teknisi Hardware
                            @elseif($user->role === 'teknisi_software')
                                <i class="bi bi-code-square"></i> Teknisi Software
                            @else
                                <i class="bi bi-person"></i> Pelapor
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── Body ── --}}
            <div class="edit-card-body">
                <form method="POST" action="{{ $isAdmin ? route('admin.users.update', $user->id) : route('users.update', $user->id) }}">
                    @csrf @method('PUT')

                    {{-- Info Dasar --}}
                    <div class="form-section-title">
                        <i class="bi bi-person-vcard"></i> Informasi Dasar
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-person field-icon"></i>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required
                                    placeholder="Nama lengkap">
                            </div>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">ID Karyawan <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-card-text field-icon"></i>
                                <input type="text" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required
                                    placeholder="ID Karyawan">
                            </div>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">
                                Role
                                @if(auth()->user()->role !== 'admin')
                                    <span class="text-muted" style="font-size:.72rem;font-weight:400;">
                                        <i class="bi bi-lock ms-1"></i> hanya admin
                                    </span>
                                @endif
                            </label>
                            @if(auth()->user()->role === 'admin')
                                <div class="input-icon-wrap">
                                    <i class="bi bi-shield-half field-icon"></i>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="user"             {{ old('role', $user->role) === 'user'             ? 'selected' : '' }}>Pelapor</option>
                                        <option value="teknisi_hardware" {{ old('role', $user->role) === 'teknisi_hardware' ? 'selected' : '' }}>Teknisi Hardware</option>
                                        <option value="teknisi_software" {{ old('role', $user->role) === 'teknisi_software' ? 'selected' : '' }}>Teknisi Software</option>
                                        <option value="admin"            {{ old('role', $user->role) === 'admin'            ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @else
                                <div>
                                    @if($user->role === 'teknisi_hardware')
                                        <span class="role-locked-badge" style="background:rgba(59,130,246,.1);border-color:rgba(59,130,246,.3);color:#3b82f6;">
                                            <i class="bi bi-pc-display"></i> Teknisi Hardware
                                        </span>
                                    @elseif($user->role === 'teknisi_software')
                                        <span class="role-locked-badge" style="background:rgba(139,92,246,.1);border-color:rgba(139,92,246,.3);color:#8b5cf6;">
                                            <i class="bi bi-code-square"></i> Teknisi Software
                                        </span>
                                    @elseif($user->role === 'admin')
                                        <span class="role-locked-badge" style="background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#ef4444;">
                                            <i class="bi bi-shield-check"></i> Admin
                                        </span>
                                    @else
                                        <span class="role-locked-badge" style="background:rgba(100,116,139,.1);border-color:rgba(100,116,139,.3);color:#64748b;">
                                            <i class="bi bi-person"></i> Pelapor
                                        </span>
                                    @endif
                                    <p class="field-hint mt-2"><i class="bi bi-lock me-1"></i>Role hanya bisa diubah oleh Admin.</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Departemen / Unit</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-building field-icon"></i>
                                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}{{ $dept->description ? ' — ' . $dept->description : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">No. HP</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-telephone field-icon"></i>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="Contoh: 08123456789">
                            </div>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Ganti Password --}}
                    <div class="form-section-title">
                        <i class="bi bi-key"></i> Ganti Password
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Password Baru <span class="text-muted fw-normal" style="font-size:.72rem;">(opsional)</span></label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-lock field-icon"></i>
                                <input type="password" name="password" id="newPassword"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Kosongkan jika tidak diubah"
                                    style="padding-right:2.5rem;">
                                <button type="button" class="pw-toggle" onclick="togglePw('newPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <p class="field-hint">Minimal 6 karakter</p>
                            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-lock-fill field-icon"></i>
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                    class="form-control"
                                    placeholder="Ulangi password baru"
                                    style="padding-right:2.5rem;">
                                <button type="button" class="pw-toggle" onclick="togglePw('confirmPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ $isAdmin ? route('admin.users.index') : route('users.index') }}"
                            class="btn btn-outline-secondary btn-cancel">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endsection
