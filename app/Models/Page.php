<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'baslik', 'kategori', 'kelime', 'kisa', 'icerik',
        'sef', 'galeri', 'ust_resim', 'kutu_resim', 'sira',
    ];
}
