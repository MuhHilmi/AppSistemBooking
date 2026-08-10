<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $booking->booking_code }}</title>
    @vite(['resources/css/app.css', 'resources/js/receipt.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .receipt-paper { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10">

    {{-- Tombol aksi (tidak ikut ter-capture) --}}
    <div class="no-print max-w-md mx-auto mb-4 flex flex-wrap justify-between items-center gap-3 px-1">
        <a href="{{ route('customer.bookings.show', $booking) }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali ke Detail Booking</a>
        <div class="flex gap-2">
            <button type="button" id="download-png-btn"
                onclick="downloadReceiptAsPng('receipt-paper', 'struk-{{ $booking->booking_code }}')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50">
                Download PNG
            </button>
            <button type="button" id="download-pdf-btn"
                onclick="downloadReceiptAsPdf('receipt-paper', 'struk-{{ $booking->booking_code }}')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                Download PDF
            </button>
        </div>
    </div>

    {{-- Kertas Struk --}}
    <div id="receipt-paper" class="receipt-paper max-w-md mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-8">

        {{-- Header --}}
        <div class="text-center mb-6">
            @if ($siteSettings->logo_url)
                <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}" class="h-14 w-14 rounded-xl object-cover mx-auto mb-3">
            @else
                <div class="h-14 w-14 rounded-xl bg-green-600 text-white flex items-center justify-center font-bold text-2xl mx-auto mb-3">
                    {{ strtoupper(substr($siteSettings->site_name, 0, 1)) }}
                </div>
            @endif

            <h1 class="text-lg font-bold text-gray-800">{{ $siteSettings->site_name }}</h1>

            @if ($siteSettings->address)
                <p class="text-xs text-gray-500 mt-1">{{ $siteSettings->address }}</p>
            @endif
            @if ($siteSettings->phone)
                <p class="text-xs text-gray-500">{{ $siteSettings->phone }}</p>
            @endif

            @if ($siteSettings->receipt_header)
                <p class="text-xs text-gray-600 mt-3 italic">{{ $siteSettings->receipt_header }}</p>
            @endif
        </div>

        <div class="border-t border-dashed border-gray-300 my-4"></div>

        {{-- Judul & Kode --}}
        <div class="text-center mb-6">
            <p class="text-xs uppercase tracking-widest text-gray-400">Struk Booking</p>
            <p class="text-xl font-mono font-bold text-gray-800 mt-1">{{ $booking->booking_code }}</p>
            <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full
                {{ $booking->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-green-100 text-green-700' }}">
                {{ $booking->status === 'completed' ? 'Selesai' : 'Terkonfirmasi' }}
            </span>
        </div>

        {{-- Detail --}}
        <div class="space-y-2.5 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Customer</span>
                <span class="font-medium text-gray-800">{{ $booking->customer->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Venue</span>
                <span class="font-medium text-gray-800 text-right">{{ $booking->field->venue->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Lapangan</span>
                <span class="font-medium text-gray-800 text-right">{{ $booking->field->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Jam</span>
                <span class="font-medium text-gray-800">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} ({{ $booking->duration }} Jam)</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Metode Bayar</span>
                <span class="font-medium text-gray-800">
                    @switch($booking->payment_method)
                        @case('cash') Cash @break
                        @case('transfer') Transfer Bank @break
                        @case('qris') QRIS @break
                        @default {{ ucfirst($booking->payment_method ?? '-') }}
                    @endswitch
                </span>
            </div>
        </div>

        <div class="border-t border-dashed border-gray-300 my-4"></div>

        {{-- Total --}}
        <div class="flex justify-between items-center">
            <span class="font-semibold text-gray-700">Total Bayar</span>
            <span class="text-2xl font-bold text-green-600">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>

        <div class="border-t border-dashed border-gray-300 my-4"></div>

        {{-- Footer --}}
        <div class="text-center">
            @if ($siteSettings->receipt_footer)
                <p class="text-xs text-gray-500 whitespace-pre-line">{{ $siteSettings->receipt_footer }}</p>
            @endif
            <p class="text-[10px] text-gray-400 mt-4">Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>
    </div>
</body>
</html>
