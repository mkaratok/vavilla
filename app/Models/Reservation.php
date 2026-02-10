<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'villa_id', 'adsoyad', 'email', 'telefon', 'yetiskin', 'cocuk',
        'adres', 'notu', 'toplam_tutar', 'gelis_tarihi', 'cikis_tarihi',
        'rezervasyon_tarihi', 'ip', 'durum', 'admin_notu', 'tc',
        'harici', 'odeme_tipi', 'ek_hizmetler',
    ];

    protected $casts = [
        'gelis_tarihi' => 'date',
        'cikis_tarihi' => 'date',
        'rezervasyon_tarihi' => 'date',
        'ek_hizmetler' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;

    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    // Get status label
    public function getStatusLabelAttribute(): string
    {
        return match($this->durum) {
            self::STATUS_PENDING => 'Beklemede',
            self::STATUS_CONFIRMED => 'Onaylandı',
            self::STATUS_COMPLETED => 'Tamamlandı',
            self::STATUS_CANCELLED => 'İptal',
            default => 'Bilinmiyor',
        };
    }

    // Get status color for UI
    public function getStatusColorAttribute(): string
    {
        return match($this->durum) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONFIRMED => 'success',
            self::STATUS_COMPLETED => 'info',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary',
        };
    }

    // Calculate night count
    public function getNightCountAttribute(): int
    {
        return $this->gelis_tarihi->diffInDays($this->cikis_tarihi);
    }

    // Scope for pending reservations
    public function scopePending($query)
    {
        return $query->where('durum', self::STATUS_PENDING);
    }

    // Scope for confirmed reservations
    public function scopeConfirmed($query)
    {
        return $query->where('durum', self::STATUS_CONFIRMED);
    }

    // Scope for external reservations
    public function scopeExternal($query)
    {
        return $query->where('harici', 1);
    }
}
