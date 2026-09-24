<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\MembershipTier;
use App\Models\PointTransaction;
use App\Models\Venue;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MembershipController extends Controller
{
    public function __construct(protected MembershipService $membershipService)
    {
    }

    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $membership = $customer->membership()->with('tier')->firstOrFail();
        $streak = $customer->streak;

        $tiers = MembershipTier::with('benefits')->orderBy('level')->get();

        // Benefit pasif yang otomatis melekat di tier customer saat ini
        $tierBenefits = $membership->tier->benefits;

        // Benefit yang bisa ditukar poin: dari venue manapun (platform-wide + katalog tiap venue)
        $redeemableBenefits = Benefit::whereNotNull('point_cost')
            ->where('is_active', true)
            ->with('venue')
            ->orderBy('point_cost')
            ->get();

        // Dipakai buat dropdown pilih venue saat redeem benefit platform-wide
        // (benefit venue-specific tidak perlu pilih, otomatis ikut venue-nya).
        $activeVenues = Venue::where('status', true)->orderBy('name')->get();

        $dailyQuotaRemaining = $this->membershipService->remainingDailyQuota($customer);

        // key => sisa kuota (null = benefit ini tidak punya batas khusus)
        $benefitQuotaRemaining = $redeemableBenefits->mapWithKeys(
            fn (Benefit $benefit) => [$benefit->id => $this->membershipService->remainingBenefitQuota($customer, $benefit)]
        );

        $pointHistory = PointTransaction::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $redemptionHistory = $customer->benefitRedemptions()
            ->with('benefit')
            ->orderByDesc('redeemed_at')
            ->limit(10)
            ->get();

        return view('customer.membership.index', [
            'membership' => $membership,
            'streak' => $streak,
            'tiers' => $tiers,
            'tierBenefits' => $tierBenefits,
            'redeemableBenefits' => $redeemableBenefits,
            'activeVenues' => $activeVenues,
            'dailyQuotaRemaining' => $dailyQuotaRemaining,
            'benefitQuotaRemaining' => $benefitQuotaRemaining,
            'pointHistory' => $pointHistory,
            'redemptionHistory' => $redemptionHistory,
        ]);
    }

    public function redeem(Request $request, Benefit $benefit)
    {
        $customer = Auth::guard('customer')->user();

        // venue_id cuma wajib & divalidasi untuk benefit platform-wide;
        // untuk benefit venue-specific, kolom ini tidak ditampilkan di form sama sekali.
        $validated = $request->validate([
            'venue_id' => [
                $benefit->isPlatformWide() ? 'required' : 'nullable',
                Rule::exists('venues', 'id')->where('status', true),
            ],
        ]);

        try {
            $this->membershipService->redeemBenefit($customer, $benefit, $validated['venue_id'] ?? null);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Berhasil menukar poin dengan \"{$benefit->name}\". Tunjukkan halaman ini ke petugas venue untuk klaim.");
    }
}
