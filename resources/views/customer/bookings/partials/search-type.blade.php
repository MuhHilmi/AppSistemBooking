<select name="sport_type" class="border rounded-lg px-4 py-2">
    <option value="">Pilih tipe lapang</option>
    @foreach ([ 'futsal', 'badminton', 'basket', 'tennis', 'voli' ] as $sport)
        <option value="{{ $sport }}" @selected (request('sport_type') == $sport)>
            {{ ucwords($sport) }}
        </option>
    @endforeach
</select>
