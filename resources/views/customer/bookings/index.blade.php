@extends ('layouts.customer')

@section ('title', 'Cari Lapang')

@section ('content')
    <div class="max-w-7xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8">Booking Lapang</h1>
        <div>
            @include ('customer.bookings.partials.filter')
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if ($fields -> isEmpty())
                <div class="col-span-full">
                    <div class="bg-white rounded-xl p-10 text-center">
                        <h2 class="text-xl font-semibold">Belum ada lapang yang tersedia</h2>
                    </div>
                </div>
            @else
                @foreach ($fields as $field)
                    @include ('customer.bookings.partials.field-card', ['field' => $field])
                @endforeach
            @endif
        </div>
    </div>
@endsection
