<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TierHistory extends Model
{
    protected $fillable = [
        'customer_id',
        'from_tier_id',
        'to_tier_id',
        'reason',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function fromTier()
    {
        return $this->belongsTo(MembershipTier::class, 'from_tier_id');
    }

    public function toTier()
    {
        return $this->belongsTo(MembershipTier::class, 'to_tier_id');
    }
}
