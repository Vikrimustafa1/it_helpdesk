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
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Edit Departemen — {{ $department->name }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $isAdmin ? route('admin.departments.update', $department->id) : route('departments.update', $department->id) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $department->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description', $department->description) }}">
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ $isAdmin ? route('admin.departments.index') : route('departments.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
