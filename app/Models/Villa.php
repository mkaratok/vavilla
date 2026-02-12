<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Villa extends Model
{
    protected $fillable = [
        'baslik', 'kelime', 'kisa', 'icerik', 'konum', 'fiyat', 'eski_fiyat',
        'region_id', 'villa_tipi', 'teknik_ozellikler', 'anasayfa', 'firsat',
        'mesafe_bilgileri', 'genel_bilgiler', 'durum', 'minimum_kiralama_suresi',
        'gorsel', 'sef', 'adet', 'yetiskin', 'anasayfa_sira', 'ek_hizmetler', 'adres',
    ];

    protected $casts = [
        'villa_tipi' => 'array',
        'teknik_ozellikler' => 'array',
        'mesafe_bilgileri' => 'array',
        'genel_bilgiler' => 'array',
        'ek_hizmetler' => 'array',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(VillaImage::class);
    }

    public function seasonalPrices(): HasMany
    {
        return $this->hasMany(VillaSeasonalPrice::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CustomerReview::class);
    }

    // Get current price based on date
    public function getPriceForDate($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $seasonalPrice = $this->seasonalPrices()
            ->where('tarih1', '<=', $date)
            ->where('tarih2', '>=', $date)
            ->first();
        
        return $seasonalPrice ? $seasonalPrice->fiyat : $this->fiyat;
    }

    // Müsaitlik Kontrol Et for date range
    public function isAvailable($startDate, $endDate)
    {
        return !$this->reservations()
            ->whereIn('durum', [1, 2]) // confirmed or completed
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('gelis_tarihi', [$startDate, $endDate])
                    ->orWhereBetween('cikis_tarihi', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('gelis_tarihi', '<=', $startDate)
                          ->where('cikis_tarihi', '>=', $endDate);
                    });
            })
            ->exists();
    }

    // Scope for homepage villas
    public function scopeHomepage($query)
    {
        return $query->where('anasayfa', 1)->where('durum', 1)->orderBy('anasayfa_sira');
    }

    // Scope for active villas
    public function scopeActive($query)
    {
        return $query->where('durum', 1);
    }
}
