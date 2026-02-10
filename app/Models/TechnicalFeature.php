<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalFeature extends Model
{
    protected $fillable = [
        'baslik',
        'gosterim',
        'ikon',
    ];
}
