<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BenefitRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedemptionClaimController extends Controller
{
    public function index(Request $request)
    {
        $venueIds = Auth::user()->accessibleVenueIds();

        $query = BenefitRedemption::with(['customer', 'benefit'])
            ->whereHas('benefit', function ($q) use ($venueIds) {
                $q->whereIn('venue_id', $venueIds);
            });

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
        $venueId = $claim->benefit->venue_id;

        abort_unless($venueId && Auth::user()->canManageVenue($venueId), 403);
        abort_if($claim->status !== 'pending', 400, 'Penukaran ini sudah diproses sebelumnya.');

        $claim->update([
            'status' => 'used',
            'used_at' => now(),
        ]);

        return back()->with('success', 'Penukaran berhasil ditandai sudah diklaim customer.');
    }
}
