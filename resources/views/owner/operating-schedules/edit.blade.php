@extends('layouts.dashboard')

@section('content')

@php

$days = [
    1=>'Senin',
    2=>'Selasa',
    3=>'Rabu',
    4=>'Kamis',
    5=>'Jumat',
    6=>'Sabtu',
    7=>'Minggu',
];

@endphp

@if($errors->any())

    <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
        {{ $errors->first() }}
    </div>

@endif

<form action="{{ route('owner.operating-schedules.update',$field) }}" method="POST">

@csrf
@method('PUT')

@foreach($field->operatingSchedules as $schedule)
<div class="bg-white rounded-lg shadow p-6 mb-5">
    <h3 class="font-bold text-lg mb-4">
        {{ $days[$schedule->day_of_week] }}
    </h3>
    <label class="flex items-center gap-3">
        <input
            type="checkbox"
            name="days[{{ $loop->index }}][is_open]"
            value="1"
            {{ $schedule->is_open ? 'checked':'' }}>
        Buka
    </label>
    <input
        type="hidden"
        name="days[{{ $loop->index }}][id]"
        value="{{ $schedule->id }}">
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label>
                Jam Buka
            </label>
            <input
                type="time"
                name="days[{{ $loop->index }}][open_time]"
                value="{{ $schedule->open_time }}"
                class="w-full border rounded p-2">
        </div>
        <div>
            <label>
                Jam Tutup
            </label>
            <input
                type="time"
                name="days[{{ $loop->index }}][close_time]"
                value="{{ $schedule->close_time }}"
                class="w-full border rounded p-2">
        </div>
    </div>
</div>
@endforeach

<div class="flex justify-end">
    <button class="bg-indigo-600 text-white px-6 py-2 rounded">
        Simpan Jadwal
    </button>
</div>
</form>

@endsection