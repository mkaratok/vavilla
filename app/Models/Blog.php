<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'baslik', 'kelime', 'icerik', 'kategori', 'foto', 'video',
        'resim', 'sef', 'durum', 'kisa', 'tarih', 'etiket',
        'okunma', 'yazar', 'ay', 'anasayfa',
    ];

    // Scope for active posts
    public function scopeActive($query)
    {
        return $query->where('durum', 1);
    }

    // Scope for homepage posts
    public function scopeHomepage($query)
    {
        return $query->where('anasayfa', 1)->where('durum', 1);
    }

    // Increment read count
    public function incrementReadCount()
    {
        $this->increment('okunma');
    }
}
