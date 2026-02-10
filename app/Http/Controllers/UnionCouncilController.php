<?php

namespace App\Http\Controllers;

use App\Models\UnionCouncil;
use App\Models\Tehsil;
use Illuminate\Http\Request;

class UnionCouncilController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isDistrictUser()) {
                abort(403, 'Access denied. District users cannot access Union Councils.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $unionCouncils = UnionCouncil::with(['tehsil.district'])->orderBy('name')->get();
        $tehsils = Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        return view('union-councils.index', compact('unionCouncils', 'tehsils', 'districts'));
    }

    public function create()
    {
        $tehsils = Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        return view('union-councils.create', compact('tehsils', 'districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tehsil_id' => 'required|exists:tehsils,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        UnionCouncil::create($validated);

        return redirect()->route('union-councils.index')
            ->with('success', 'Union Council created successfully.');
    }

    public function show(UnionCouncil $unionCouncil)
    {
        $unionCouncil->load(['tehsil.district', 'villages']);
        return view('union-councils.show', compact('unionCouncil'));
    }

    public function edit(UnionCouncil $unionCouncil)
    {
        $tehsils = Tehsil::where('is_active', true)->with('district')->orderBy('name')->get();
        $districts = \App\Models\District::where('is_active', true)->orderBy('name')->get();
        return view('union-councils.edit', compact('unionCouncil', 'tehsils', 'districts'));
    }

    public function update(Request $request, UnionCouncil $unionCouncil)
    {
        $validated = $request->validate([
            'tehsil_id' => 'required|exists:tehsils,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $unionCouncil->update($validated);

        return redirect()->route('union-councils.index')
            ->with('success', 'Union Council updated successfully.');
    }

    public function destroy(UnionCouncil $unionCouncil)
    {
        if ($unionCouncil->villages()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete union council with associated villages.']);
        }

        $unionCouncil->delete();
        return redirect()->route('union-councils.index')
            ->with('success', 'Union Council deleted successfully.');
    }
}

