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

    protected $appends = [
        'thumbnail_url',
        'price_formatted',
        'sport_type_label',
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

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews() {
        return $this->hasMany(Review::class);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/'.$this->thumbnail);
        }

        return 'https://picsum.photos/seed/'.$this->slug.'/800/500';
    }

    public function getPriceFormattedAttribute(): string
    {
        return number_format((float) $this->price_per_hour, 0, ',', '.');
    }

    public function getSportTypeLabelAttribute(): string
    {
        return ucfirst($this->sport_type);
    }
}
