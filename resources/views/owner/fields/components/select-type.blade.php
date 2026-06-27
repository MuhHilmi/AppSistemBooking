<select name="sport_type" class="border rounded-lg px-4 py-2">
    <option value="">Semua Jenis</option>
    @foreach([ 'Futsal', 'Badminton', 'Basket', 'Mini Soccer', 'Tenis', 'Voli' ] as $sport)
        <option value="{{ $sport }}" @selected(request('sport_type')==$sport)>
            {{ $sport }}
        </option>
    @endforeach
</select>