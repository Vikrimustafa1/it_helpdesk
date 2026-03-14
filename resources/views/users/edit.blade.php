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
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Edit Data User</h6>
                <div class="text-muted" style="font-size:.75rem;">Kosongkan password jika tidak ingin mengubahnya</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $isAdmin ? route('admin.users.update', $user->id) : route('users.update', $user->id) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ID Karyawan <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role
                                @if(auth()->user()->role !== 'admin')
                                    <span class="text-muted" style="font-size:.72rem;font-weight:400;">
                                        <i class="bi bi-lock ms-1"></i>hanya admin yang bisa ubah
                                    </span>
                                @endif
                            </label>
                            @if(auth()->user()->role === 'admin')
                                {{-- Admin: dropdown role bisa diubah --}}
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="user"              {{ old('role', $user->role) === 'user'              ? 'selected' : '' }}>Pelapor</option>
                                    <option value="teknisi_hardware"  {{ old('role', $user->role) === 'teknisi_hardware'  ? 'selected' : '' }}>Teknisi Hardware</option>
                                    <option value="teknisi_software"  {{ old('role', $user->role) === 'teknisi_software'  ? 'selected' : '' }}>Teknisi Software</option>
                                    <option value="admin"             {{ old('role', $user->role) === 'admin'             ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @else
                                {{-- IT Support: badge read-only --}}
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    @if($user->role === 'teknisi_hardware')
                                        <span class="badge px-3 py-2" style="background:#3b82f6;font-size:.82rem;">
                                            <i class="bi bi-pc-display me-1"></i>Teknisi Hardware
                                        </span>
                                    @elseif($user->role === 'teknisi_software')
                                        <span class="badge px-3 py-2" style="background:#8b5cf6;font-size:.82rem;">
                                            <i class="bi bi-code-square me-1"></i>Teknisi Software
                                        </span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge bg-danger px-3 py-2" style="font-size:.82rem;">
                                            <i class="bi bi-shield-check me-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2" style="font-size:.82rem;">
                                            <i class="bi bi-person me-1"></i>Pelapor
                                        </span>
                                    @endif
                                    <i class="bi bi-lock text-muted"></i>
                                </div>
                                <small class="text-muted" style="font-size:.72rem;">
                                    Role hanya bisa diubah oleh Admin.
                                </small>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Departemen / Unit</label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}{{ $dept->description ? ' — ' . $dept->description : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12"><hr class="my-1"></div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru <span class="text-muted">(opsional)</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Kosongkan jika tidak diubah">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ $isAdmin ? route('admin.users.index') : route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
