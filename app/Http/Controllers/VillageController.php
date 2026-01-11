<?php

namespace App\Http\Controllers;

use App\Models\Village;
use App\Models\UnionCouncil;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isDistrictUser()) {
                abort(403, 'Access denied. District users cannot access Villages.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $villages = Village::with(['unionCouncil.tehsil.district'])->orderBy('name')->get();
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->orderBy('name')->get();
        return view('villages.index', compact('villages', 'unionCouncils'));
    }

    public function create()
    {
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        $tehsils = \App\Models\Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        return view('villages.create', compact('unionCouncils', 'districts', 'tehsils'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'union_council_id' => 'required|exists:union_councils,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        Village::create($validated);

        return redirect()->route('villages.index')
            ->with('success', 'Village created successfully.');
    }

    public function show(Village $village)
    {
        $village->load(['unionCouncil.tehsil.district', 'mohallas']);
        return view('villages.show', compact('village'));
    }

    public function edit(Village $village)
    {
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        $tehsils = \App\Models\Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        return view('villages.edit', compact('village', 'unionCouncils', 'districts', 'tehsils'));
    }

    public function update(Request $request, Village $village)
    {
        $validated = $request->validate([
            'union_council_id' => 'required|exists:union_councils,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $village->update($validated);

        return redirect()->route('villages.index')
            ->with('success', 'Village updated successfully.');
    }

    public function destroy(Village $village)
    {
        if ($village->mohallas()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete village with associated mohallas.']);
        }

        $village->delete();
        return redirect()->route('villages.index')
            ->with('success', 'Village deleted successfully.');
    }
}

