@props(['route', 'active' => null])

@php
    $isActive = request()->routeIs($active ?? $route);
@endphp

<a href="{{ route($route) }}" {{ $attributes->merge(['class' => 'px-3 py-2 rounded-lg text-[13.5px] font-500 transition ' . ($isActive ? 'text-[var(--primary)] bg-[var(--primary-tint)]' : 'text-[var(--ink-soft)] hover:bg-[var(--bg)]')]) }}>
    {{ $slot }}
</a>
