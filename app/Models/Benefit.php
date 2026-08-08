<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'value_type',
        'value',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tierBenefits()
    {
        return $this->hasMany(TierBenefit::class, 'benefit_id');
    }

    public function tiers()
    {
        return $this->belongsToMany(MembershipTier::class, 'tier_benefits', 'benefit_id', 'tier_id')
            ->withPivot(['usage_limit', 'limit_period'])
            ->withTimestamps();
    }

    public function redemptions()
    {
        return $this->hasMany(BenefitRedemption::class);
    }
}
