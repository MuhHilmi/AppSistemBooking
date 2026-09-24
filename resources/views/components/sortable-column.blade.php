@php
    $currentSort = request('sort');
    $currentDirection = request('direction', 'asc');

    $isActive = $currentSort === $column;

    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';

    $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection]);
@endphp

<a href="{{ $url }}" class="flex items-center gap-1 hover:text-gray-900">
    {{ $label }}
    @if ($isActive)
        @if ($currentDirection === 'asc')
            <span>&uarr;</span>
        @else
            <span>&darr;</span>
        @endif
    @else
        <span class="text-gray-400">&duarr;</span>
    @endif
</a>
