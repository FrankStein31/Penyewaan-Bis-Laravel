<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
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