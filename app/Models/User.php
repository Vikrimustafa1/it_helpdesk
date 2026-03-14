<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit',
        'phone',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tiket yang dibuat user ini.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    /**
     * Relasi ke tiket yang ditangani sebagai teknisi.
     */
    public function handledTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'handled_by');
    }

    /**
     * Relasi ke progress yang dibuat user ini.
     */
    public function progressUpdates(): HasMany
    {
        return $this->hasMany(TicketProgress::class, 'updated_by');
    }

    /**
     * Cek apakah user adalah salah satu jenis teknisi (Hardware atau Software).
     */
    public function isTekniksi(): bool
    {
        return in_array($this->role, ['teknisi_hardware', 'teknisi_software'], true);
    }

    /**
     * Mendapatkan kategori tiket yang boleh ditangani user ini.
     * null = semua kategori.
     */
    public function getAllowedKategori(): ?string
    {
        return match ($this->role) {
            'teknisi_hardware' => 'Hardware',
            'teknisi_software' => 'Software',
            default            => null,
        };
    }

    /**
     * Cek apakah user adalah user biasa (pelapor).
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Cek apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relasi ke departemen user.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
