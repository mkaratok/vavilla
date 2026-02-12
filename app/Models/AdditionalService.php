<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    protected $fillable = [
        'baslik',
        'fiyat',
        'aciklama',
        'ikon',
    ];

    protected $casts = [
        'fiyat' => 'decimal:2',
    ];
}
