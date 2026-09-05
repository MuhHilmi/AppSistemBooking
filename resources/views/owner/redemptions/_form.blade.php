@php
    $item = $item ?? null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
    <select name="venue_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
        <option value="">-- Pilih venue --</option>
        @foreach ($venues as $venue)
            <option value="{{ $venue->id }}" {{ old('venue_id', $item->venue_id ?? null) == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
        @endforeach
    </select>
    @if ($venues->isEmpty())
        <p class="mt-1 text-xs text-red-600">Anda belum punya venue. Tambah venue dulu sebelum membuat item tukar poin.</p>
    @endif
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Item</label>
    <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" placeholder="mis. Gratis 1 Minuman"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
    <textarea name="description" rows="2" placeholder="Penjelasan singkat untuk customer"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">{{ old('description', $item->description ?? '') }}</textarea>
</div>

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Item</label>
        <select name="type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
            @foreach (['voucher' => 'Voucher potongan harga', 'free_item' => 'Item gratis (minuman/snack)', 'discount' => 'Diskon langsung'] as $value => $label)
                <option value="{{ $value }}" {{ old('type', $item->type ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Poin Dibutuhkan</label>
        <input type="number" name="point_cost" min="1" value="{{ old('point_cost', $item->point_cost ?? '') }}" placeholder="mis. 50"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
    </div>
</div>

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Bentuk Nilai</label>
        <select name="value_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
            @foreach (['fixed_amount' => 'Nominal rupiah', 'percentage' => 'Persentase', 'text' => 'Teks bebas'] as $value => $label)
                <option value="{{ $value }}" {{ old('value_type', $item->value_type ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai</label>
        <input type="text" name="value" value="{{ old('value', $item->value ?? '') }}" placeholder="mis. 10000 atau 1 minuman"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
        <p class="mt-1 text-xs text-gray-400">Isi angka rupiah untuk voucher/diskon, atau teks bebas untuk item fisik.</p>
    </div>
</div>
