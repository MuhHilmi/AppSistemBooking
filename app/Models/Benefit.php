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
        'redemption_limit',
        'redemption_limit_period',
        'description',
        'is_active',
    ];

    /**
     * Periode limit yang valid untuk redemption_limit_period.
     */
    public const LIMIT_PERIODS = ['day', 'week', 'month'];

    /**
     * Benefit yang bisa ditukar poin kapan saja (tidak melekat otomatis ke tier).
     */
    public function isRedeemable(): bool
    {
        return ! is_null($this->point_cost);
    }

    /**
     * Apakah benefit ini punya batas penukaran khusus (di luar batas harian global).
     */
    public function hasRedemptionLimit(): bool
    {
        return ! is_null($this->redemption_limit) && ! is_null($this->redemption_limit_period);
    }

    /**
     * Awal periode berjalan untuk batas penukaran benefit ini, relatif ke $now.
     * day = mulai hari ini, week = Senin minggu ini, month = tanggal 1 bulan ini.
     */
    public function redemptionPeriodStart(\Carbon\Carbon $now): \Carbon\Carbon
    {
        return match ($this->redemption_limit_period) {
            'week' => $now->copy()->startOfWeek(),
            'month' => $now->copy()->startOfMonth(),
            default => $now->copy()->startOfDay(),
        };
    }

    /**
     * Label periode dalam Bahasa Indonesia, untuk pesan error & tampilan UI.
     */
    public function redemptionPeriodLabel(): string
    {
        return match ($this->redemption_limit_period) {
            'week' => 'minggu ini',
            'month' => 'bulan ini',
            default => 'hari ini',
        };
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
