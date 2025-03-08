<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalExtension extends Model
{
    protected $fillable = [
        'rental_id',
        'additional_days',
        'start_date',
        'end_date',
        'status',
        'notes',
        'additional_price',
        'payment_status',
        'paid_at',
        'payment_data'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'paid_at' => 'datetime'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}