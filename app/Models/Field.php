<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'venue_id',
        'name',
        'slug',
        'sport_type',
        'description',
        'thumbnail',
        'price_per_hour',
        'capacity',
        'status'
    ];

    public function venue()
    {
        return $this->belongsTo(
            Venue::class
        );
    }

    public function operatingSchedules()
    {
        return $this->hasMany(
            OperatingSchedule::class
        );
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
