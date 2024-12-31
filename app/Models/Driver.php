<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'license_number',
        'phone',
        'address',
        'license_expire',
        'status',
        'photo',
        'is_active'
    ];

    protected $casts = [
        'license_expire' => 'date',
        'is_active' => 'boolean'
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