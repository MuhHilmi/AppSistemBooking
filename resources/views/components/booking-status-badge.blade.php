@php
    $badge = match($status) {
        'waiting_payment_method' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Menunggu Metode Pembayaran'],
        'pending_payment' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Pembayaran'],
        'paid' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Sudah Dibayar'],
        'confirmed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Dikonfirmasi'],
        'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'label' => 'Selesai'],
        'canceled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Dibatalkan'],
        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => Str::headline($status)],
    };
@endphp

<span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">
    {{ $badge['label'] }}
</span>