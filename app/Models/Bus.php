<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'armada_id',
        'plate_number',
        'type',
        'capacity',
        'price_per_day',
        'description',
        'image',
        'status',
        'is_active'
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'is_active' => 'boolean',
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

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id', 'armada_id');
    }
} 