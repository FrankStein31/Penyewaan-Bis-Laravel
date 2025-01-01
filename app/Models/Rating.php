<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'rental_id',
        'driver_id',
        'conductor_id',
        'driver_rating',
        'conductor_rating',
        'driver_review',
        'conductor_review'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }
} 