<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'address',
        'description',
        'photo',
        'phone',
        'open_time',
        'close_time',
        'status'
    ];

    public function owner()
    {
        return $this->belongsTo(
            User::class,
            'owner_id'
        );
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function staff()
    {
        return $this->hasMany(User::class, 'venue_id')->where('role', 'penjaga');
    }
}
