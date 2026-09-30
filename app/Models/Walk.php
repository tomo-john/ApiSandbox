<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Walk extends Model
{
    protected $fillable = [
        'walked_at',
        'duration_minutes',
        'distance_km',
    ];

    public function dog(): BelongsTo
    {
        return $this->belongsTo(Dog::class);
    }
}
