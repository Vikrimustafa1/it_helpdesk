<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketPhoto;
use App\Models\TicketProgress;
use App\Models\AppNotification;
use App\Models\Department;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Daftar tiket untuk IT Support / Teknisi dengan filter + pagination.
     * Semua teknisi dapat melihat semua tiket.
     */
    public function index(Request $request): View
    {
        $filters = [
            'status'   => $request->query('status'),
            'kategori' => $request->query('kategori'),
            'prioritas'=> $request->query('prioritas'),
            'dari'     => $request->query('dari'),
            'sampai'   => $request->query('sampai'),
            'search'   => $request->query('search'),
        ];

        /** @var LengthAwarePaginator $tickets */
        $tickets = Ticket::query()
            ->with(['user', 'handler'])
            ->filter($filters)
            ->paginate(10)
            ->withQueryString();

        return view('tickets.index', [
            'tickets' => $tickets,
            'filters' => $filters,
        ]);
    }

    /**
     * Form buat tiket baru (user).
     */
    public function create(): View
    {
        $frequentIssues = Ticket::query()
            ->selectRaw('deskripsi, kategori, COUNT(*) as total')
            ->groupBy('deskripsi', 'kategori')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($t) => [
                'masalah'  => $t->deskripsi,
                'kategori' => $t->kategori,
                'total'    => $t->total,
            ]);

        $categories  = TicketCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('tickets.create', [
            'frequentIssues' => $frequentIssues,
            'categories'     => $categories,
            'departments'    => $departments,
        ]);
    }

    /**
     * Simpan tiket baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_category_id' => ['nullable', 'exists:ticket_categories,id'],
            'department_id'      => ['nullable', 'exists:departments,id'],
            'unit'               => ['nullable', 'string', 'max:255'],
            'kategori'           => ['nullable', 'string', 'max:100'],
            'deskripsi'          => ['required', 'string'],
            'fotos'              => ['nullable', 'array', 'max:5'],
            'fotos.*'            => ['image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $namaKategori = null;
        if (!empty($validated['ticket_category_id'])) {
            $namaKategori = TicketCategory::find($validated['ticket_category_id'])?->name;
        } elseif (!empty($validated['kategori'])) {
            $namaKategori = $validated['kategori'];
        }

        $namaUnit = null;
        if (!empty($validated['department_id'])) {
            $namaUnit = Department::find($validated['department_id'])?->name;
        } elseif (!empty($validated['unit'])) {
            $namaUnit = $validated['unit'];
        }

        $ticket = new Ticket();
        try {
            $ticket->kode_tiket = Ticket::generateKodeTiket();
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
        $ticket->user_id             = Auth::id();
        $ticket->unit                = $namaUnit;
        $ticket->kategori            = $namaKategori;
        $ticket->ticket_category_id  = $validated['ticket_category_id'] ?? null;
        $ticket->deskripsi           = $validated['deskripsi'];
        $ticket->status              = 'Open';
        $ticket->save();

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('ticket_fotos', 'public');
                TicketPhoto::create(['ticket_id' => $ticket->id, 'path' => $path]);
            }
        }

        $pelapor = Auth::user()->name;
        AppNotification::sendToAllStaff(
            "Tiket baru dari {$pelapor} — {$ticket->kode_tiket} ({$ticket->kategori})",
            route('tickets.show', $ticket->id),
            'info'
        );

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Tiket berhasil dibuat dengan kode ' . $ticket->kode_tiket . '.');
    }

    /**
     * Detail tiket (shared).
     */
    public function show(int $id): View
    {
        $ticket = Ticket::with(['user', 'handler', 'progress.updater', 'photos'])->findOrFail($id);

        $user = Auth::user();
        if ($user !== null && $user->role === 'user' && $ticket->user_id !== $user->id) {
            abort(403);
        }

        return view('tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Form klasifikasi / update tiket untuk IT Support / Teknisi.
     */
    public function edit(int $id): View
    {
        $ticket = Ticket::with(['user', 'handler', 'ticketCategory'])->findOrFail($id);

        $user        = Auth::user();
        $isLocked    = $ticket->handled_by !== null && $ticket->handled_by !== $user->id;
        $lockedByMe  = $ticket->handled_by === $user->id;

        // Cek apakah teknisi boleh tangani kategori ini
        $allowedKategori = $user->getAllowedKategori();
        $categoryAllowed = $allowedKategori === null || $ticket->kategori === $allowedKategori;

        return view('tickets.edit', [
            'ticket'          => $ticket,
            'isLocked'        => $isLocked,
            'lockedByMe'      => $lockedByMe,
            'categoryAllowed' => $categoryAllowed,
            'allowedKategori' => $allowedKategori,
        ]);
    }

    /**
     * Update status dan atribut klasifikasi.
     * Blokir jika teknisi tidak berwenang pada kategori tiket ini.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        // ── CATEGORY CHECK: blokir jika teknisi salah kategori
        $allowedKategori = $user->getAllowedKategori();
        if ($allowedKategori !== null && $ticket->kategori !== $allowedKategori) {
            return redirect()
                ->route('tickets.edit', $ticket->id)
                ->with('error', "Anda hanya dapat menangani tiket kategori {$allowedKategori}. Tiket ini adalah kategori {$ticket->kategori}.");
        }

        // ── LOCK CHECK
        if ($ticket->handled_by !== null && $ticket->handled_by !== Auth::id()) {
            return redirect()
                ->route('tickets.edit', $ticket->id)
                ->with('error', 'Tiket ini sedang dikerjakan oleh ' . ($ticket->handler?->name ?? 'teknisi lain') . '. Anda tidak dapat mengubah status atau klasifikasinya.');
        }

        $validated = $request->validate([
            'tingkat_keparahan' => ['nullable', Rule::in(['Low', 'Medium', 'High', 'Critical'])],
            'prioritas'         => ['nullable', Rule::in(['Low', 'Medium', 'High', 'Urgent'])],
            'metode_penanganan' => ['nullable', Rule::in(['Remote', 'Onsite'])],
            'status'            => ['required', Rule::in(['Open', 'Diproses', 'Selesai', 'Closed'])],
        ]);

        $oldSeverity = $ticket->tingkat_keparahan;
        $oldStatus   = $ticket->status;

        $ticket->tingkat_keparahan = $validated['tingkat_keparahan'] ?? $ticket->tingkat_keparahan;
        $ticket->prioritas         = $validated['prioritas'] ?? $ticket->prioritas;
        $ticket->metode_penanganan = $validated['metode_penanganan'] ?? $ticket->metode_penanganan;

        $ticket->updateStatus($validated['status'], Auth::id());

        if ($ticket->tingkat_keparahan !== null && $ticket->tingkat_keparahan !== $oldSeverity) {
            $ticket->setSlaDeadline();
        }

        $ticket->save();

        if ($oldStatus !== $validated['status'] && $ticket->user_id) {
            AppNotification::send(
                $ticket->user_id,
                "Status tiket {$ticket->kode_tiket} diubah menjadi {$validated['status']}.",
                route('tickets.show', $ticket->id),
                'info'
            );
        }

        return redirect()
            ->route('tickets.edit', $ticket->id)
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * Tambah progress tiket (Teknisi/IT Support).
     * Blokir jika teknisi tidak berwenang pada kategori tiket ini.
     */
    public function addProgress(Request $request, int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        if ($ticket->status === 'Closed') {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('error', 'Tiket ini sudah ditutup (Closed). Catatan progress tidak dapat ditambahkan.');
        }

        // ── CATEGORY CHECK: blokir jika teknisi salah kategori
        $allowedKategori = $user->getAllowedKategori();
        if ($allowedKategori !== null && $ticket->kategori !== $allowedKategori) {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('error', "Anda hanya dapat menambah progress pada tiket kategori {$allowedKategori}.");
        }

        if ($ticket->handled_by !== null && $ticket->handled_by !== Auth::id()) {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('error', 'Tiket ini sedang dikerjakan oleh ' . ($ticket->handler?->name ?? 'teknisi lain') . '. Hanya teknisi yang menangani yang dapat menambah catatan progress.');
        }

        $validated = $request->validate([
            'catatan' => ['required', 'string'],
            'foto'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('ticket_progress_fotos', 'public');
        }

        TicketProgress::create([
            'ticket_id'  => $ticket->id,
            'catatan'    => $validated['catatan'],
            'foto'       => $pathFoto,
            'updated_by' => Auth::id(),
            'role'       => null, // null = catatan teknisi
        ]);

        if ($ticket->user_id) {
            $teknisi = Auth::user()->name;
            AppNotification::send(
                $ticket->user_id,
                "Teknisi ({$teknisi}) menambahkan catatan progress pada tiket {$ticket->kode_tiket}.",
                route('tickets.show', $ticket->id),
                'info'
            );
        }

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Progress tiket berhasil ditambahkan.');
    }

    /**
     * Tambah catatan dari user pelapor (saat status Diproses).
     */
    public function addUserNote(Request $request, int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        // Hanya pelapor tiket yang boleh
        if ($ticket->user_id !== $user->id) {
            abort(403);
        }

        // Tiket harus berstatus Diproses
        if ($ticket->status !== 'Diproses') {
            return back()->with('error', 'Catatan hanya dapat ditambahkan saat tiket berstatus Diproses.');
        }

        $validated = $request->validate([
            'catatan_user' => ['required', 'string', 'max:2000'],
        ]);

        TicketProgress::create([
            'ticket_id'  => $ticket->id,
            'catatan'    => $validated['catatan_user'],
            'foto'       => null,
            'updated_by' => $user->id,
            'role'       => 'user', // 'user' = catatan dari pelapor
        ]);

        // Notifikasi ke teknisi yang menangani
        if ($ticket->handled_by) {
            AppNotification::send(
                $ticket->handled_by,
                "Pelapor ({$user->name}) menambahkan catatan pada tiket {$ticket->kode_tiket}.",
                route('tickets.show', $ticket->id),
                'warning'
            );
        }

        return back()->with('success', 'Catatan berhasil ditambahkan. Teknisi akan melihat update Anda.');
    }

    /**
     * Riwayat tiket milik user.
     */
    public function myTickets(Request $request): View
    {
        $user = Auth::user();

        $filters = [
            'status'   => $request->query('status'),
            'kategori' => $request->query('kategori'),
            'search'   => $request->query('search'),
        ];

        $query = Ticket::query()->where('user_id', $user?->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search): void {
                $q->where('kode_tiket', 'like', '%' . $search . '%')
                    ->orWhere('unit', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('tickets.my-tickets', [
            'tickets' => $tickets,
            'filters' => $filters,
        ]);
    }
}
