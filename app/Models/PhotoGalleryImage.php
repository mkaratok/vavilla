<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoGalleryImage extends Model
{
    protected $fillable = [
        'gallery_id',
        'kresim',
        'bresim',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(PhotoGallery::class, 'gallery_id');
    }
}
