<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitRedemption extends Model
{
    protected $fillable = [
        'customer_id',
        'benefit_id',
        'point_transaction_id',
        'points_used',
        'status',
        'redeemed_at',
        'used_at',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function benefit()
    {
        return $this->belongsTo(Benefit::class);
    }

    public function pointTransaction()
    {
        return $this->belongsTo(PointTransaction::class);
    }
}
