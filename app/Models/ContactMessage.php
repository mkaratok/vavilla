<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'konu', 'mesaj', 'tarih', 'gonderen', 'durum',
        'ip', 'alici', 'dosya',
    ];

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('durum', 0);
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update(['durum' => 1]);
    }
}
