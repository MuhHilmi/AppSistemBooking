@extends('layouts.landing')

@section('title', 'Semua Lapangan')
@section('description', 'Daftar lengkap lapangan olahraga yang tersedia untuk dibooking.')

@section('content')
    @include('landing.partials.navbar')

    <div class="pt-32 pb-24">
        <div class="container-custom">
            {{-- Heading --}}
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">Semua Lapangan</span>
                <h1 class="mt-5 text-4xl font-bold text-slate-900">Jelajahi Semua Lapangan Kami</h1>
                <p class="mt-5 text-lg text-slate-600">Temukan lapangan olahraga sesuai kebutuhan Anda dari seluruh venue yang tersedia.</p>
            </div>

            {{-- Card + Detail Popup --}}
            <div class="mx-auto max-w-7xl mt-5">
                <x-fields.grid :fields="$fields" />
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $fields->links() }}
            </div>
        </div>
    </div>

    @include('landing.partials.footer')
@endsection