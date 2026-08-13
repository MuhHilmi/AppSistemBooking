<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStreak extends Model
{
    protected $fillable = [
        'customer_id',
        'current_streak',
        'longest_streak',
        'last_booking_date',
        'streak_status',
    ];

    protected $casts = [
        'last_booking_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
