@extends('layouts.app')

@section('page-title', 'Edit Kategori Tiket')

@section('breadcrumb')
    @php $isAdmin = auth()->user()->role === 'admin'; @endphp
    <li class="breadcrumb-item">
        <a href="{{ $isAdmin ? route('admin.ticket-categories.index') : route('ticket-categories.index') }}">Kategori Tiket</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Edit Kategori — {{ $category->name }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $isAdmin ? route('admin.ticket-categories.update', $category->id) : route('ticket-categories.update', $category->id) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Warna <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="color" id="colorPicker"
                                   class="form-control form-control-color"
                                   value="{{ old('color', $category->color) }}"
                                   style="width:60px;height:38px;cursor:pointer;" required>
                            <input type="text" id="colorHex"
                                   class="form-control" style="width:120px;font-family:monospace;"
                                   value="{{ old('color', $category->color) }}" readonly>
                        </div>
                        @error('color')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Ikon (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i id="iconPreview" class="bi {{ $category->icon ?? 'bi-tag' }}"></i></span>
                            <input type="text" name="icon" id="iconInput"
                                   class="form-control @error('icon') is-invalid @enderror"
                                   value="{{ old('icon', $category->icon) }}" placeholder="Contoh: bi-pc-display">
                        </div>
                        <small class="text-muted">Lihat semua ikon di <a href="https://icons.getbootstrap.com" target="_blank">icons.getbootstrap.com</a></small>
                        @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ $isAdmin ? route('admin.ticket-categories.index') : route('ticket-categories.index') }}" class="btn btn-outline-secondary">Batal</a>
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

@push('scripts')
<script>
const picker = document.getElementById('colorPicker');
const hexInput = document.getElementById('colorHex');
picker.addEventListener('input', () => hexInput.value = picker.value);

const iconInput = document.getElementById('iconInput');
const iconPreview = document.getElementById('iconPreview');
iconInput.addEventListener('input', () => {
    iconPreview.className = 'bi ' + iconInput.value;
});
</script>
@endpush
