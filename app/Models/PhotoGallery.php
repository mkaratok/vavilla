<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhotoGallery extends Model
{
    protected $fillable = [
        'baslik',
        'sef',
        'kelime',
        'kisa',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PhotoGalleryImage::class, 'gallery_id');
    }
}
