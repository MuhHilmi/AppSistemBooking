@extends('layouts.dashboard')

@section('title', 'Profil')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include ('profile.partials.update-password-form')
            </div>
        </div>

        {{-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include ('profile.partials.delete-user-form')
            </div>
        </div> --}}
    </div>
@endsection
