<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function show(Booking $booking)
    {
        $this->authorizeReceiptAccess($booking);

        $booking->load(['customer', 'field.venue']);
        $siteSettings = SiteSetting::current();

        return view('customer.bookings.receipt', compact('booking', 'siteSettings'));
    }

    public function download(Booking $booking)
    {
        $this->authorizeReceiptAccess($booking);

        $booking->load(['customer', 'field.venue']);
        $siteSettings = SiteSetting::current();

        $pdf = Pdf::loadView('customer.bookings.receipt', compact('booking', 'siteSettings'))->setPaper('a4', 'potrait');

        return $pdf->download("struk-{$booking->booking_code}.pdf");
    }

    private function authorizeReceiptAccess(Booking $booking): void
    {
        abort_unless($booking->customer_id === auth('customer')->id(), 403);

        abort_unless(
            in_array($booking->status, ['confirmed', 'completed']), 404, 'Struk belum tersedia karena booking belum dikonfirmasi.'
        );
    }
}
