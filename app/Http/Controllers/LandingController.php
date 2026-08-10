<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Review;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::with('venue')
            ->where('status', true)
            ->latest()
            ->take(6)
            ->get();

        $reviews = Review::with(['customer', 'field'])
            ->where('status', 'approved')
            ->where('has_pending_edit', false)
            ->latest()
            ->take(6)
            ->get();

        $siteSettings = SiteSetting::current();

        return view('landing.index', compact('fields', 'reviews', 'siteSettings'));
    }

    public function allFields()
    {
        $fields = Field::with('venue')
            ->where('status', true)
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $siteSettings = SiteSetting::current();

        return view('fields.index', compact('fields', 'siteSettings'));
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
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        //
    }
}
