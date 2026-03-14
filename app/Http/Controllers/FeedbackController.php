<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketFeedback;
use App\Models\TicketProgress;
use App\Models\AppNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Hanya pelapor tiket yang boleh memberi feedback
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        // Tiket harus sudah selesai / closed
        if (!in_array($ticket->status, ['Selesai', 'Closed'], true)) {
            return back()->with('error', 'Feedback hanya dapat diberikan untuk tiket yang sudah selesai.');
        }

        // Cek apakah sudah pernah memberi feedback
        if ($ticket->feedback()->exists()) {
            return back()->with('error', 'Anda sudah memberikan feedback untuk tiket ini.');
        }

        $validated = $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $feedback = TicketFeedback::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'rating'    => $validated['rating'],
            'komentar'  => $validated['komentar'] ?? null,
        ]);

        // ── FEEDBACK BURUK: Buka kembali tiket untuk penanganan ulang
        if ($feedback->rating <= 2) {
            $ticket->status        = 'Diproses';
            $ticket->waktu_selesai = null;
            $ticket->durasi_menit  = null;
            $ticket->save();

            // Catat progress otomatis
            TicketProgress::create([
                'ticket_id'  => $ticket->id,
                'catatan'    => "⚠️ Penanganan ulang diperlukan — Feedback user menunjukkan masalah belum terselesaikan (rating {$feedback->rating}/5). Waktu penanganan direset.",
                'foto'       => null,
                'updated_by' => Auth::id(),
                'role'       => 'system',
            ]);

            // Notifikasi ke handler
            if ($ticket->handled_by) {
                $pelapor = Auth::user()?->name ?? 'Pelapor';
                AppNotification::send(
                    $ticket->handled_by,
                    "⚠️ Feedback buruk dari {$pelapor} untuk tiket {$ticket->kode_tiket} (rating {$feedback->rating}/5). Tiket dibuka kembali untuk penanganan ulang.",
                    route('tickets.show', $ticket->id),
                    'danger'
                );
            }

            return back()->with('warning', 'Feedback dikirim. Karena masalah belum terselesaikan, tiket dikembalikan ke teknisi untuk penanganan ulang.');
        }

        // Feedback biasa (rating ≥ 3): kirim notifikasi ke handler
        if ($ticket->handled_by) {
            $pelapor = Auth::user()?->name ?? 'Pelapor';
            $message = "Feedback dari {$pelapor} untuk tiket {$ticket->kode_tiket} (rating {$feedback->rating}/5).";

            AppNotification::send(
                $ticket->handled_by,
                $message,
                route('tickets.show', $ticket->id),
                'success'
            );
        }

        return back()->with('success', 'Terima kasih! Feedback Anda telah dikirimkan.');
    }
}
