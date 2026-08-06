@extends ('layouts.dashboard')

@section ('title', 'Review & Testimoni')

@section ('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Review &amp; Testimoni</h1>
            <p class="text-gray-500 mt-1">Review dari customer yang pernah booking di venue Anda.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <div class="flex flex-wrap gap-2">
                @php
                    $tabs = [
                        'all' => 'Semua',
                        'pending' => 'Menunggu Persetujuan (' . $summary['pending'] . ')',
                        'pending_edit' => 'Ada Perubahan (' . $summary['pending_edit'] . ')',
                        'approved' => 'Sudah Tampil',
                    ];
                @endphp
                @foreach ($tabs as $value => $label)
                    <a href="{{ route('owner.reviews.index', ['status' => $value]) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold border
                            {{ request('status', 'all') === $value ? 'bg-green-600 text-white border-green-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- List --}}
        <div class="space-y-4">
            @forelse ($reviews as $review)
                <div class="bg-white rounded-xl shadow p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-gray-800">{{ $review->customer->name ?? '-' }}</p>
                                <span class="text-xs text-gray-400">&middot;</span>
                                <p class="text-sm text-gray-500">{{ $review->field->name ?? '-' }} ({{ $review->field->venue->name ?? '-' }})</p>

                                @if ($review->status === 'pending')
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Menunggu Persetujuan</span>
                                @elseif ($review->has_pending_edit)
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Ada Perubahan Menunggu Persetujuan</span>
                                @else
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Tampil ke Publik</span>
                                @endif
                            </div>

                            @unless ($review->has_pending_edit)
                                <div class="mt-2 text-amber-400 text-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                                <p class="mt-1 text-sm text-gray-700">{{ $review->comment }}</p>
                            @else
                                @if ($review->status === 'approved')
                                    <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                        <p class="text-xs font-semibold text-gray-400 mb-1">SAAT INI TAMPIL</p>
                                        <div class="text-amber-400 text-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                                        <p class="mt-1 text-sm text-gray-600">{{ $review->comment }}</p>
                                    </div>
                                @endif
                                <div class="mt-3 rounded-lg border border-blue-200 bg-blue-50 p-3">
                                    <p class="text-xs font-semibold text-blue-500 mb-1">PENGAJUAN PERUBAHAN</p>
                                    <div class="text-amber-400 text-sm">{{ str_repeat('★', $review->pending_rating) }}{{ str_repeat('☆', 5 - $review->pending_rating) }}</div>
                                    <p class="mt-1 text-sm text-gray-700">{{ $review->pending_comment }}</p>
                                </div>
                            @endunless
                        </div>

                        @if ($review->status === 'pending' || $review->has_pending_edit)
                            <div class="flex gap-2 shrink-0">
                                <form method="POST" action="{{ route('owner.reviews.approve', $review) }}">
                                    @csrf
                                    <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded-lg">
                                        Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('owner.reviews.reject', $review) }}"
                                    onsubmit="return confirm('{{ $review->has_pending_edit ? 'Tolak perubahan ini? Review lama akan tetap tampil.' : 'Tolak review ini? Review akan dihapus.' }}');">
                                    @csrf
                                    <button class="bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold px-4 py-2 rounded-lg">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-12 text-center text-gray-500">
                    Belum ada review pada kategori ini.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $reviews->links() }}</div>
    </div>
@endsection
