<section id="testimonial" class="bg-white py-24">

    <div class="container-custom">

        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <span
                class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                Testimoni

            </span>

            <h2
                class="mt-5 text-4xl font-bold text-slate-900">

                Apa Kata Pengguna Kami?

            </h2>

            <p
                class="mt-5 text-lg text-slate-600">

                Ribuan pengguna telah menggunakan platform kami
                untuk memesan lapangan olahraga dengan mudah.

            </p>

        </div>

        @php

            $testimonials = [

                [
                    'name' => 'Andi Saputra',
                    'field' => 'Futsal',
                    'rating' => 5,
                    'comment' => 'Proses booking sangat mudah. Jadwal yang ditampilkan selalu sesuai dan pembayaran juga cepat.'
                ],

                [
                    'name' => 'Rina Amelia',
                    'field' => 'Badminton',
                    'rating' => 5,
                    'comment' => 'Tidak perlu datang ke lokasi hanya untuk bertanya jadwal. Semuanya bisa dilakukan secara online.'
                ],

                [
                    'name' => 'Budi Santoso',
                    'field' => 'Basket',
                    'rating' => 5,
                    'comment' => 'Antarmuka aplikasi sangat mudah dipahami. Saya bisa booking hanya dalam beberapa menit.'
                ],

            ];

        @endphp

        {{-- Cards --}}
        <div
            class="mt-20 grid gap-8 lg:grid-cols-3">

            @foreach($testimonials as $testimonial)

                <div
                    class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm transition duration-300 hover:-translate-y-2 hover:shadow-xl">

                    {{-- Avatar --}}
                    <div
                        class="flex items-center gap-4">

                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-green-600 text-2xl font-bold text-white">

                            {{ strtoupper(substr($testimonial['name'],0,1)) }}

                        </div>

                        <div>

                            <h3
                                class="font-bold text-lg">

                                {{ $testimonial['name'] }}

                            </h3>

                            <p
                                class="text-slate-500">

                                Pemesan {{ $testimonial['field'] }}

                            </p>

                        </div>

                    </div>

                    {{-- Rating --}}
                    <div
                        class="mt-6 text-yellow-500 text-xl">

                        @for($i=0;$i<$testimonial['rating'];$i++)

                            ⭐

                        @endfor

                    </div>

                    {{-- Comment --}}
                    <p
                        class="mt-6 leading-8 text-slate-600">

                        "{{ $testimonial['comment'] }}"

                    </p>

                </div>

            @endforeach

        </div>

        {{-- Bottom Statistic --}}
        <div
            class="mt-20 rounded-3xl bg-green-600 px-8 py-10 text-center text-white">

            <h3
                class="text-3xl font-bold">

                12.500+

            </h3>

            <p
                class="mt-3 text-green-100">

                Pengguna telah mempercayakan kebutuhan booking
                lapangan olahraga kepada kami.

            </p>

        </div>

    </div>

</section>
