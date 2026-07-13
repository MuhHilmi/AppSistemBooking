@extends('layouts.dashboard')

@section('title', 'Dashboard | Owner')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-500">
                Booking Hari Ini
            </p>
            <h2 class="text-4xl font-bold">
                {{ $today }}
            </h2>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-500">
                Booking Besok
            </p>
            <h2 class="text-4xl font-bold text-yellow-500">
                {{ $tomorrow }}
            </h2>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-500">
                Pending
            </p>
            <h2 class="text-4xl font-bold text-green-600">
                {{ $pending }}
            </h2>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-500">
                Lunas
            </p>
            <h2 class="text-4xl font-bold text-green-600">
                {{ $confirmed }}
            </h2>
        </div>
    </div>
@endsection