<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\OperatingSchedule;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::with(['venue'])->where('status', 1)->get();

        return view('customer.bookings.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Field $field)
    {
        $field -> load(['venue', 'operatingSchedules']);

        return view('customer.bookings.create', compact('field'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'field_id' => 'required|exists:fields,id',
        ]);

        // Memeriksa jika slot masih tersedia
        $field = Field::findOrFail($request->field_id);
        $dayOfWeek = Carbon::parse($request->booking_date)->dayOfWeekIso;
        $schedule = $field->operatingSchedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule || !$schedule->is_open) {
            return redirect()->back()->with('error', 'Lapangan tidak buka pada tanggal yang dipilih.');
        }

        // Memeriksa perulangan fitur booking
        $existingBooking = Booking::where('field_id', $request->field_id)
            ->where('booking_date', $request->booking_date)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
                })
                ->whereIn('status', ['pending_payment', 'confirmed']);
            })
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'Slot waktu ini sudah tidak tersedia.');
        }

        // Menghitung durasi
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $duration = $start->diffInHours($end); // Durasi dalam jam

        // Buat booking
        $booking = Booking::create([
            'booking_code' => 'BK-' . now()->format('YmdHis') . '-' . rand(100,999),
            'field_id' => $request->field_id,
            'customer_id' => Auth::guard('customer')->id(),
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $duration,
            'price_per_hour' => $field->price_per_hour,
            'total_price' => $this->calculateTotalPrice($field, $request->start_time, $request->end_time),
            'status' => 'pending_payment',
        ]);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['field.venue']);
        return view('customer.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // 
    }

    public function availableSlots(Request $request, Field $field)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        $dayOfWeek = Carbon::parse($request->date)->dayOfWeekIso;
        $schedule = $field->operatingSchedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $bookings = Booking::where('field_id', $field->id)
            ->where('booking_date', $request->date)
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->get();

        if (!$schedule || !$schedule->is_open) {
            return response()->json(['slots' => []]);
        }

        $slots = [];

        $start = Carbon::parse($schedule->open_time);
        $end = Carbon::parse($schedule->close_time);
        $duration = $start->diffInHours($end);

        while ($start < $end) {
            $next = $start->copy()->addHour();

            if ($next > $end) {
                break;
            }

            $slotStart = $start->copy();
            $slotEnd = $next->copy();

            $available = true;

            foreach ($bookings as $booking) {
                $bookingStart = Carbon::parse($booking->start_time);
                $bookingEnd = Carbon::parse($booking->end_time);

                if (
                    $slotStart->lessThan($bookingEnd) &&
                    $slotEnd->greaterThan($bookingStart)
                ) {
                    $available = false;
                    break;
                }
            }

            $slots[] = [
                'start' => $start->format('H:i'),
                'end' => $next->format('H:i'),
                'available' => $available,
            ];

            $start = $next;
        }

        return response()->json(['slots' => $slots]);
    }

    /**
     * Calculate total price based on field price per hour and duration
     */
    private function calculateTotalPrice(Field $field, $startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $hours = $start->diffInHours($end);
        return $field->price_per_hour * $hours;
    }
}