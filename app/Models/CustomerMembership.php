<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMembership extends Model
{
    protected $fillable = [
        'customer_id',
        'tier_id',
        'current_point',
        'qualification_booking_count',
        'qualification_spend',
        'cycle_start_at',
        'cycle_end_at',
    ];

    protected $casts = [
        'qualification_spend' => 'decimal:2',
        'cycle_start_at' => 'date',
        'cycle_end_at' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'tier_id');
    }
}
