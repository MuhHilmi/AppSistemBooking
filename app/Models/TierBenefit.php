<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TierBenefit extends Model
{
    protected $fillable = [
        'tier_id',
        'benefit_id',
        'usage_limit',
        'limit_period',
    ];

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'tier_id');
    }

    public function benefit()
    {
        return $this->belongsTo(Benefit::class, 'benefit_id');
    }
}
