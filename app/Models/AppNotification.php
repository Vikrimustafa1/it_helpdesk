<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id',
        'message',
        'url',
        'type',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    /**
     * Kirim notifikasi ke satu user.
     */
    public static function send(int $userId, string $message, string $url = null, string $type = 'info'): void
    {
        static::create([
            'user_id' => $userId,
            'message' => $message,
            'url'     => $url,
            'type'    => $type,
        ]);
    }


    public static function sendToAllStaff(string $message, string $url = null, string $type = 'info'): void
    {
        $staffUsers = User::whereIn('role', ['admin', 'teknisi_hardware', 'teknisi_software'])->pluck('id');
        foreach ($staffUsers as $userId) {
            static::send($userId, $message, $url, $type);
        }
    }

    /**
     * @deprecated Gunakan sendToAllStaff() agar admin juga menerima notifikasi.
     */
    public static function sendToAllItSupport(string $message, string $url = null, string $type = 'info'): void
    {
        static::sendToAllStaff($message, $url, $type);
    }
}
