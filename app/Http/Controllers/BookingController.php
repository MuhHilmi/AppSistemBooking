<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Field;
use App\Models\OperatingSchedule;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $fields = Field::with(['venue'])->where('status', 1)->get();

        return view('customer.bookings.index', compact('fields'));
    }

    public function dashboardView() {
        $customerId = auth('customer')->id();

        $upcomingBooking = Booking::with('field.venue')
            ->where('customer_id', $customerId)
            ->whereIn('status', ['waiting_payment_method', 'pending_payment', 'paid', 'confirmed'])
            ->where(function ($query) {
                $query->where('booking_date', '>', today())->orWhere(function ($query) {
                    $query->where('booking_date', today())->where('end_time', '>=', now()->format('H:i:s'));
                });
            })
            ->orderByRaw("CASE status
                            WHEN 'pending_payment' THEN 1
                            WHEN 'waiting_payment_method' THEN 2
                            WHEN 'paid' THEN 3
                            WHEN 'confirmed' THEN 4
                            ELSE 5
                        END")
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->first();

        $frequentFields = Booking::with('field')
            ->where('customer_id', $customerId)
            ->select('field_id', DB::raw('COUNT(*) as total'))
            ->groupBy('field_id')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->pluck('field')
            ->filter();

        // dd(Booking::where('customer_id', $customerId)->pluck('status')->unique());

        $recentBookings = Booking::with('field')
            ->where('customer_id', $customerId)
            ->whereIn('status', ['waiting_payment_method', 'payment_method', 'paid', 'completed', 'confirmed', 'canceled'])
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->take(5)
            ->get();

        // dd($customerId, $recentBookings->toArray());

        return view('customer.dashboard', compact(
            'upcomingBooking',
            'frequentFields',
            'recentBookings'
        ));
    }

    public function dashboardOwnerView() {
        $ownerId = auth()->id();

        $activeStatuses = ['waitin_payment_method', 'pending_payment', 'paid', 'confirmed'];

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $ownerBookings = Booking::whereHas('field.venue', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        });

        $todayCount = (clone $ownerBookings)
            ->whereDate('booking_date', today())
            ->count();

        $todayRevenue = (clone $ownerBookings)
            ->whereDate('booking_date', today())
            ->whereIn('status', ['paid', 'confirmed', 'completed'])
            ->sum('total_price');

        $weekRevenue = (clone $ownerBookings)
            ->whereDate('booking_date', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['paid', 'confirmed', 'completed'])
            ->sum('total_price');

        $ownerFields = Field::whereHas('venue', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->with('operatingSchedules')->where('status', 1)->get();

        $dayOfWeek = now()->dayOfWeekIso;
        $totalOperatingHours = 0;
        foreach ($ownerFields as $field) {
            $schedule = $field->operatingSchedules->firstWhere('day_of_week', $dayOfWeek);
            if ($schedule && $schedule->is_open) {
                $totalOperatingHours += Carbon::parse($schedule->open_time)->diffInHours(Carbon::parse($schedule->close_time));
            }
        }

        $bookedHoursToday = (clone $ownerBookings)->whereDate('booking_date', today())->whereIn('status', $activeStatuses)->sum('duration');
        $occupancyRate = $totalOperatingHours > 0 ? round(($bookedHoursToday / $totalOperatingHours) * 100) : 0;

        $activeFieldsCount = $ownerFields->count();

        $todaySchedule = $ownerFields->map(function ($field) use ($activeStatuses) {
            return [
                'field' => $field->name,
                'sport_type' => $field->sport_type,
                'bookings' => Booking::where('field_id', $field->id)->whereDate('booking_date', today())->whereIn('status', $activeStatuses)->orderBy('start_time')->get(['start_time', 'end_time', 'status']),
            ];
        });

        $needsAttention = (clone $ownerBookings)
            ->with(['customer', 'field'])
            ->whereIn('status', ['pending_payment', 'paid'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(10)
            ->get();

        $revenueBySport = (clone $ownerBookings)
            ->join('fields', 'bookings.field_id', '=', 'fields.id')
            ->whereBetween('booking_date', [$startOfWeek, $endOfWeek])
            ->whereIn('bookings.status', ['paid', 'confirmed', 'completed'])
            ->selectRaw('fields.sport_type, SUM(bookings.total_price) as total')
            ->groupBy('fields.sport_type')
            ->orderByDesc('total')
            ->get();

        return view('owner.dashboard', compact(
            'todayCount',
            'todayRevenue',
            'weekRevenue',
            'occupancyRate',
            'activeFieldsCount',
            'todaySchedule',
            'needsAttention',
            'revenueBySport'
        ));
    }

    public function historyCustomer(Request $request) {
        $customerId = auth('customer')->id();
        $filter = $request->query('status', 'all');

        $statusGroup = [
            'pending_payment' => ['waiting_payment_method', 'pending_payment'],
            'active' => ['paid', 'confirmed'],
            'completed' => ['completed'],
            'canceled' => ['canceled'],
        ];

        $query = Booking::with('field.venue')
            ->where('customer_id', $customerId)
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time');

        if ($filter !== 'all' && isset($statusGroup[$filter])) {
            $query->whereIn('status', $statusGroup[$filter]);
        }

        $bookings = $query->paginate(10)->withQueryString();

        $groupedBookings = $bookings->getCollection()->groupBy(function ($booking) {
            $date = Carbon::parse($booking->booking_date);

            if ($date->isToday()) {
                return 'Hari ini';
            }

            if ($date->between(now()->startOfWeek(), now()->endOfWeek())) {
                return 'Minggu ini';
            }

            return 'Lebih awal';
        });

        return view('customer.bookings.history', [
            'groupedBookings' => $groupedBookings,
            'bookings' => $bookings,
            'activeFilter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Field $field) {
        $field -> load(['venue', 'operatingSchedules']);

        return view('customer.bookings.create', compact('field'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'field_id' => 'required|exists:fields,id',
        ]);

        return DB::transaction(function () use ($request) {
            // Memeriksa jika slot masih tersedia
            $field = Field::where('id', $request->field_id)->lockForUpdate()->firstOrFail();
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
                    ->whereIn('status', ['waiting_payment_method', 'pending_payment', 'paid', 'confirmed']);
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
            // $last = Booking::latest() -> first();
            // $number = $last ? $last -> id + 1 : 1;

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
                'reservation_expires_at' => now()->addMinutes(3),
                'notes' => $request->notes,
            ]);

            // Jika ingin diarahkan ke halaman detail booking
            return redirect()->route('customer.bookings.show', $booking)->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');

            // JIka ingin diarahkan langsung ke halaman pembayaran
            // return redirect()->route('customer.bookings.payment', $booking);
        });
    }

    public function updatePaymentMethod() {
        // $this->authorize('update', $booking);

        $request->validate(['payment_method' => 'require|in:cash,transfer, qris']);

        if ($booking->status !== 'waiting_payment_method') {
            return redirect()->route('customer.bookings.show', $booking);
        }

        DB::transaction(function () use ($booking, $request) {
            switch ($request->payment_method) {
                case 'cash':
                    $booking->update([
                        'payment_method' => 'cash',
                        'status' => 'confirmed',
                        'payment_due_at' => null,
                    ]);
                    break;
                case 'transfer':
                    $booking->update([
                        'payment_method' => 'transfer',
                        'status' => 'pending_payment',
                        'payment_due_at' => now()->addMinutes(30),
                    ]);
                    break;
                case 'qris':
                    $booking->update([
                        'payment_method' => 'qris',
                        'status' => 'pending_payment',
                        'payment_due_at' => now()->addMinutes(30),
                    ]);
                    break;
            }
        });

        if ($booking->payment_method === 'cash') {
            return redirect()->route('customer.bookings.show', $booking)->with('success', 'Booking berhasil dikonfirmasi.');
        }

        return redirect()->route('customer.bookings.payment.pending', $booking);
    }

    /**
     * Menampilkan Slot yang sudah dibooking oleh customer
     */
    public function show(Booking $booking) {
        // Digunakan ketika pengecekkan
        // dd(auth()->check(), auth()->user(), auth()->id());
        // dd([ 'default_guard' => auth()->user(), 'customer_guard' => auth('customer')->user(), ]);

        // Digunakan ketika menggunakan policy
        $this->authorize('view', $booking);

        // Digunakan ketika belum menggunakan policy
        // if ($booking->customer_id !== auth('customer')->id()) {
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
            ->whereIn('status', ['waiting_payment_method', 'pending_payment', 'paid', 'confirmed'])
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

        if (!in_array($booking->status, ['waiting_payment_method', 'pending_payment'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan!');
        }

        $booking->update([
            'status' => 'canceled',
            'canceled_by' => 'customer',
            'cancel_reason' => 'customer_request',
            'canceled_at' => now()
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan');
    }

    /**
     * Metode cancel untuk owner
     */
    public function cancelOwner(Booking $booking) {
        $this->authorize('ownerCancel', $booking);

        if (!in_array($booking->status, ['waiting_payment_method', 'pending_payment'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan');
        }

        $booking->update([
            'status' => 'canceled',
            'canceled_by' => 'owner',
            'cancel_reason' => 'owner_request',
            'canceled_at' => now(),
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan');
    }

    /**
     * Metode cancel berdasarkan durasi waktu
     */
    // public function cancelDuration() {
    //     Booking::where('status', 'pending_payment')
    //     ->where('created_at', '<', now()->subMinutes(30))
    //     ->update([
    //         'status' => 'canceled',
    //         'canceled_by' => 'system',
    //         'cancel_reason' => 'payment_timeout',
    //         'canceled_at' => now(),
    //     ]);

    //     Schedule::command('booking:expire')->everyMinute();
    // }
}