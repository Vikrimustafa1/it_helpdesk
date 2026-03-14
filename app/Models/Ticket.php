<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use App\Models\TicketPhoto;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tiket',
        'user_id',
        'unit',
        'kategori',
        'ticket_category_id',
        'deskripsi',
        'tingkat_keparahan',
        'prioritas',
        'metode_penanganan',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'sla_deadline',
        'foto',
        'handled_by',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
        'sla_deadline'  => 'datetime',
    ];

    /**
     * Relasi ke pelapor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke teknisi yang menangani.
     */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Relasi ke progress tiket.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(TicketProgress::class, 'ticket_id')->latest();
    }

    /**
     * Relasi ke foto-foto tiket.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(TicketPhoto::class, 'ticket_id');
    }

    /**
     * Relasi ke feedback user.
     */
    public function feedback(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TicketFeedback::class, 'ticket_id');
    }

    /**
     * Relasi ke kategori tiket (master data).
     */
    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * Mendapatkan nama kategori dari relasi atau fallback ke kolom kategori lama.
     */
    public function getNamaKategori(): string
    {
        if ($this->ticketCategory !== null) {
            return $this->ticketCategory->name;
        }
        return $this->kategori ?? '-';
    }

    /**
     * Generate kode tiket: IT-YYYYMMDD-XXX (reset per hari).
     */
    public static function generateKodeTiket(): string
    {
        return DB::transaction(static function (): string {
            $today = Carbon::now()->format('Ymd');
            $prefix = 'IT-' . $today . '-';

            $last = static::where('kode_tiket', 'like', $prefix . '%')
                ->orderBy('kode_tiket', 'desc')
                ->lockForUpdate()
                ->first();

            $nextNumber = 1;

            if ($last !== null) {
                $lastSequence = (int) substr($last->kode_tiket, -3);
                $nextNumber = $lastSequence + 1;
            }

            // Guard: maksimal 999 tiket per hari
            if ($nextNumber > 999) {
                throw new \RuntimeException(
                    'Batas maksimal tiket harian (999) telah tercapai. Silakan hubungi administrator.'
                );
            }

            $sequence = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

            return $prefix . $sequence;
        });
    }

    /**
     * Hitung durasi dalam menit dari waktu_mulai ke waktu_selesai.
     */
    public function hitungDurasi(): ?int
    {
        if ($this->waktu_mulai === null || $this->waktu_selesai === null) {
            return null;
        }

        return $this->waktu_mulai->diffInMinutes($this->waktu_selesai);
    }

    /**
     * Set SLA deadline berdasarkan tingkat keparahan.
     */
    public function setSlaDeadline(): void
    {
        if ($this->tingkat_keparahan === null) {
            $this->sla_deadline = null;

            return;
        }

        $createdAt = $this->created_at instanceof Carbon
            ? $this->created_at
            : Carbon::parse($this->created_at);

        switch ($this->tingkat_keparahan) {
            case 'Critical':
                $this->sla_deadline = $createdAt->copy()->addHour();
                break;
            case 'High':
                $this->sla_deadline = $createdAt->copy()->addHours(4);
                break;
            case 'Medium':
                $this->sla_deadline = $createdAt->copy()->addHours(8);
                break;
            case 'Low':
                $this->sla_deadline = $createdAt->copy()->addDay();
                break;
            default:
                $this->sla_deadline = null;
                break;
        }
    }

    /**
     * Badge HTML untuk status.
     */
    public function statusBadge(): string
    {
        $map = [
            'Open'     => 'secondary',
            'Diproses' => 'warning',
            'Selesai'  => 'success',
            'Closed'   => 'dark',
        ];

        $class = $map[$this->status] ?? 'secondary';

        return '<span class="badge bg-' . $class . '">' . e($this->status) . '</span>';
    }

    /**
     * Badge HTML untuk prioritas.
     */
    public function prioritasBadge(): string
    {
        if ($this->prioritas === null) {
            return '<span class="badge bg-light text-muted">-</span>';
        }

        $map = [
            'Urgent' => 'danger',
            'High'   => 'warning',
            'Medium' => 'info',
            'Low'    => 'secondary',
        ];

        $class = $map[$this->prioritas] ?? 'secondary';

        return '<span class="badge bg-' . $class . '">' . e($this->prioritas) . '</span>';
    }

    /**
     * Badge HTML untuk tingkat keparahan.
     */
    public function keparahanBadge(): string
    {
        if ($this->tingkat_keparahan === null) {
            return '<span class="badge bg-light text-muted">-</span>';
        }

        $map = [
            'Critical' => 'danger',
            'High'     => 'warning',
            'Medium'   => 'info',
            'Low'      => 'secondary',
        ];

        $class = $map[$this->tingkat_keparahan] ?? 'secondary';

        return '<span class="badge bg-' . $class . '">' . e($this->tingkat_keparahan) . '</span>';
    }

    /**
     * Icon kategori (Bootstrap Icons).
     */
    public function kategoriIcon(): string
    {
        $colors = [
            'Hardware' => ['bg' => '#eff6ff', 'text' => '#2563eb', 'border' => '#bfdbfe'],
            'Software' => ['bg' => '#f5f3ff', 'text' => '#7c3aed', 'border' => '#ddd6fe'],
            'Jaringan' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'border' => '#bbf7d0'],
            'SIMRS'    => ['bg' => '#fef2f2', 'text' => '#dc2626', 'border' => '#fecaca'],
        ];

        $c = $colors[$this->kategori] ?? ['bg' => '#f8fafc', 'text' => '#64748b', 'border' => '#e2e8f0'];

        return '<span style="display:inline-block;padding:.25rem .75rem;border-radius:9999px;font-size:.8rem;font-weight:600;background:' . $c['bg'] . ';color:' . $c['text'] . ';border:1px solid ' . $c['border'] . ';">'
            . e($this->kategori ?? '-')
            . '</span>';
    }

    /**
     * Format durasi menjadi string ramah.
     */
    public function durasiFormatted(): string
    {
        $minutes = $this->durasi_menit ?? $this->hitungDurasi();

        if ($minutes === null) {
            return '-';
        }

        if ($minutes < 60) {
            return $minutes . ' menit';
        }

        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        if ($remaining === 0) {
            return $hours . ' jam';
        }

        return $hours . ' jam ' . $remaining . ' menit';
    }

    /**
     * Cek apakah tiket sudah melewati SLA dan belum selesai/closed.
     */
    public function isOverdue(): bool
    {
        if ($this->sla_deadline === null) {
            return false;
        }

        if (in_array($this->status, ['Selesai', 'Closed'], true)) {
            return false;
        }

        return Carbon::now()->greaterThan($this->sla_deadline);
    }

    /**
     * Scope filter untuk daftar tiket IT Support.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['prioritas'])) {
            $query->where('prioritas', $filters['prioritas']);
        }

        if (!empty($filters['dari'])) {
            $query->whereDate('created_at', '>=', $filters['dari']);
        }

        if (!empty($filters['sampai'])) {
            $query->whereDate('created_at', '<=', $filters['sampai']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search): void {
                $q->where('kode_tiket', 'like', '%' . $search . '%')
                    ->orWhere('unit', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    /**
     * Update status dengan aturan waktu mulai/selesai.
     * Handler ID kini dioper dari luar (controller) agar model
     * tidak bergantung pada konteks HTTP/Auth.
     */
    public function updateStatus(string $status, ?int $handlerId = null): void
    {
        $now = Carbon::now();

        if ($status === 'Diproses' && $this->waktu_mulai === null) {
            $this->waktu_mulai = $now;
            // Set handler hanya jika belum ada dan handlerId diberikan
            if ($this->handled_by === null && $handlerId !== null) {
                $this->handled_by = $handlerId;
            }
        }

        if ($status === 'Selesai' && $this->waktu_selesai === null) {
            $this->waktu_selesai = $now;
            $this->durasi_menit = $this->hitungDurasi();
        }

        $this->status = $status;
    }
}
