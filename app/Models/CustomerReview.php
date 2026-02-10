<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerReview extends Model
{
    protected $fillable = [
        'email', 'adsoyad', 'yorum', 'tarih', 'durum', 'villa_id',
        'temizlik', 'ucret', 'ulasim', 'servis', 'ortalama', 'telefon', 'ip',
    ];

    protected $casts = [
        'tarih' => 'date',
    ];

    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    // Scope for approved reviews
    public function scopeApproved($query)
    {
        return $query->where('durum', 1);
    }

    // Scope for pending reviews
    public function scopePending($query)
    {
        return $query->where('durum', 0);
    }

    // Calculate average before saving
    public static function boot()
    {
        parent::boot();

        static::saving(function ($review) {
            $review->ortalama = ($review->temizlik + $review->ucret + $review->ulasim + $review->servis) / 4;
        });
    }
}
