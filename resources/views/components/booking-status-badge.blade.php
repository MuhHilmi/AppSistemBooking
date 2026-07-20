@php
    $badge = match($status) {
        'waiting_payment_method' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'],
        'pending_payment' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
        'paid' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'confirmed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800'],
        'canceled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'],
    };
@endphp

<span class="inline-flex items-center rounded-fll px-3 py-1 text-sm font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">
    {{ Str::headline($status) }}
</span>