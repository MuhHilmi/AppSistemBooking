@extends('layouts.customer')

@section('title', 'Review Lapangan')

@section('content')
<div class="mx-auto max-w-xl px-4 py-8">
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-5">
            <h1 class="text-xl font-bold text-gray-900">
                {{ $review ? 'Ubah Review' : 'Beri Review' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $field->name }} &middot; {{ $field->venue->name ?? '-' }}
            </p>
        </div>

        <form method="POST" action="{{ route('customer.reviews.store', $field) }}" class="space-y-5 p-6" x-data="{ rating: {{ old('rating', $review?->display_rating ?? 0) }} }">
            @csrf

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Rating</label>
                <div class="flex gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" x-on:click="rating = {{ $i }}" class="text-3xl leading-none focus:outline-none">
                            <span x-text="rating >= {{ $i }} ? '★' : '☆'" :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-gray-300'"></span>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" x-model="rating">
            </div>

            <div>
                <label for="comment" class="mb-1 block text-sm font-medium text-gray-700">Ceritakan pengalaman Anda</label>
                <textarea name="comment" id="comment" rows="5" required
                    placeholder="Bagaimana pengalaman Anda bermain di lapangan ini?"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('comment', $review?->display_comment) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('customer.bookings.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" x-on:click="if(rating < 1){ $event.preventDefault(); alert('Silakan pilih rating terlebih dahulu.'); }"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                    {{ $review ? 'Simpan Perubahan' : 'Kirim Review' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
