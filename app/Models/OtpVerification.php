<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'customer_id',
        'otp',
        'expider_at',
        'is_used'
    ];
}
