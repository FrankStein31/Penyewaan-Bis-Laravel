<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'rental_id',
        'user_id',
        'ratable_id',   // ID bis/supir/kernet yang dirating
        'ratable_type', // Model type (Bus/Driver/Conductor)
        'rating',       // Nilai rating (1-5)
        'comment',      // Komentar opsional
    ];

    // Polymorphic relation untuk bis/supir/kernet
    public function ratable()
    {
        return $this->morphTo();
    }

    // Relasi ke rental
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    // Relasi ke user pemberi rating
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk memudahkan query
    public function scopeBusRatings($query)
    {
        return $query->where('ratable_type', Bus::class);
    }

    public function scopeDriverRatings($query)
    {
        return $query->where('ratable_type', Driver::class);
    }

    public function scopeConductorRatings($query)
    {
        return $query->where('ratable_type', Conductor::class);
    }
} 