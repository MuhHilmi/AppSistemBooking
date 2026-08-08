<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTier extends Model
{
    protected $fillable = [
        'code',
        'name',
        'level',
        'min_booking',
        'min_spend',
        'evaluation_period_days',
        'booking_advance_days',
        'point_multiplier',
    ];

    protected $casts = [
        'min_spend' => 'decimal:2',
        'point_multiplier' => 'decimal:2',
    ];

    public function customerMemberships()
    {
        return $this->hasMany(CustomerMembership::class, 'tier_id');
    }

    public function tierBenefits()
    {
        return $this->hasMany(TierBenefit::class, 'tier_id');
    }

    public function benefits()
    {
        return $this->belongsToMany(Benefit::class, 'tier_benefits', 'tier_id', 'benefit_id')
            ->withPivot(['usage_limit', 'limit_period'])
            ->withTimestamps();
    }

    /**
     * Tier default untuk akun baru.
     */
    public static function defaultTier(): self
    {
        return static::orderBy('level')->firstOrFail();
    }
}
