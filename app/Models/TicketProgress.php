<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketProgress extends Model
{
    use HasFactory;

    protected $table = 'ticket_progress';

    protected $fillable = [
        'ticket_id',
        'catatan',
        'foto',
        'updated_by',
        'role',
    ];

    /**
     * Relasi ke tiket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Relasi ke user yang mengupdate.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

