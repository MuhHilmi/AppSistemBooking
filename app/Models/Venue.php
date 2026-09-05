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

    /**
     * Katalog item tukar poin milik venue ini (dikelola owner venue).
     */
    public function benefits()
    {
        return $this->hasMany(Benefit::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Akun penjaga yang di-assign untuk menjaga venue ini.
     */
    public function staff()
    {
        return $this->hasMany(User::class, 'venue_id')->where('role', 'penjaga');
    }
}
