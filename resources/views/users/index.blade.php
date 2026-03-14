@extends('layouts.app')

@section('page-title', 'Manajemen User')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen User</li>
@endsection

@section('content')
@php $isAdmin = auth()->user()->role === 'admin'; @endphp
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-semibold">Daftar User</h6>
            <div class="text-muted" style="font-size:.75rem;">Kelola akun pelapor dan Teknisi</div>
        </div>
        <a href="{{ $isAdmin ? route('admin.users.create') : route('users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Tambah User
        </a>
    </div>

    {{-- Search --}}
    <div class="card-body border-bottom py-2">
        <form method="GET" action="{{ $isAdmin ? route('admin.users.index') : route('users.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Cari nama atau ID karyawan..." style="max-width:300px;">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            @if($search)
                <a href="{{ $isAdmin ? route('admin.users.index') : route('users.index') }}" class="btn btn-sm btn-light">Reset</a>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        @if($users->isEmpty())
            <div class="empty-state py-5">
                <i class="bi bi-people"></i>
                <p class="fw-semibold mb-1">Tidak ada user ditemukan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>ID Karyawan</th>
                            <th>Role</th>
                            <th>Unit / Departemen</th>
                            <th>No. HP</th>
                            <th>Bergabung</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="badge bg-primary ms-1" style="font-size:.65rem;">Anda</span>
                                @endif
                            </td>
                            <td class="text-muted" style="font-size:.85rem;">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Administrator</span>
                                @elseif($user->role === 'teknisi_hardware')
                                    <span class="badge" style="background:#3b82f6;color:#fff;">Teknisi Hardware</span>
                                @elseif($user->role === 'teknisi_software')
                                    <span class="badge" style="background:#8b5cf6;color:#fff;">Teknisi Software</span>
                                @else
                                    <span class="badge bg-secondary">Pelapor</span>
                                @endif
                            </td>
                            <td>
                                @if($user->department)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-building me-1" style="font-size:.7rem;"></i>{{ $user->department->name }}
                                    </span>
                                @elseif($user->unit)
                                    {{ $user->unit }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td class="text-muted" style="font-size:.82rem;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php $isAdmin = auth()->user()->role === 'admin'; @endphp
                                <div class="d-flex gap-1">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ $isAdmin ? route('admin.users.edit', $user->id) : route('users.edit', $user->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    @if($user->id !== auth()->id())
                                        @if($isAdmin && $user->role !== 'admin')
                                            {{-- Admin bisa hapus siapapun kecuali admin lain --}}
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                  class="js-confirm-form"
                                                  data-confirm-title="Hapus User"
                                                  data-confirm-message="Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus user">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($isAdmin && $user->role === 'admin')
                                            {{-- Admin tidak bisa hapus admin lain --}}
                                            <span class="btn btn-sm btn-outline-secondary disabled" title="Akun Admin tidak dapat dihapus">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                        @elseif(!$isAdmin && $user->role === 'user')
                                            {{-- Teknisi hanya bisa hapus user biasa --}}
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                  class="js-confirm-form"
                                                  data-confirm-title="Hapus User"
                                                  data-confirm-message="Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus user">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Teknisi tidak bisa hapus sesama Teknisi --}}
                                            <span class="btn btn-sm btn-outline-secondary disabled" title="Akun Teknisi tidak dapat dihapus">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
