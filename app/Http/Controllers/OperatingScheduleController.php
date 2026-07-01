<?php

namespace App\Http\Controllers;

use App\Models\OperatingSchedule;
use App\Models\Field;
use Illuminate\Http\Request;

class OperatingScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::with(['operatingSchedules' => function ($query) {$query->orderBy('day_of_week');}, 'venue'])
        ->whereHas('venue', function ($query) {
            $query->where('owner_id', auth()->id());
        })->get();

        return view('owner.operating-schedules.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OperatingSchedule $operatingSchedule)
    {
        $field = Field::findOrFail($fieldId);

        $schedules = $field->operatingSchedules;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OperatingSchedule $operatingSchedule, Field $field)
    {
        abort_if(
            $field->venue->owner_id != auth()->id(),
            403
        );

        $field->load('operatingSchedules');

        return view(
            'owner.operating-schedules.edit',
            compact('field')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperatingSchedule $operatingSchedule, Field $field)
    {
    abort_if($field->venue->owner_id != auth()->id(), 403);
    foreach($request->days as $day){
        $schedule = OperatingSchedule::findOrFail(
            $day['id']
        );
        if(
            isset($day['is_open']) &&
            $day['open_time'] >= $day['close_time']
        ){
            return back()->withErrors(['Jam operasional tidak valid']);
        }
        $schedule->update([
            'open_time'=>$day['open_time'],
            'close_time'=>$day['close_time'],
            'is_open'=>isset($day['is_open'])
        ]);
    }

    return redirect()
        ->route('operating-schedules.index')
        ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperatingSchedule $operatingSchedule)
    {
        //
    }
}
