<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_id',
        'extension_id',
        'payment_code',
        'amount',
        'payment_method',
        'payment_proof',
        'status',
        'notes'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
} 