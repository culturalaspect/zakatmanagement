<?php

namespace App\Http\Controllers;

use App\Models\Tehsil;
use App\Models\District;
use Illuminate\Http\Request;

class TehsilController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isDistrictUser()) {
                abort(403, 'Access denied. District users cannot access Tehsils.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $tehsils = Tehsil::with('district')->orderBy('name')->get();
        $districts = District::where('is_active', true)->orderBy('name')->get();
        return view('tehsils.index', compact('tehsils', 'districts'));
    }

    public function create()
    {
        $districts = District::where('is_active', true)->orderBy('name')->get();
        return view('tehsils.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        Tehsil::create($validated);

        return redirect()->route('tehsils.index')
            ->with('success', 'Tehsil created successfully.');
    }

    public function show(Tehsil $tehsil)
    {
        $tehsil->load(['district', 'unionCouncils']);
        return view('tehsils.show', compact('tehsil'));
    }

    public function edit(Tehsil $tehsil)
    {
        $districts = District::where('is_active', true)->orderBy('name')->get();
        return view('tehsils.edit', compact('tehsil', 'districts'));
    }

    public function update(Request $request, Tehsil $tehsil)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $tehsil->update($validated);

        return redirect()->route('tehsils.index')
            ->with('success', 'Tehsil updated successfully.');
    }

    public function destroy(Tehsil $tehsil)
    {
        if ($tehsil->unionCouncils()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete tehsil with associated union councils.']);
        }

        $tehsil->delete();
        return redirect()->route('tehsils.index')
            ->with('success', 'Tehsil deleted successfully.');
    }
}

