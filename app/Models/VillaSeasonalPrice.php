<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaSeasonalPrice extends Model
{
    protected $fillable = [
        'villa_id',
        'tarih1',
        'tarih2',
        'fiyat',
    ];

    protected $casts = [
        'tarih1' => 'date',
        'tarih2' => 'date',
    ];

    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }
}
