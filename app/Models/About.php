<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'baslik',
        'icerik',
        'image_1',
        'image_2'
    ];
}
