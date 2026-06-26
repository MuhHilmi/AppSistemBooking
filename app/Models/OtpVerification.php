<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'customers_id',
        'otp',
        'expired_at',
        'is_used'
    ];

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'customers_id'
        );
    }
}
