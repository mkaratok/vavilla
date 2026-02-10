<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'kullanici_adi',
        'sifre',
        'email',
        'tel',
        'adsoyad',
        'songiris',
        'songirisip',
    ];

    protected $hidden = [
        'sifre',
    ];

    // Override the default password column
    public function getAuthPassword()
    {
        return $this->sifre;
    }
}
