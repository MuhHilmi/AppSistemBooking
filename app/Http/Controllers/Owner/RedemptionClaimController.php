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

        $claims = BenefitRedemption::with(['customer', 'benefit'])
            ->whereHas('benefit', function ($q) use ($venueIds) {
                $q->whereIn('venue_id', $venueIds);
            })
            ->orderByDesc('redeemed_at')
            ->paginate(20);

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
