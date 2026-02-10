<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillaType extends Model
{
    protected $fillable = [
        'baslik',
        'kelime',
        'kisa',
        'icerik',
        'sef',
    ];
}
