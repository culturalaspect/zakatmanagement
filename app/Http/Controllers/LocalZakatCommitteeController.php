<?php

namespace App\Http\Controllers;

use App\Models\LocalZakatCommittee;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UnionCouncil;
use App\Models\Village;
use App\Models\Mohalla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalZakatCommitteeController extends Controller
{
    public function __construct()
    {
        // Restrict access for district users
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isDistrictUser()) {
                abort(403, 'Access denied. District users cannot access Local Zakat Committees.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $committees = LocalZakatCommittee::with(['district', 'members', 'mohallas'])
            ->orderByRaw('COALESCE(code, name) ASC')
            ->get();
        $districts = District::where('is_active', true)->orderBy('name')->get();
        return view('local-zakat-committees.index', compact('committees', 'districts'));
    }

    public function create()
    {
        $districts = District::where('is_active', true)->get();
        $tehsils = Tehsil::where('is_active', true)->with('district')->get();
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->get();
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->get();
        $mohallas = Mohalla::where('is_active', true)->with('village.unionCouncil.tehsil.district')->get();
        return view('local-zakat-committees.create', compact('districts', 'tehsils', 'unionCouncils', 'villages', 'mohallas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'area_coverage' => 'nullable|string',
            'formation_date' => 'required|date',
            'tenure_years' => 'required|integer|min:1',
            'mohalla_ids' => 'required|array|min:1',
            'mohalla_ids.*' => 'exists:mohallas,id',
            'is_active' => 'boolean',
        ]);

        $validated['tenure_end_date'] = date('Y-m-d', strtotime($validated['formation_date'] . ' + ' . $validated['tenure_years'] . ' years'));
        
        // Generate unique code
        $validated['code'] = LocalZakatCommittee::generateCode($validated['district_id']);

        $committee = LocalZakatCommittee::create($validated);
        
        // Attach mohallas
        $committee->mohallas()->attach($validated['mohalla_ids']);

        return redirect()->route('local-zakat-committees.index')
            ->with('success', 'Local Zakat Committee created successfully with code: ' . $committee->code);
    }

    public function show(LocalZakatCommittee $localZakatCommittee)
    {
        // Deactivate any members whose tenure has expired
        \App\Models\LZCMember::deactivateExpiredMembers();
        
        $localZakatCommittee->load(['district', 'members', 'mohallas.village.unionCouncil.tehsil.district']);
        
        // Get all available mohallas for this district (excluding already attached ones)
        $attachedMohallaIds = $localZakatCommittee->mohallas->pluck('id')->toArray();
        $availableMohallas = Mohalla::whereHas('village.unionCouncil.tehsil', function($query) use ($localZakatCommittee) {
            $query->where('tehsils.district_id', $localZakatCommittee->district_id);
        })
        ->where('is_active', true)
        ->whereNotIn('id', $attachedMohallaIds)
        ->with('village.unionCouncil.tehsil.district')
        ->get();
        
        // Get hierarchical data for filtering
        $districts = District::where('is_active', true)->get();
        $tehsils = Tehsil::where('is_active', true)->with('district')->get();
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->get();
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->get();
        
        // Get unique genders, verification statuses, statuses, and designations from members for filtering
        $members = $localZakatCommittee->members;
        $uniqueGenders = $members->pluck('gender')->filter()->map(function($gender) {
            return is_string($gender) ? strtolower(trim($gender)) : '';
        })->filter()->unique()->sort()->values();
        $uniqueVerificationStatuses = $members->pluck('verification_status')->filter()->unique()->sort()->values();
        $uniqueStatuses = $members->map(function($member) {
            return $member->is_active ? 'active' : 'inactive';
        })->unique()->sort()->values();
        $uniqueDesignations = $members->pluck('designation')->filter()->unique()->sort()->values();
        
        return view('local-zakat-committees.show', compact(
            'localZakatCommittee', 
            'availableMohallas', 
            'districts', 
            'tehsils', 
            'unionCouncils', 
            'villages',
            'uniqueGenders',
            'uniqueVerificationStatuses',
            'uniqueStatuses',
            'uniqueDesignations'
        ));
    }

    public function edit(LocalZakatCommittee $localZakatCommittee)
    {
        $districts = District::where('is_active', true)->get();
        $tehsils = Tehsil::where('is_active', true)->with('district')->get();
        $unionCouncils = UnionCouncil::where('is_active', true)->with('tehsil.district')->get();
        $villages = Village::where('is_active', true)->with('unionCouncil.tehsil.district')->get();
        $mohallas = Mohalla::where('is_active', true)->with('village.unionCouncil.tehsil.district')->get();
        $localZakatCommittee->load('mohallas');
        return view('local-zakat-committees.edit', compact('localZakatCommittee', 'districts', 'tehsils', 'unionCouncils', 'villages', 'mohallas'));
    }

    public function update(Request $request, LocalZakatCommittee $localZakatCommittee)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'area_coverage' => 'nullable|string',
            'formation_date' => 'required|date',
            'tenure_years' => 'required|integer|min:1',
            'mohalla_ids' => 'required|array|min:1',
            'mohalla_ids.*' => 'exists:mohallas,id',
            'is_active' => 'boolean',
        ]);

        $validated['tenure_end_date'] = date('Y-m-d', strtotime($validated['formation_date'] . ' + ' . $validated['tenure_years'] . ' years'));

        // Generate code if it doesn't exist
        if (!$localZakatCommittee->code) {
            $validated['code'] = LocalZakatCommittee::generateCode($validated['district_id']);
        }

        $localZakatCommittee->update($validated);
        
        // Sync mohallas
        $localZakatCommittee->mohallas()->sync($validated['mohalla_ids']);

        return redirect()->route('local-zakat-committees.index')
            ->with('success', 'Local Zakat Committee updated successfully.');
    }

    public function destroy(LocalZakatCommittee $localZakatCommittee)
    {
        $localZakatCommittee->delete();
        return redirect()->route('local-zakat-committees.index')
            ->with('success', 'Local Zakat Committee deleted successfully.');
    }

    /**
     * Add mohalla to committee via AJAX
     */
    public function addMohalla(Request $request, LocalZakatCommittee $localZakatCommittee)
    {
        $validated = $request->validate([
            'mohalla_id' => 'required|exists:mohallas,id',
        ]);

        // Check if mohalla is already attached
        if ($localZakatCommittee->mohallas()->where('mohallas.id', $validated['mohalla_id'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This mohalla is already attached to this committee.'
            ], 422);
        }

        $localZakatCommittee->mohallas()->attach($validated['mohalla_id']);
        $mohalla = Mohalla::with('village.unionCouncil.tehsil.district')->find($validated['mohalla_id']);

        return response()->json([
            'success' => true,
            'message' => 'Mohalla added successfully.',
            'mohalla' => $mohalla
        ]);
    }

    /**
     * Remove mohalla from committee via AJAX
     */
    public function removeMohalla(Request $request, LocalZakatCommittee $localZakatCommittee)
    {
        $validated = $request->validate([
            'mohalla_id' => 'required|exists:mohallas,id',
        ]);

        $localZakatCommittee->mohallas()->detach($validated['mohalla_id']);

        return response()->json([
            'success' => true,
            'message' => 'Mohalla removed successfully.'
        ]);
    }
}
