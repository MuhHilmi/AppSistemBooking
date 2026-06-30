<section id="fields" class="bg-slate-50 py-24">

    <div class="container-custom">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <span
                class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                Lapangan Populer

            </span>

            <h2
                class="mt-5 text-4xl font-bold text-slate-900">

                Temukan Lapangan Favorit Anda

            </h2>

            <p
                class="mt-5 text-lg text-slate-600">

                Pilih berbagai jenis lapangan olahraga dengan jadwal
                yang selalu diperbarui secara real-time.

            </p>

        </div>

        @php

            $fields = [

                [
                    'name' => 'Futsal Arena A',
                    'city' => 'Bandung',
                    'price' => '120.000',
                    'rating' => '4.9',
                    'type' => 'Indoor'
                ],

                [
                    'name' => 'Badminton Center',
                    'city' => 'Jakarta',
                    'price' => '80.000',
                    'rating' => '4.8',
                    'type' => 'Badminton'
                ],

                [
                    'name' => 'Basket Hall',
                    'city' => 'Bekasi',
                    'price' => '150.000',
                    'rating' => '4.7',
                    'type' => 'Basket'
                ],

                [
                    'name' => 'Mini Soccer',
                    'city' => 'Bogor',
                    'price' => '250.000',
                    'rating' => '5.0',
                    'type' => 'Outdoor'
                ],

                [
                    'name' => 'Tennis Court',
                    'city' => 'Depok',
                    'price' => '180.000',
                    'rating' => '4.8',
                    'type' => 'Tennis'
                ],

                [
                    'name' => 'Volley Arena',
                    'city' => 'Cimahi',
                    'price' => '100.000',
                    'rating' => '4.9',
                    'type' => 'Volley'
                ],

            ];

        @endphp

        {{-- Cards --}}
        <div
            class="mt-20 grid gap-8 md:grid-cols-2 xl:grid-cols-3">

            @foreach($fields as $field)

                <div
                    class="overflow-hidden rounded-3xl bg-white shadow-sm transition duration-300 hover:-translate-y-2 hover:shadow-xl">

                    {{-- Image --}}
                    <img
                        src="https://placehold.co/700x450/F1F5F9/94A3B8?text=Lapangan"
                        alt="{{ $field['name'] }}"
                        class="h-56 w-full object-cover">

                    <div class="p-6">

                        {{-- Type --}}
                        <span
                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

                            {{ $field['type'] }}

                        </span>

                        {{-- Title --}}
                        <h3
                            class="mt-4 text-2xl font-bold text-slate-900">

                            {{ $field['name'] }}

                        </h3>

                        {{-- Rating --}}
                        <div
                            class="mt-4 flex items-center justify-between">

                            <span
                                class="text-yellow-500">

                                ⭐ {{ $field['rating'] }}

                            </span>

                            <span
                                class="text-slate-500">

                                📍 {{ $field['city'] }}

                            </span>

                        </div>

                        {{-- Price --}}
                        <div
                            class="mt-6">

                            <span
                                class="text-3xl font-bold text-green-600">

                                Rp{{ $field['price'] }}

                            </span>

                            <span
                                class="text-slate-500">

                                / Jam

                            </span>

                        </div>

                        {{-- Button --}}
                        <a
                            href="#"
                            class="mt-8 block rounded-xl bg-green-600 py-3 text-center font-semibold text-white transition hover:bg-green-700">

                            Booking Sekarang

                        </a>

                    </div>

                </div>

            @endforeach

        </div>

        {{-- CTA --}}
        <div
            class="mt-16 text-center">

            <a
                href="#"
                class="inline-flex rounded-xl border border-green-600 px-8 py-4 font-semibold text-green-600 transition hover:bg-green-600 hover:text-white">

                Lihat Semua Lapangan

            </a>

        </div>

    </div>

</section>

