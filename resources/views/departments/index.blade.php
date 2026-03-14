@extends('layouts.app')

@section('page-title', 'Departemen')

@section('breadcrumb')
    <li class="breadcrumb-item active">Departemen</li>
@endsection

@section('content')
@php $isAdmin = auth()->user()->role === 'admin'; @endphp

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-semibold">Manajemen Departemen</h6>
            <div class="text-muted" style="font-size:.75rem;">Kelola daftar unit/departemen rumah sakit</div>
        </div>
        <a href="{{ $isAdmin ? route('admin.departments.create') : route('departments.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Departemen
        </a>
    </div>
    <div class="card-body p-0">
        @if($departments->isEmpty())
            <div class="empty-state">
                <i class="bi bi-building"></i>
                <p class="mb-2">Belum ada departemen.</p>
                <a href="{{ $isAdmin ? route('admin.departments.create') : route('departments.create') }}" class="btn btn-sm btn-primary">Tambah Sekarang</a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Nama Departemen</th>
                        <th>Deskripsi</th>
                        <th style="width:120px;">Jumlah User</th>
                        <th style="width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $dept)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:.5rem;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-building text-primary" style="font-size:.85rem;"></i>
                                </div>
                                <span class="fw-semibold">{{ $dept->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $dept->description ?? '-' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $dept->users_count }} user</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ $isAdmin ? route('admin.departments.edit', $dept->id) : route('departments.edit', $dept->id) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ $isAdmin ? route('admin.departments.destroy', $dept->id) : route('departments.destroy', $dept->id) }}"
                                      method="POST"
                                      class="js-confirm-form"
                                      data-confirm-title="Hapus Departemen"
                                      data-confirm-message="Yakin ingin menghapus departemen {{ $dept->name }}? Semua user terkait akan kehilangan relasi departemen.">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-0 px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
