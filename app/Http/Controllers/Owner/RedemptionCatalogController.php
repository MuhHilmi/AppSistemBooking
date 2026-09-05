<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RedemptionCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ownerId = Auth::id();

        $items = Benefit::whereHas('venue', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })
            ->with('venue')
            ->orderByDesc('created_at')
            ->get();

        return view('owner.redemptions.index', ['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $venues = Venue::where('owner_id', Auth::id())->orderBy('name')->get();

        return view('owner.redemptions.create', ['venues' => $venues]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ownerId = Auth::id();

        $validated = $request->validate([
            'venue_id' => ['required', Rule::exists('venues', 'id')->where('owner_id', $ownerId)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:voucher,free_item,discount',
            'value_type' => 'required|in:fixed_amount,percentage,text',
            'value' => 'nullable|string|max:255',
            'point_cost' => 'required|integer|min:1',
        ]);

        $item = Benefit::create([
            ...$validated,
            'code' => $this->generateUniqueCode($validated['name']),
            'is_active' => true,
        ]);

        return redirect()->route('owner.redemptions.index')
            ->with('success', "Item tukar poin \"{$item->name}\" berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Benefit $redemption)
    {
        $this->authorizeOwnership($redemption);

        $venues = Venue::where('owner_id', Auth::id())->orderBy('name')->get();

        return view('owner.redemptions.edit', ['item' => $redemption, 'venues' => $venues]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Benefit $redemption)
    {
        $this->authorizeOwnership($redemption);

        $ownerId = Auth::id();

        $validated = $request->validate([
            'venue_id' => ['required', Rule::exists('venues', 'id')->where('owner_id', $ownerId)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:voucher,free_item,discount',
            'value_type' => 'required|in:fixed_amount,percentage,text',
            'value' => 'nullable|string|max:255',
            'point_cost' => 'required|integer|min:1',
        ]);

        $redemption->update($validated);

        return redirect()->route('owner.redemptions.index')
            ->with('success', "Item tukar poin \"{$redemption->name}\" berhasil diperbarui.");
    }

    public function toggleActive(Benefit $redemption)
    {
        $this->authorizeOwnership($redemption);

        $redemption->update(['is_active' => ! $redemption->is_active]);

        $status = $redemption->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Item \"{$redemption->name}\" berhasil {$status}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Benefit $redemption)
    {
        $this->authorizeOwnership($redemption);

        $redemption->delete();

        return redirect()->route('owner.redemptions.index')
            ->with('success', "Item \"{$redemption->name}\" berhasil dihapus.");
    }

    private function authorizeOwnership(Benefit $benefit): void
    {
        abort_unless(
            $benefit->venue && $benefit->venue->owner_id === Auth::id(),
            403
        );
    }

    private function generateUniqueCode(string $name): string
    {
        $base = Str::slug($name);
        $code = $base.'-'.Str::lower(Str::random(6));

        while (Benefit::where('code', $code)->exists()) {
            $code = $base.'-'.Str::lower(Str::random(6));
        }

        return $code;
    }
}
