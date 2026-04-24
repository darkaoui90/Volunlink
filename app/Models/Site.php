<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $fillable = [
        'name',
        'city',
        'type',
        'address',
        'capacity',
        'description',
        'latitude',
        'longitude',
    ];

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }
}
