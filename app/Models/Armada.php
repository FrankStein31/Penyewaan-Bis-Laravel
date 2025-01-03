<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    protected $table = 'armada';
    protected $primaryKey = 'armada_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_armada'
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class, 'armada_id', 'armada_id');
    }
} 