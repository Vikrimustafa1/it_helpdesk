@extends('layouts.app')

@section('page-title', 'Kategori Tiket')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori Tiket</li>
@endsection

@section('content')
@php $isAdmin = auth()->user()->role === 'admin'; @endphp

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-semibold">Manajemen Kategori Tiket</h6>
            <div class="text-muted" style="font-size:.75rem;">Kelola kategori/jenis masalah IT yang tersedia</div>
        </div>
        <a href="{{ $isAdmin ? route('admin.ticket-categories.create') : route('ticket-categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>
    <div class="card-body p-0">
        @if($categories->isEmpty())
            <div class="empty-state">
                <i class="bi bi-tags"></i>
                <p class="mb-2">Belum ada kategori tiket.</p>
                <a href="{{ $isAdmin ? route('admin.ticket-categories.create') : route('ticket-categories.create') }}" class="btn btn-sm btn-primary">Tambah Sekarang</a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Kategori</th>
                        <th style="width:100px;">Warna</th>
                        <th style="width:130px;">Jumlah Tiket</th>
                        <th style="width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;background:{{ $cat->color }}20;border-radius:.5rem;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi {{ $cat->icon ?? 'bi-tag' }}" style="color:{{ $cat->color }};font-size:.9rem;"></i>
                                </div>
                                <span class="fw-semibold">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:22px;height:22px;background:{{ $cat->color }};border-radius:.35rem;flex-shrink:0;box-shadow:0 1px 4px {{ $cat->color }}60;"></div>
                                <code style="font-size:.78rem;">{{ $cat->color }}</code>
                            </div>
                        </td>
                        <td>
                            <span class="badge"
                                  style="background:{{ $cat->color }}20;color:{{ $cat->color }};border:1px solid {{ $cat->color }}40;">
                                {{ $cat->tickets_count }} tiket
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ $isAdmin ? route('admin.ticket-categories.edit', $cat->id) : route('ticket-categories.edit', $cat->id) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ $isAdmin ? route('admin.ticket-categories.destroy', $cat->id) : route('ticket-categories.destroy', $cat->id) }}"
                                      method="POST"
                                      class="js-confirm-form"
                                      data-confirm-title="Hapus Kategori Tiket"
                                      data-confirm-message="Yakin ingin menghapus kategori {{ $cat->name }}? Tiket yang sudah ada tetap tersimpan dengan kategori lama.">
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
