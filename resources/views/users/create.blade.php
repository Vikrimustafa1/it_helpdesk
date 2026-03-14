@extends('layouts.app')

@section('page-title', 'Tambah User')

@section('breadcrumb')
    @php $isAdmin = auth()->user()->role === 'admin'; @endphp
    <li class="breadcrumb-item">
        <a href="{{ $isAdmin ? route('admin.users.index') : route('users.index') }}">Manajemen User</a>
    </li>
    <li class="breadcrumb-item active">Tambah User</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Form Tambah User Baru</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $isAdmin ? route('admin.users.store') : route('users.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ID Karyawan <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="Digunakan untuk login" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Pilih role</option>
                                <option value="user"             {{ old('role') === 'user'             ? 'selected' : '' }}>Pelapor</option>
                                <option value="teknisi_hardware" {{ old('role') === 'teknisi_hardware' ? 'selected' : '' }}>Teknisi Hardware</option>
                                <option value="teknisi_software" {{ old('role') === 'teknisi_software' ? 'selected' : '' }}>Teknisi Software</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Departemen / Unit</label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}{{ $dept->description ? ' — ' . $dept->description : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 6 karakter" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ $isAdmin ? route('admin.users.index') : route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-check me-1"></i> Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
