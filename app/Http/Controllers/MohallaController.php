<?php

namespace App\Http\Controllers;

use App\Models\Mohalla;
use App\Models\Village;
use Illuminate\Http\Request;

class MohallaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isDistrictUser()) {
                abort(403, 'Access denied. District users cannot access Mohallas.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $mohallas = Mohalla::with(['village.unionCouncil.tehsil.district'])->orderBy('name')->get();
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        $tehsils = \App\Models\Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        $unionCouncils = \App\Models\UnionCouncil::where('is_active', true)->with('tehsil.district')->orderBy('name')->get();
        return view('mohallas.index', compact('mohallas', 'villages', 'districts', 'tehsils', 'unionCouncils'));
    }

    public function create()
    {
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        $tehsils = \App\Models\Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        $unionCouncils = \App\Models\UnionCouncil::where('is_active', true)->with('tehsil.district')->orderBy('name')->get();
        return view('mohallas.create', compact('villages', 'districts', 'tehsils', 'unionCouncils'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'village_id' => 'required|exists:villages,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        Mohalla::create($validated);

        return redirect()->route('mohallas.index')
            ->with('success', 'Mohalla created successfully.');
    }

    public function show(Mohalla $mohalla)
    {
        $mohalla->load(['village.unionCouncil.tehsil.district', 'localZakatCommittees']);
        return view('mohallas.show', compact('mohalla'));
    }

    public function edit(Mohalla $mohalla)
    {
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        $tehsils = \App\Models\Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        $unionCouncils = \App\Models\UnionCouncil::where('is_active', true)->with('tehsil.district')->orderBy('name')->get();
        return view('mohallas.edit', compact('mohalla', 'villages', 'districts', 'tehsils', 'unionCouncils'));
    }

    public function update(Request $request, Mohalla $mohalla)
    {
        $validated = $request->validate([
            'village_id' => 'required|exists:villages,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $mohalla->update($validated);

        return redirect()->route('mohallas.index')
            ->with('success', 'Mohalla updated successfully.');
    }

    public function destroy(Mohalla $mohalla)
    {
        if ($mohalla->localZakatCommittees()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete mohalla with associated local zakat committees.']);
        }

        $mohalla->delete();
        return redirect()->route('mohallas.index')
            ->with('success', 'Mohalla deleted successfully.');
    }
}

