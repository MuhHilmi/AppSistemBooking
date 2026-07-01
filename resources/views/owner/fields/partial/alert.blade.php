@if (session('success'))
    <div class="mb-6 bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">
        {{ session('success') }}
    </div>
@endif
