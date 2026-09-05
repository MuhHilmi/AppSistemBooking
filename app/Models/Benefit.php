<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = [
        'venue_id',
        'code',
        'name',
        'type',
        'value_type',
        'value',
        'point_cost',
        'description',
        'is_active',
    ];

    /**
     * Benefit yang bisa ditukar poin kapan saja (tidak melekat otomatis ke tier).
     */
    public function isRedeemable(): bool
    {
        return ! is_null($this->point_cost);
    }

    /**
     * Benefit platform-wide (dikelola admin) vs katalog milik venue tertentu (dikelola owner).
     */
    public function isPlatformWide(): bool
    {
        return is_null($this->venue_id);
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

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
