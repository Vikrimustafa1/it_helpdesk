<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends Model
{
    protected $fillable = ['name', 'color', 'icon'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
