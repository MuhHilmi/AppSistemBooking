<select name="venue_id" class="border rounded-lg px-4 py-2">
    <option value="">
        Semua Venue
    </option>
    @foreach($venues as $venue)
        <option value="{{ $venue->id }}" @selected(request('venue_id')==$venue->id)>
            {{ $venue->name }}
        </option>
    @endforeach
</select>