<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venues = Venue::where(
            'owner_id',
            auth()->id()
        )->latest()->get();

        return view('owner.venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('owner.venues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'open_time' => 'required',
            'close_time' => 'required'
        ]);

        $photo = null;
        if($request->hasFile('photo'))
        {
            $photo = $request
                ->file('photo')
                ->store(
                    'venues',
                    'public'
                );
        }

        Venue::create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'address' => $request->address,
            'description' => $request->description,
            'phone' => $request->phone,
            'photo' => $photo,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time
        ]);

        return redirect()
        ->route('venues.index')
        ->with('success', 'Tempat berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue)
    {
        return view('owner.venues.show', compact('venue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venue $venue)
    {
        $this->authorize('update', $venue);

        return view('owner.venues.edit',compact('venue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venue $venue)
    {

        // dd('Masuk Update');
        $this->authorize('update', $venue);

        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'open_time' => 'required',
            'close_time' => 'required'
        ]);

        $photo = $venue->photo;

        if ($request->hasFile('photo')) {
            $photo = $request
                ->file('photo')
                ->store(
                    'venues',
                    'public'
                );
        }

        $venue->update([
            'name' => $request->name,
            'slug' => Str::slug(
                $request->name
            ),
            'address' => $request->address,
            'phone' => $request->phone,
            'photo' => $photo,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'status' => $request->status
        ]);

        return redirect()->route('venues.index')->with('success', 'Venue berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue)
    {
        $this->authorize('delete', $venue);

        if ($venue->fields()->exists()) {
            return back()
                ->with(
                    'error',
                    'Venue masih memiliki lapangan'
                );
        }

        if ($venue->photo) {
            Storage::disk('public')
                ->delete($venue->photo);
        }

        $venue->delete();

        return redirect()->route('venues.index')->with('success', 'Venue berhasil dihapus');
    }
}
