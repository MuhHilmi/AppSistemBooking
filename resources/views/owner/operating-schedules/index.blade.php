@php
$days = [
    1 => 'Senin',
    2 => 'Selasa',
    3 => 'Rabu',
    4 => 'Kamis',
    5 => 'Jumat',
    6 => 'Sabtu',
    7 => 'Minggu',
];
@endphp

@extends('layouts.dashboard')

@section('content')

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">
        Jadwal Operasional
    </h1>
    <div class="grid gap-6">
        @foreach($fields as $field)
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold">
                            {{ $field->name }}
                        </h2>
                        <p class="text-gray-500">
                            {{ $field->venue->name }}
                        </p>
                    </div>
                    <a href="{{ route('owner.operating-schedules.edit', $field) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                        Edit Jadwal
                    </a>
                </div>
                <hr class="my-4">
                <div class="space-y-3">
                    @foreach($field->operatingSchedules as $schedule)
                        <div class="flex justify-between items-center">
                            <span class="font-medium">
                                {{ $days[$schedule->day_of_week] }}
                            </span>
                            @if($schedule->is_open)
                                <span class="text-green-600">
                                    ✔ {{ \Carbon\Carbon::parse($schedule->open_time)->format('H:i') }}
                                    - {{ \Carbon\Carbon::parse($schedule->close_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-red-600">
                                    ✖ Tutup
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
