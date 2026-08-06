<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Daftar customer yang pernah booking di venue milik owner yang login,
     * lengkap dengan ringkasan jumlah booking & total belanja.
     */
    public function index(Request $request)
    {
        $ownerId = auth()->id();

        $ownerBookings = function ($query) use ($ownerId) {
            $query->whereHas('field.venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            });
        };

        $query = Customer::query()
            ->whereHas('bookings', $ownerBookings)
            ->withCount(['bookings as bookings_count' => $ownerBookings])
            ->withSum(['bookings as total_spent' => function ($query) use ($ownerBookings) {
                $ownerBookings($query);
                $query->whereIn('status', ['paid', 'confirmed', 'completed']);
            }], 'total_price')
            ->withMax(['bookings as last_booking_date' => $ownerBookings], 'booking_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('last_booking_date')
            ->paginate(15)
            ->withQueryString();

        return view('owner.customers.index', compact('customers'));
    }

    /**
     * Detail seorang customer: profil singkat + riwayat booking-nya
     * khusus di venue milik owner yang login.
     */
    public function show(Request $request, Customer $customer)
    {
        $ownerId = auth()->id();

        $hasBookingWithOwner = $customer->bookings()
            ->whereHas('field.venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->exists();

        abort_unless($hasBookingWithOwner, 404);

        $bookings = $customer->bookings()
            ->with('field.venue')
            ->whereHas('field.venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'bookings_count' => $customer->bookings()
                ->whereHas('field.venue', fn ($q) => $q->where('owner_id', $ownerId))
                ->count(),
            'total_spent' => $customer->bookings()
                ->whereHas('field.venue', fn ($q) => $q->where('owner_id', $ownerId))
                ->whereIn('status', ['paid', 'confirmed', 'completed'])
                ->sum('total_price'),
        ];

        return view('owner.customers.show', compact('customer', 'bookings', 'summary'));
    }
}
