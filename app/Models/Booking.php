<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'customer_id',
        'field_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration',
        'price_per_hour',
        'total_price',
        'status',
        'payment_method',
        'reservation_expires_at',
        'payment_due_at',
        'checked_in_at',
        'canceled_by',
        'cancel_reason',
        'canceled_at',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'reservation_expires_at' => 'datetime',
        'payment_due_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function field() {
        return $this->belongsTo(Field::class);
    }
}
