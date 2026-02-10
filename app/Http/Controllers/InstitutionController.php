<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UnionCouncil;
use App\Models\Village;
use App\Models\Mohalla;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institutions = Institution::with(['district', 'tehsil', 'unionCouncil', 'village', 'mohalla'])
            ->orderBy('name')
            ->get();
        
        $districts = District::where('is_active', true)->orderBy('name')->get();
        $types = [
            'middle_school' => 'Middle School',
            'high_school' => 'High School',
            'college' => 'College',
            'university' => 'University',
            'madarsa' => 'Madarsa',
            'hospital' => 'Hospital',
        ];
        
        return view('institutions.index', compact('institutions', 'districts', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::where('is_active', true)->get();
        $tehsils = Tehsil::where('is_active', true)->with('district')->get();
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->get();
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->get();
        $mohallas = Mohalla::where('is_active', true)->with('village.unionCouncil.tehsil.district')->get();
        
        $types = [
            'middle_school' => 'Middle School',
            'high_school' => 'High School',
            'college' => 'College',
            'university' => 'University',
            'madarsa' => 'Madarsa',
            'hospital' => 'Hospital',
        ];
        
        return view('institutions.create', compact('districts', 'tehsils', 'unionCouncils', 'villages', 'mohallas', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:middle_school,high_school,college,university,madarsa,hospital',
            'code' => 'nullable|string|max:255|unique:institutions,code',
            'district_id' => 'nullable|exists:districts,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',
            'union_council_id' => 'nullable|exists:union_councils,id',
            'village_id' => 'nullable|exists:villages,id',
            'mohalla_id' => 'nullable|exists:mohallas,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'principal_director_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = Institution::generateCode($validated['type']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Institution::create($validated);

        return redirect()->route('institutions.index')
            ->with('success', 'Institution created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Institution $institution)
    {
        $institution->load(['district', 'tehsil', 'unionCouncil', 'village', 'mohalla']);
        return view('institutions.show', compact('institution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institution $institution)
    {
        $districts = District::where('is_active', true)->get();
        $tehsils = Tehsil::where('is_active', true)->with('district')->get();
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->get();
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->get();
        $mohallas = Mohalla::where('is_active', true)->with('village.unionCouncil.tehsil.district')->get();
        
        $types = [
            'middle_school' => 'Middle School',
            'high_school' => 'High School',
            'college' => 'College',
            'university' => 'University',
            'madarsa' => 'Madarsa',
            'hospital' => 'Hospital',
        ];
        
        return view('institutions.edit', compact('institution', 'districts', 'tehsils', 'unionCouncils', 'villages', 'mohallas', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:middle_school,high_school,college,university,madarsa,hospital',
            'code' => 'nullable|string|max:255|unique:institutions,code,' . $institution->id,
            'district_id' => 'nullable|exists:districts,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',
            'union_council_id' => 'nullable|exists:union_councils,id',
            'village_id' => 'nullable|exists:villages,id',
            'mohalla_id' => 'nullable|exists:mohallas,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'principal_director_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $institution->update($validated);

        return redirect()->route('institutions.index')
            ->with('success', 'Institution updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution)
    {
        $institution->delete();

        return redirect()->route('institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }
}
