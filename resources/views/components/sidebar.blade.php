<aside class="w-64 min-h-screen bg-slate-800 text-white">
    <div>
        <div>
            <div class="p-4 font-bold text-xl">Booking Lapangan</div>

            <ul class="mt-5">
                <li>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-slate-700">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('venues.index') }}"
                        class="block px-4 py-2 hover:bg-slate-700"
                    >
                        Venue
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('fields.index') }}"
                        class="block px-4 py-2 hover:bg-slate-700"
                    >
                        Lapangan
                    </a>
                </li>
                <li>
                    <a href="{{ route('operating-schedules.index') }}" class="block px-4 py-2 hover:bg-slate-700">
                        Jadwal Operasional
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-700"> Booking </a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-700"> Pembayaran </a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-700"> Customer </a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-700"> Laporan </a>
                </li>
            </ul>
        </div>

        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="mx-4 my-2 px-4 py-2 rounded-md bg-red-500 hover:bg-red-700 text-white font-semibold transition duration-200"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
