<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\MembershipTier;
use App\Models\PointTransaction;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Benefit yang bisa ditukar poin (berlaku untuk semua tier)
        $redeemableBenefits = Benefit::whereNotNull('point_cost')
            ->where('is_active', true)
            ->orderBy('point_cost')
            ->get();

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
            'pointHistory' => $pointHistory,
            'redemptionHistory' => $redemptionHistory,
        ]);
    }

    public function redeem(Request $request, Benefit $benefit)
    {
        $customer = Auth::guard('customer')->user();

        try {
            $this->membershipService->redeemBenefit($customer, $benefit);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Berhasil menukar poin dengan \"{$benefit->name}\". Tunjukkan halaman ini ke petugas venue untuk klaim.");
    }
}
