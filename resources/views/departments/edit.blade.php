@extends('layouts.app')

@section('page-title', 'Edit Departemen')

@section('breadcrumb')
    @php $isAdmin = auth()->user()->role === 'admin'; @endphp
    <li class="breadcrumb-item">
        <a href="{{ $isAdmin ? route('admin.departments.index') : route('departments.index') }}">Departemen</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<style>
.form-card { border: none; border-radius: 1.25rem; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
.dark-mode .form-card { background: #1e2a3a; box-shadow: 0 4px 24px rgba(0,0,0,.3); }
.form-card-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #0ea5e9 60%, #38bdf8 100%);
    padding: 2rem; position: relative; overflow: hidden;
}
.form-card-header::before {
    content: ''; position: absolute; width: 180px; height: 180px; border-radius: 50%;
    background: rgba(255,255,255,.06); top: -60px; right: -50px;
}
.form-card-header::after {
    content: ''; position: absolute; width: 100px; height: 100px; border-radius: 50%;
    background: rgba(255,255,255,.04); bottom: -30px; left: 40px;
}
.header-icon-box {
    width: 56px; height: 56px; border-radius: 14px;
    background: rgba(255,255,255,.2); border: 2px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: #fff; flex-shrink: 0;
}
.header-title { color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0; }
.header-subtitle { color: rgba(255,255,255,.75); font-size: .8rem; margin-top: .25rem; }
.dept-name-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    color: #fff; border-radius: 20px; padding: .25rem .75rem;
    font-size: .72rem; font-weight: 600; margin-top: .5rem;
}
.form-card-body { padding: 2rem; }
@media (max-width: 576px) {
    .form-card-body { padding: 1.25rem; }
    .form-card-header { padding: 1.5rem 1.25rem; }
}
.input-icon-wrap { position: relative; }
.input-icon-wrap .field-icon {
    position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: .9rem; z-index: 4; pointer-events: none;
}
.input-icon-wrap .form-control,
.input-icon-wrap .form-select {
    padding-left: 2.4rem; border-radius: .75rem;
    border-color: #e2e8f0; transition: border-color .2s, box-shadow .2s;
}
.dark-mode .input-icon-wrap .form-control,
.dark-mode .input-icon-wrap .form-select {
    background: #243447; border-color: #2d3f55; color: #e2e8f0;
}
.input-icon-wrap .form-control:focus {
    border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14,165,233,.15);
}
.form-label { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
.dark-mode .form-label { color: #cbd5e1; }
.field-hint { font-size: .72rem; color: #94a3b8; margin-top: .3rem; }
.btn-save {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    border: none; border-radius: .75rem; padding: .65rem 1.75rem;
    font-weight: 600; color: #fff; transition: all .25s;
    box-shadow: 0 4px 14px rgba(14,165,233,.35);
}
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(14,165,233,.5); color: #fff; }
.btn-cancel { border-radius: .75rem; padding: .65rem 1.35rem; font-weight: 600; }
</style>

<div class="row justify-content-center">
    <div class="col-lg-6 col-xl-5">
        <div class="form-card card">

            {{-- Header --}}
            <div class="form-card-header">
                <div class="d-flex align-items-center gap-3" style="position:relative;z-index:1;">
                    <div class="header-icon-box">
                        <i class="bi bi-building-gear"></i>
                    </div>
                    <div>
                        <p class="header-title">Edit Departemen</p>
                        <p class="header-subtitle">Perbarui data departemen atau unit kerja</p>
                        <span class="dept-name-chip">
                            <i class="bi bi-building"></i> {{ $department->name }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="form-card-body">
                <form method="POST" action="{{ $isAdmin ? route('admin.departments.update', $department->id) : route('departments.update', $department->id) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-building field-icon"></i>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $department->name) }}" required
                                placeholder="Nama departemen">
                        </div>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi <span class="text-muted fw-normal" style="font-size:.72rem;">(opsional)</span></label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-card-text field-icon"></i>
                            <input type="text" name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                value="{{ old('description', $department->description) }}"
                                placeholder="Deskripsi singkat departemen">
                        </div>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <p class="field-hint">Contoh: Instalasi Gawat Darurat, Instalasi Farmasi</p>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ $isAdmin ? route('admin.departments.index') : route('departments.index') }}"
                            class="btn btn-outline-secondary btn-cancel">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-check-lg me-1"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
