<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Venue;
use App\Models\OperatingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Field::with('venue')
            ->whereHas('venue', function ($q) {
                $q->where('owner_id', auth()->id());
            });

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sport_type', 'like', '%' . $request->search . '%')
                    ->orWhereHas('venue', function ($venue) use ($request) {
                    $venue->where(
                        'name',
                        'like',
                        '%' . $request->search . '%'
                    );
                });
            });
        }

        // Filter Jenis
        if ($request->filled('sport_type')) {
            $query->where(
                'sport_type',
                $request->sport_type
            );
        }

        // Filter Status
        if ($request->status !== null && $request->status !== '') {
            $query->where(
                'status',
                $request->status
            );
        }

        // Filter Venue
        if ($request->filled('venue_id')) {
            $query->where(
                'venue_id',
                $request->venue_id
            );
        }

        $fields = $query->latest()->paginate(9);

        $venues = Venue::where(
            'owner_id',
            auth()->id()
        )->get();

        return view(
            'owner.fields.index',
            compact(
                'fields',
                'venues'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $venues = Venue::where(
            'owner_id',
            auth()->id()
        )->get();

        return view(
            'owner.fields.create',
            compact('venues')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|max:255',
            'sport_type' => 'required',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required'
        ]);

        $thumbnail = null;

        if($request->hasFile('thumbnail'))
        {
            $thumbnail = $request
                ->file('thumbnail')
                ->store('fields', 'public');
        }

        $field = Field::create([
            'venue_id'=>$request->venue_id,
            'name'=>$request->name,
            'slug'=>Str::slug($request->name),
            'sport_type'=>$request->sport_type,
            'price_per_hour'=>$request->price_per_hour,
            'capacity'=>$request->capacity,
            'description'=>$request->description,
            'thumbnail'=>$thumbnail,
            'status'=>$request->status
        ]);

        for ($day = 1; $day <= 7; $day++) {
            OperatingSchedule::create([
                'field_id'    => $field->id,
                'day_of_week' => $day,
                'open_time'   => '08:00',
                'close_time'  => '22:00',
                'is_open'     => true,
            ]);
        }

        return redirect()
        ->route('fields.index')
        ->with('success', 'Tempat berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        $this->authorizeField($field);

        return view(
            'owner.fields.show',
            compact('field')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        $this->authorizeField($field);

        $venues = Venue::where(
            'owner_id',
            auth()->id()
        )->get();

        return view(
            'owner.fields.edit',
            compact(
                'field',
                'venues'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        $this->authorizeField($field);

        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|max:255',
            'sport_type' => 'required',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required'
        ]);

        $thumbnail = $field->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($field->thumbnail) {
                Storage::disk('public')
                    ->delete($field->thumbnail);
            }
            $thumbnail = $request
                ->file('thumbnail')
                ->store('fields', 'public');
        }

        $field->update([
            'venue_id' => $request->venue_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sport_type' => $request->sport_type,
            'price_per_hour' => $request->price_per_hour,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'thumbnail' => $thumbnail,
            'status' => $request->status
        ]);

        return redirect()
            ->route('fields.index')
            ->with(
                'success',
                'Lapangan berhasil diperbarui.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        $this->authorizeField($field);

        // Nanti ketika booking selesai
        // if($field->bookings()->exists()){
        //     return back()->with(
        //         'error',
        //         'Lapangan masih memiliki booking.'
        //     );
        // }

        if ($field->thumbnail) {
            Storage::disk('public')
                ->delete($field->thumbnail);
        }

        $field->delete();

        return back()->with(
            'success',
            'Lapangan berhasil dihapus.'
        );
    }

    private function authorizeField(Field $field)
    {
        abort_if(
            $field->venue->owner_id != auth()->id(),
            403
        );
    }
}
