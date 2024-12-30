<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_id',
        'payment_code',
        'amount',
        'payment_method',
        'status', // pending, verified, failed
        'proof_of_payment',
        'verified_at',
        'verified_by'
    ];

    protected $dates = [
        'verified_at'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
} 