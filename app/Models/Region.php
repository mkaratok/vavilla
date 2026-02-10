<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'baslik',
        'kelime',
        'kisa',
        'icerik',
        'sef',
    ];

    public function villas(): HasMany
    {
        return $this->hasMany(Villa::class);
    }
}
