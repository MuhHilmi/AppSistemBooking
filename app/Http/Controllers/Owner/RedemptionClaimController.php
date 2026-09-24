<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BenefitRedemption;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedemptionClaimController extends Controller
{
    public function __construct(protected MembershipService $membershipService)
    {
    }

    public function index(Request $request)
    {
        $venueIds = Auth::user()->accessibleVenueIds();

        // Filter dari venue_id milik redemption itu sendiri (venue tujuan klaim
        // yang dipilih customer saat redeem), BUKAN dari venue_id benefit —
        // benefit platform-wide punya venue_id null dan tidak akan pernah cocok
        // kalau difilter lewat relasi benefit.
        $query = BenefitRedemption::with(['customer', 'benefit', 'venue'])
            ->whereIn('venue_id', $venueIds);

        $allowedSorts = [
            'customer',
            'benefit',
            'points_used',
            'redeemed_at',
            'status',
        ];

        $sort = $request->get('sort', 'redeemed_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'redeemed_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        switch ($sort) {
            case 'customer':
                $query->join('customers', 'benefit_redemptions.customer_id', '=', 'customers.id')->select('benefit_redemptions.*')->orderBy('customers.name', $direction);
                break;

            case 'benefit':
                $query->join('benefits', 'benefit_redemptions.benefit_id', '=', 'benefits.id')->select('benefit_redemptions.*')->orderBy('benefits.name', $direction);
                break;

            default:
                $query->orderBy($sort, $direction);
                break;
        }

        $claims = $query
            ->paginate(20)
            ->withQueryString();

        return view('owner.redemption-claims.index', ['claims' => $claims]);
    }

    public function markUsed(BenefitRedemption $claim)
    {
        // Pakai venue_id redemption (venue tujuan klaim yang dipilih customer),
        // bukan venue_id benefit — supaya benefit platform-wide tetap bisa
        // diverifikasi oleh venue yang dipilih customer saat redeem.
        abort_unless($claim->venue_id && Auth::user()->canManageVenue($claim->venue_id), 403);
        abort_if($claim->status !== 'pending', 400, 'Penukaran ini sudah diproses sebelumnya.');

        $claim->update([
            'status' => 'used',
            'used_at' => now(),
        ]);

        return back()->with('success', 'Penukaran berhasil ditandai sudah diklaim customer.');
    }

    /**
     * Batalkan klaim yang masih pending (misalnya stok benefit habis di venue).
     * Poin dikembalikan penuh ke customer dan kuota penukaran hariannya juga
     * dikembalikan (redemption berstatus 'canceled' tidak dihitung ke batas manapun).
     */
    public function cancel(BenefitRedemption $claim)
    {
        abort_unless($claim->venue_id && Auth::user()->canManageVenue($claim->venue_id), 403);

        try {
            $this->membershipService->cancelRedemption($claim);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Penukaran dibatalkan. Poin dan kuota customer sudah dikembalikan.');
    }
}
