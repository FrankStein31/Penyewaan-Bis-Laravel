<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'plate_number',
        'type',
        'capacity',
        'price',
        'status'
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratable');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
} 