<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Halaman semua riwayat notifikasi.
     */
    public function index(): View
    {
        $notifications = AppNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        // Tandai semua sebagai dibaca saat membuka halaman ini
        AppNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca, lalu redirect ke URL-nya.
     */
    public function read(int $id): RedirectResponse
    {
        $notif = AppNotification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notif->update(['read_at' => now()]);

        return redirect($notif->url ?? '/');
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function readAll(): JsonResponse
    {
        AppNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    /**
     * Polling endpoint — kembalikan jumlah unread + 20 notif terbaru.
     * Dipakai oleh AJAX polling di frontend (tanpa full page reload).
     */
    public function poll(): JsonResponse
    {
        $userId = Auth::id();

        $unreadCount = AppNotification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        $notifications = AppNotification::where('user_id', $userId)
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($n) => [
                'id'       => $n->id,
                'message'  => $n->message,
                'url'      => $n->url,
                'type'     => $n->type,
                'read_at'  => $n->read_at,
                'time_ago' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'unread_count'  => $unreadCount,
            'notifications' => $notifications,
        ]);
    }
}
