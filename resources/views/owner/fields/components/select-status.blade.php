<select name="status" class="border rounded-lg px-4 py-2">
    <option value="">Semua Status</option>
    <option value="1" @selected (request('status')==='1')>Aktif</option>
    <option value="0" @selected (request('status')==='0')>Nonaktif</option>
</select>
