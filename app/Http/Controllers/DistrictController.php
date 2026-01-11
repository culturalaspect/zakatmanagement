<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::orderBy('name')->get();
        return view('districts.index', compact('districts'));
    }

    public function create()
    {
        return view('districts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:districts',
            'population' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        District::create($validated);

        return redirect()->route('districts.index')
            ->with('success', 'District added successfully.');
    }

    public function show(District $district)
    {
        return view('districts.show', compact('district'));
    }

    public function edit(District $district)
    {
        return view('districts.edit', compact('district'));
    }

    public function update(Request $request, District $district)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:districts,name,' . $district->id,
            'population' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $district->update($validated);

        return redirect()->route('districts.index')
            ->with('success', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();
        return redirect()->route('districts.index')
            ->with('success', 'District deleted successfully.');
    }
}
