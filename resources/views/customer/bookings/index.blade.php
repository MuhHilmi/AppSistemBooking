@extends ('layouts.customer')

@section ('title', 'Cari Lapang')

@section ('content')
    <div class="max-w-7xl mx-auto py-8">
        <div class="bg-white p-4 mb-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold">Booking Lapang</h1>
        </div>
        <div>
            @include ('customer.bookings.partials.filter')
        </div>
        <div x-data="{ showModal: false, selectedField: null, openModal(field) { this.selectedField = field; this.showModal = true; }, closeModal() { this.showModal = false; } }"
            x-on:keydown.escape.window="closeModal()">
            <div class="bg-white p-4 rounded-lg shadow-lg grid md:grid-cols-2 lg:grid-cols-3 gap-6">
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

            @include ('customer.bookings.partials.field-detail-modal')
        </div>
    </div>
@endsection
