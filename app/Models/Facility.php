<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'type',
        'location',
        'capacity',
        'description',
        'status'
    ];

    public function reservations() 
    {
        return $this->hasMany(Reservation::class);
    }
    
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

}
