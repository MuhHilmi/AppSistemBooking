<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'remaining_amount',
        'reference_type',
        'reference_id',
        'note',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function benefitRedemption()
    {
        return $this->hasOne(BenefitRedemption::class);
    }
}
