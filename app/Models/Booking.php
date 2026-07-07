<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'customer_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration',
        'price_per_hour',
        'total_price',
        'status',
        'notes',
        'field_id',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function field() {
        return $this->belongsTo(Field::class);
    }
}
