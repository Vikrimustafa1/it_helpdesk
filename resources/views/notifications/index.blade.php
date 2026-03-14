@extends('layouts.app')
@section('page-title', 'Riwayat Notifikasi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0" style="font-size:1.05rem;">
            <i class="bi bi-bell-fill text-primary me-2"></i>Riwayat Notifikasi
        </h5>
        <p class="text-muted mb-0" style="font-size:.8rem;">Semua notifikasi yang pernah Anda terima</p>
    </div>
    @if($notifications->total() > 0)
    <form method="POST" action="{{ route('notifications.readAll') }}" id="markAllForm">
        @csrf
        <button type="button" class="btn btn-sm btn-outline-primary"
                onclick="fetch('{{ route('notifications.readAll') }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}}).then(()=>window.location.reload())">
            <i class="bi bi-check2-all me-1"></i>Tandai semua dibaca
        </button>
    </form>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @forelse($notifications as $notif)
        <a href="{{ route('notifications.read', $notif->id) }}"
           class="d-block px-4 py-3 border-bottom text-decoration-none notif-row-item {{ $notif->read_at === null ? 'unread-row' : '' }}"
           style="transition:background .15s; position:relative;">
            <div class="d-flex align-items-start gap-3">
                {{-- Ikon status --}}
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:38px;height:38px; background:{{ $notif->read_at === null ? '#eff6ff' : '#f8fafc' }};">
                    <i class="bi bi-{{ $notif->read_at === null ? 'bell-fill text-primary' : 'bell text-secondary' }}"
                       style="font-size:.95rem;"></i>
                </div>
                {{-- Pesan --}}
                <div style="flex:1; min-width:0;">
                    <div style="font-size:.875rem; line-height:1.5; color:{{ $notif->read_at === null ? '#1e293b' : '#64748b' }};">
                        {{ $notif->message }}
                    </div>
                    <div class="mt-1 d-flex align-items-center gap-2">
                        <span style="font-size:.72rem; color:#94a3b8;">
                            <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                            &nbsp;·&nbsp; {{ $notif->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
                {{-- Dot unread --}}
                @if($notif->read_at === null)
                <div class="flex-shrink-0 mt-2" style="width:8px;height:8px;background:#3b82f6;border-radius:50%;"></div>
                @endif
            </div>
        </a>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash d-block mb-2" style="font-size:2.5rem;"></i>
            <div style="font-size:.9rem;">Belum ada notifikasi</div>
        </div>
        @endforelse
    </div>
</div>

{{-- Pagination --}}
@if($notifications->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $notifications->links() }}
</div>
@endif

@endsection

@push('styles')
<style>
    .notif-row-item { color: #334155; }
    .notif-row-item:hover { background: #f0f6ff !important; }
    .notif-row-item.unread-row { background: #f8fbff; }

    html.dark .notif-row-item { color: #cbd5e1; }
    html.dark .notif-row-item:hover { background: #1a2740 !important; }
    html.dark .notif-row-item.unread-row { background: #0f2942 !important; }
    html.dark .notif-row-item [style*="background:#eff6ff"] { background: rgba(59,130,246,.15) !important; }
    html.dark .notif-row-item [style*="background:#f8fafc"] { background: rgba(100,116,139,.1) !important; }
    html.dark .notif-row-item [style*="color:#1e293b"] { color: #e2e8f0 !important; }
    html.dark .notif-row-item [style*="color:#64748b"] { color: #64748b !important; }
</style>
@endpush
