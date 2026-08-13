<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Daftar semua akun penjaga milik venue-venue owner yang login.
     */
    public function index()
    {
        $ownerId = Auth::id();

        $staff = User::where('role', 'penjaga')
            ->whereHas('venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->with('venue')
            ->orderBy('name')
            ->get();

        return view('owner.staff.index', ['staff' => $staff]);
    }

    public function create()
    {
        $venues = Venue::where('owner_id', Auth::id())->orderBy('name')->get();

        return view('owner.staff.create', ['venues' => $venues]);
    }

    /**
     * Buat akun penjaga baru. Password digenerate sistem secara acak dan
     * wajib diganti penjaga saat login pertama. Hanya bisa di-assign ke
     * venue milik owner yang sedang login.
     */
    public function store(Request $request)
    {
        $ownerId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'venue_id' => [
                'required',
                Rule::exists('venues', 'id')->where('owner_id', $ownerId),
            ],
        ]);

        $generatedPassword = Str::password(10, symbols: false);

        $penjaga = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'penjaga',
            'venue_id' => $request->venue_id,
            'is_active' => true,
            'must_change_password' => true,
            'password' => Hash::make($generatedPassword),
        ]);

        return redirect()->route('owner.staff.index')->with([
            'success' => "Akun penjaga \"{$penjaga->name}\" berhasil dibuat.",
            'generated_password' => $generatedPassword,
            'generated_password_for' => $penjaga->email,
        ]);
    }

    public function edit(User $staff)
    {
        $this->authorizeStaff($staff);

        $venues = Venue::where('owner_id', Auth::id())->orderBy('name')->get();

        return view('owner.staff.edit', ['staff' => $staff, 'venues' => $venues]);
    }

    public function update(Request $request, User $staff)
    {
        $this->authorizeStaff($staff);

        $ownerId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($staff->id)],
            'venue_id' => [
                'required',
                Rule::exists('venues', 'id')->where('owner_id', $ownerId),
            ],
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'venue_id' => $request->venue_id,
        ]);

        return redirect()->route('owner.staff.index')->with('success', "Data penjaga \"{$staff->name}\" berhasil diperbarui.");
    }

    /**
     * Aktifkan/nonaktifkan akun penjaga tanpa menghapus datanya.
     */
    public function toggleActive(User $staff)
    {
        $this->authorizeStaff($staff);

        $staff->update(['is_active' => ! $staff->is_active]);

        $status = $staff->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun penjaga \"{$staff->name}\" berhasil {$status}.");
    }

    public function destroy(User $staff)
    {
        $this->authorizeStaff($staff);

        $staff->delete();

        return redirect()->route('owner.staff.index')->with('success', "Akun penjaga \"{$staff->name}\" berhasil dihapus.");
    }

    /**
     * Pastikan target staff benar-benar penjaga di salah satu venue milik owner yang login.
     */
    private function authorizeStaff(User $staff): void
    {
        abort_unless(
            $staff->isPenjaga() && $staff->venue && $staff->venue->owner_id === Auth::id(),
            403
        );
    }
}
