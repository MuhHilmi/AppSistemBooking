<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Field;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    /**
     * Riwayat booking seluruh customer pada venue milik owner yang login.
     */
    public function index(Request $request)
    {
        $ownerId = auth()->id();

        $query = Booking::with(['customer', 'field.venue'])
            ->whereHas('field.venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        $bookings = $query->orderByDesc('id')
            ->orderByDesc('start_time')
            ->paginate(15)
            ->withQueryString();

        $fields = Field::whereHas('venue', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->orderBy('name')->get();

        return view('owner.bookings.index', [
            'bookings' => $bookings,
            'fields' => $fields,
            'filters' => $request->only(['search', 'status', 'field_id', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Form buat booking manual (walk-in) oleh owner. Metode bayar selalu cash.
     */
    public function create(Request $request)
    {
        $ownerId = auth()->id();

        $fields = Field::with('venue')
            ->whereHas('venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $selectedFieldId = $request->integer('field_id') ?: null;

        return view('owner.bookings.create', [
            'fields' => $fields,
            'selectedFieldId' => $selectedFieldId,
        ]);
    }

    /**
     * Cari customer terdaftar berdasarkan nomor HP/nama (dipakai form create via AJAX).
     */
    public function searchCustomers(Request $request)
    {
        $request->validate(['q' => 'required|min:2']);

        $customers = Customer::where('phone', 'like', "%{$request->q}%")
            ->orWhere('name', 'like', "%{$request->q}%")
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name', 'phone']);

        return response()->json($customers);
    }

    /**
     * Simpan booking walk-in. Status langsung confirmed karena
     * pembayaran cash diterima langsung di tempat oleh owner.
     */
    public function store(Request $request)
    {
        $ownerId = auth()->id();

        $request->validate([
            'field_id' => [
                'required',
                Rule::exists('fields', 'id')->where(function ($query) use ($ownerId) {
                    $query->whereIn('venue_id', Venue::where('owner_id', $ownerId)->pluck('id'));
                }),
            ],
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|required_without:customer_id|string|max:255',
            'customer_phone' => 'nullable|required_without:customer_id|string|max:20',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $field = Field::where('id', $request->field_id)->lockForUpdate()->firstOrFail();

            $existingBooking = Booking::where('field_id', $field->id)
                ->where('booking_date', $request->booking_date)
                ->where(function ($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                })
                ->whereIn('status', ['waiting_payment_method', 'pending_payment', 'paid', 'confirmed'])
                ->first();

            if ($existingBooking) {
                return back()->withInput()->with('error', 'Slot waktu ini sudah tidak tersedia.');
            }

            if ($request->filled('customer_id')) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                // Pelanggan walk-in yang belum terdaftar: buat akun ringan otomatis.
                // Email diisi placeholder karena kolom email wajib (unique, not null),
                // walk-in biasanya tidak memberikan email saat booking di tempat.
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->customer_phone],
                    [
                        'name' => $request->customer_name,
                        'email' => 'walkin+'.preg_replace('/\D/', '', $request->customer_phone).'@placeholder.local',
                        'password' => Hash::make(Str::random(24)),
                        'is_verified' => true,
                    ]
                );
            }

            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);
            $duration = $start->diffInHours($end);

            $code = Booking::generateBookingCode();

            $booking = Booking::create([
                'booking_code' => $code,
                'field_id' => $field->id,
                'customer_id' => $customer->id,
                'booking_date' => $request->booking_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration' => $duration,
                'price_per_hour' => $field->price_per_hour,
                'total_price' => $field->price_per_hour * $duration,
                'status' => 'confirmed',
                'payment_method' => 'cash',
                'checked_in_at' => now(),
                'notes' => $request->notes,
            ]);

            return redirect()->route('owner.bookings.index')
                ->with('success', "Booking {$booking->booking_code} berhasil dibuat untuk {$customer->name}.");
        });
    }

    /**
     * Konfirmasi bahwa customer sudah membayar cash di tempat, untuk booking
     * yang dibuat sendiri oleh customer secara online dengan metode cash.
     */
    public function confirmCashPayment(Booking $booking)
    {
        abort_unless($booking->field->venue->owner_id === auth()->id(), 403);

        if ($booking->payment_method !== 'cash' || $booking->status !== 'pending_payment') {
            return back()->with('error', 'Booking ini tidak sedang menunggu konfirmasi pembayaran cash.');
        }

        $booking->update([
            'status' => 'confirmed',
            'checked_in_at' => now(),
        ]);

        return back()->with('success', "Pembayaran cash untuk booking {$booking->booking_code} berhasil dikonfirmasi.");
    }

    public function confirmTransferPayment(Booking $booking)
    {
        abort_unless($booking->field->venue->owner_id === auth()->id(), 403);

        if ($booking->status !== 'paid') {
            return back()->with('error', 'Booking ini tidak sedang menunggu konfirmasi (belum berstatus paid).');
        }

        $booking->update([
            'status' => 'confirmed',
            'checked_in_at' => now(),
        ]);

        return back()->with('success', "Booking {$booking->booking_code} berhasil dikonfirmasi.");
    }
}
