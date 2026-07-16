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

    public function dashboardView() {
        $customer = Auth::guard('customer') -> user();
        $activeBookings = Booking::where('customer_id', $customer -> id) -> whereIn('status', ['waiting_payment_method', 'pending_payment', 'confirmed']) -> count();
        $pendingBookings = Booking::where('customer_id', $customer -> id) -> where('status', ['waiting_payment_method', 'pending_payment']) -> count();
        $confirmedBookings = Booking::where('customer_id', $customer -> id) -> where('status', 'confirmed') -> count();
        $latestBookings = Booking::with(['field.venue']) -> where('customer_id', $customer -> id) -> latest() -> take(5) -> get();

        return view('customer.dashboard', compact('activeBookings', 'pendingBookings', 'confirmedBookings', 'latestBookings'));
    }

    public function dashboardOwnerView() {
        $today = Booking::whereHas('field.venue', function ($query) { $query->where('owner_id', auth()->id()); }) -> whereDate('booking_date', today()) -> count();
        $tomorrow = Booking::whereHas('field.venue', function ($query) { $query->where('owner_id', auth()->id()); }) -> whereDate('booking_date', \Carbon\Carbon::tomorrow()) -> count();
        $pending = Booking::whereHas('field.venue', function ($query) { $query->where('owner_id', auth()->id()); }) -> where('status', 'pending_payment') -> count();
        $confirmed = Booking::whereHas('field.venue', function ($query) { $query->where('owner_id', auth()->id()); }) -> where('status', 'confirmed') -> count();

        return view('owner.dashboard', compact('today', 'tomorrow', 'pending', 'confirmed'));
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

        // JIka ingin list booking berurutan
        $last = Booking::latest() -> first();
        $number = $last ? $last -> id + 1 : 1;

        $code = 'BK-' . now()->format('YmdHis') . '-' . rand(100,999);
        // $code = 'BK-' . now()->format('YmdHis') . '-' . $number; // Gunakan ini jika ingin booking list berurutan

        // Buat booking
        $booking = Booking::create([
            'booking_code' => $code,
            'field_id' => $request->field_id,
            'customer_id' => Auth::guard('customer')->id(),
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $duration,
            'price_per_hour' => $field->price_per_hour,
            'total_price' => $this->calculateTotalPrice($field, $request->start_time, $request->end_time),
            'status' => 'waiting_payment_method',
            'reservation_expires_at' => now()->addMinutes(5),
            'notes' => $request->notes,
        ]);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Menampilkan Slot yang sudah dibooking oleh customer
     */
    public function show(Booking $booking)
    {
        // Digunakan ketika menggunakan policy
        $this->authorize('view', $booking);

        // Digunakan ketika belum menggunakan policy
        // if ($booking->customer_id != auth()->id()) {
        //     abort(403);
        // }

        $booking->load([
            'customer',
            'field',
            'field.venue',
        ]);

        return view(
            'customer.bookings.show',
            compact('booking')
        );
    }

    /**
     * Menampilkan Slot yang Tersedia
     */
    public function availableSlots(Request $request, Field $field)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        $selectedDate = Carbon::parse($request->date);
        $now = now();

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

            // Slot yang sudah lewat tidak dapat dipilih
            if ($selectedDate->isToday() && $slotStart->lte($now)) {
                $available = false;
            }

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

    /**
     * Metode cancel untuk customer
     */
    public function cancelCustomer(Booking $booking) {
        $this->authorize('cancel', $booking);

        if ($booking->status != 'pending_payment') {
            return back()->with('error', 'Booking tidak dapat dibatalkan!');
        }

        $booking->update([
            'status' => 'canceled',
            'canceled_by' => 'customer',
            'canceled_reason' => 'customer_canceled',
            'canceled_at' => now()
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan');
    }

    /**
     * Metode cancel untuk owner
     */
    public function cancelOwner() {
        $this->authorize('cancel', $booking);

        if ($booking->status != 'pending_payment') {
            return back()->with('error', 'Booking tidak dapat dibatalkan');
        }

        $booking->update([
            'status' => 'canceled',
            'canceled_by' => 'owner',
            'cancel_reason' => 'owner_canceled',
            'canceled_at' => now(),
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan');
    }

    /**
     * Metode cancel berdasarkan durasi waktu
     */
    public function cancelDuration() {
        Booking::where('status', 'pending_payment')
        ->where('created_at', '<', now()->subMinutes(30))
        ->update([
            'status' => 'canceled',
            'canceled_by' => 'system',
            'cancel_reason' => 'payment_timeout',
            'canceled_at' => now(),
        ]);

        Schedule::command('booking:expire')->everyMinute();
    }
}