<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'rental_code',
        'user_id',
        'bus_id',
        'driver_id',
        'conductor_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'payment_status'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
} 