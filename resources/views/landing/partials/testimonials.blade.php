@if ($reviews->count())
    <section id="testimonial" class="bg-white py-24">
        <div class="container-custom">
            {{-- Heading --}}
            <div class="mx-auto max-w-3xl text-center">
                <span
                    class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700"
                >
                    Testimoni
                </span>

                <h2 class="mt-5 text-4xl font-bold text-slate-900">Apa Kata Pengguna Kami?</h2>

                <p
                    class="mt-5 text-lg text-slate-600"
                >Ribuan pengguna telah menggunakan platform kami untuk memesan lapangan olahraga dengan mudah.</p>
            </div>

            {{-- Cards --}}
            <div class="mt-20 grid gap-8 lg:grid-cols-3">
                @foreach ($reviews as $review)
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            @if ($review->customer->photo)
                                <img src="{{ asset('storage/'.$review->customer->photo) }}" alt="{{ $review->customer->name }}" class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-lg font-semibold text-green-600">
                                    {{ strtoupper(substr($review->customer->name,0,1)) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $review->field->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $review->field->name }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg
                                    class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.95-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <blockquote class="mt-4 text-gray-600 leading-relaxed">
                            "{{ $review->comment }}"
                        </blockquote>
                        <div class="mt-6 text-sm text-gray-400">
                            {{ $review->created_at->format('d M Y') }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bottom Statistic --}}
            <div class="mt-20 rounded-3xl bg-green-600 px-8 py-10 text-center text-white">
                <h3 class="text-3xl font-bold">12.500+</h3>

                <p
                    class="mt-3 text-green-100"
                >Pengguna telah mempercayakan kebutuhan booking lapangan olahraga kepada kami.</p>
            </div>
        </div>
    </section>
@endif
