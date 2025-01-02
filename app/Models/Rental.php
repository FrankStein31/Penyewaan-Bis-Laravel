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
        'pickup_location',
        'destination',
        'total_days',
        'total_price',
        'status',
        'rental_status',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_price' => 'decimal:2'
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
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'conductor_id');
    }

    public function ratings()
    {
        return $this->hasOne(Rating::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Generate rental code
    public static function generateRentalCode()
    {
        $prefix = 'RNT';
        $date = now()->format('Ymd');
        $lastRental = self::whereDate('created_at', today())
            ->latest()
            ->first();

        if ($lastRental) {
            $lastNumber = intval(substr($lastRental->rental_code, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }
} 