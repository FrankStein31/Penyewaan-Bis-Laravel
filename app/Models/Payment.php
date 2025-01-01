<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_id',
        'amount',
        'payment_method',
        'payment_status',
        'payment_proof',
        'payment_date',
        'notes'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
} 