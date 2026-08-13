<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    /**
     * Status booking yang dihitung sebagai pendapatan riil (uang sudah diterima).
     */
    private const REVENUE_STATUSES = ['paid', 'confirmed', 'completed'];

    public function index(Request $request)
    {
        $range = $this->resolveRange($request);
        $venueIds = auth()->user()->accessibleVenueIds();

        $baseQuery = fn () => Booking::whereHas('field.venue', function ($q) use ($venueIds) {
            $q->whereIn('id', $venueIds);
        })
            ->whereIn('status', self::REVENUE_STATUSES)
            // ->whereBetween('booking_date', [$range['from']->toDateString(), $range['to']->toDateString()]);
            // Dikasrenakan DB Sqlite sulit membaca data, maka sementara dilakukan seperti dibawah ini
            ->whereDate('booking_date', '>=', $range['from']->toDateString())
            ->whereDate('booking_date', '<=', $range['to']->toDateString());

        $totalRevenue = (clone $baseQuery())->sum('total_price');
        $totalBookings = (clone $baseQuery())->count();
        $averagePerBooking = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

        $paymentBreakdown = (clone $baseQuery())
            ->selectRaw('payment_method, SUM(total_price) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $dailyRows = (clone $baseQuery())
            ->selectRaw('booking_date, SUM(total_price) as total')
            ->groupBy('booking_date')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->booking_date)->toDateString());

        $chart = $this->buildChartSeries($range['from'], $range['to'], $dailyRows);

        return view('owner.revenue.index', [
            'range' => $range,
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'averagePerBooking' => $averagePerBooking,
            'paymentBreakdown' => $paymentBreakdown,
            'chartLabels' => $chart['labels'],
            'chartTotals' => $chart['totals'],
        ]);
    }

    /**
     * Download rincian pendapatan (per transaksi) sesuai filter yang aktif, format CSV.
     */
    public function export(Request $request)
    {
        $range = $this->resolveRange($request);
        $venueIds = auth()->user()->accessibleVenueIds();

        $bookings = Booking::with(['customer', 'field.venue'])
            ->whereHas('field.venue', function ($q) use ($venueIds) {
                $q->whereIn('id', $venueIds);
            })
            ->whereIn('status', self::REVENUE_STATUSES)
            // ->whereBetween('booking_date', [$range['from']->toDateString(), $range['to']->toDateString()])
            // Sama seperti bagian atas
            ->whereDate('booking_date', '>=', $range['from']->toDateString())
            ->whereDate('booking_date', '<=', $range['to']->toDateString())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        $filename = 'pendapatan_' . $range['from']->format('Ymd') . '_' . $range['to']->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            // BOM supaya karakter dibaca benar saat dibuka di Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Tanggal', 'Kode Booking', 'Customer', 'Lapangan', 'Venue', 'Metode Bayar', 'Status', 'Total (Rp)']);

            foreach ($bookings as $booking) {
                fputcsv($handle, [
                    Carbon::parse($booking->booking_date)->format('d-m-Y'),
                    $booking->booking_code,
                    $booking->customer->name ?? '-',
                    $booking->field->name ?? '-',
                    $booking->field->venue->name ?? '-',
                    ucfirst($booking->payment_method ?? '-'),
                    ucfirst(str_replace('_', ' ', $booking->status)),
                    $booking->total_price,
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['', '', '', '', '', '', 'Total Pendapatan', $bookings->sum('total_price')]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Tentukan rentang tanggal aktif berdasarkan filter (week/month/year/custom).
     */
    private function resolveRange(Request $request): array
    {
        $type = in_array($request->get('range'), ['week', 'month', 'year', 'custom'])
            ? $request->get('range')
            : 'week';

        $now = Carbon::now();

        if ($type === 'custom') {
            $from = $request->filled('date_from')
                ? Carbon::parse($request->date_from)->startOfDay()
                : $now->copy()->subWeek()->startOfDay();

            $to = $request->filled('date_to')
                ? Carbon::parse($request->date_to)->endOfDay()
                : $now->copy()->endOfDay();

            // Jaga-jaga kalau user memasukkan tanggal terbalik.
            if ($from->gt($to)) {
                [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            }

            return ['type' => 'custom', 'from' => $from, 'to' => $to];
        }

        $from = match ($type) {
            'month' => $now->copy()->subMonth()->startOfDay(),
            'year' => $now->copy()->subYear()->startOfDay(),
            default => $now->copy()->subWeek()->startOfDay(),
        };

        return ['type' => $type, 'from' => $from, 'to' => $now->copy()->endOfDay()];
    }

    /**
     * Susun data grafik. Rentang pendek (<= ~2 bulan) dikelompokkan per hari,
     * rentang panjang (mis. 1 tahun) dikelompokkan per bulan supaya grafik tetap terbaca.
     */
    private function buildChartSeries(Carbon $from, Carbon $to, $dailyRows): array
    {
        $labels = [];
        $totals = [];

        if ($from->diffInDays($to) > 62) {
            $period = CarbonPeriod::create($from->copy()->startOfMonth(), '1 month', $to->copy()->startOfMonth());

            $monthlyTotals = [];
            foreach ($dailyRows as $date => $row) {
                $key = Carbon::parse($date)->format('Y-m');
                $monthlyTotals[$key] = ($monthlyTotals[$key] ?? 0) + (float) $row->total;
            }

            foreach ($period as $month) {
                $key = $month->format('Y-m');
                $labels[] = $month->translatedFormat('M Y');
                $totals[] = $monthlyTotals[$key] ?? 0;
            }
        } else {
            $period = CarbonPeriod::create($from->copy()->startOfDay(), '1 day', $to->copy()->startOfDay());

            foreach ($period as $day) {
                $key = $day->toDateString();
                $labels[] = $day->translatedFormat('d M');
                $totals[] = isset($dailyRows[$key]) ? (float) $dailyRows[$key]->total : 0;
            }
        }

        return ['labels' => $labels, 'totals' => $totals];
    }
}
