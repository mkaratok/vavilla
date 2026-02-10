<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'youtube',
        'gorsel',
        'baslik',
    ];

    // Get youtube embed URL
    public function getEmbedUrlAttribute(): string
    {
        return "https://www.youtube.com/embed/{$this->youtube}";
    }

    // Get youtube thumbnail
    public function getThumbnailAttribute(): string
    {
        return "https://img.youtube.com/vi/{$this->youtube}/hqdefault.jpg";
    }
}
