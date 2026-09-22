<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dog extends Model
{
    protected $fillable = [
        'name',
        'breed',
        'birthdate',
        'weight',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
