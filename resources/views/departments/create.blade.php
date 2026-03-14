@extends('layouts.app')

@section('page-title', 'Tambah Departemen')

@section('breadcrumb')
    @php $isAdmin = auth()->user()->role === 'admin'; @endphp
    <li class="breadcrumb-item">
        <a href="{{ $isAdmin ? route('admin.departments.index') : route('departments.index') }}">Departemen</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Form Tambah Departemen</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $isAdmin ? route('admin.departments.store') : route('departments.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Contoh: IGD, Farmasi, ICU" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description') }}" placeholder="Deskripsi singkat departemen">
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ $isAdmin ? route('admin.departments.index') : route('departments.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
