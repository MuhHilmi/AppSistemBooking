@extends ('layouts.dashboard')

@section ('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Header --}}
        @include ('owner.fields.partial.header')

        {{-- Alert --}}
        @include ('owner.fields.partial.alert')

        {{-- Filter --}}
        @include ('owner.fields.partial.filter')

        {{-- Card --}}
        @include ('owner.fields.partial.card')
    </div>
@endsection
