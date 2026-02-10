<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use App\Models\Installment;
use App\Models\District;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\DistrictQuota;
use Illuminate\Support\Facades\Schema;

class PhaseController extends Controller
{
    public function __construct()
    {
        // Restrict access for district users
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isDistrictUser()) {
                abort(403, 'Access denied. District users cannot access Phases.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Close expired phases before fetching
        Phase::closeExpiredPhases();
        
        $phases = Phase::with(['installment.fundAllocation.financialYear', 'district', 'scheme'])->orderBy('created_at', 'desc')->get();
        $districts = District::where('is_active', true)->orderBy('name')->get();
        $schemes = \App\Models\Scheme::where('is_active', true)->orderBy('name')->get();
        $financialYears = \App\Models\FinancialYear::orderBy('name', 'desc')->get();
        return view('phases.index', compact('phases', 'districts', 'schemes', 'financialYears'));
    }

    public function create()
    {
        $installments = Installment::with(['fundAllocation.financialYear', 'districtQuotas.district', 'districtQuotas.schemeDistributions.scheme'])
            ->whereHas('fundAllocation', function($query) {
                $query->where('status', '!=', 'completed');
            })
            ->get();
        
        // Get districts that have quotas in at least one installment
        $districts = District::where('is_active', true)
            ->whereHas('districtQuotas')
            ->get();
        
        return view('phases.create', compact('installments', 'districts'));
    }
    
    public function getDistrictsForInstallment(Request $request)
    {
        $installmentId = $request->get('installment_id');
        
        if (!$installmentId) {
            return response()->json(['error' => 'Installment ID is required.'], 400);
        }
        
        $installmentId = (int) $installmentId;
        
        // Get districts that have quotas for this installment
        $districts = District::whereHas('districtQuotas', function($query) use ($installmentId) {
            $query->where('installment_id', $installmentId);
        })
        ->where('is_active', true)
        ->get(['id', 'name']);
        
        return response()->json([
            'districts' => $districts
        ]);
    }
    
    public function getDistrictQuota(Request $request)
    {
        $installmentId = $request->get('installment_id');
        $districtId = $request->get('district_id');
        
        // Ensure we have valid IDs
        if (!$installmentId || !$districtId) {
            return response()->json(['error' => 'Installment ID and District ID are required.'], 400);
        }
        
        // Convert to integers to ensure proper type matching
        $installmentId = (int) $installmentId;
        $districtId = (int) $districtId;
        
        // Check if installment exists
        $installment = Installment::find($installmentId);
        if (!$installment) {
            return response()->json([
                'error' => 'Installment not found.',
                'debug' => ['installment_id' => $installmentId]
            ], 404);
        }
        
        // Debug: Check if any quotas exist for this installment
        $allQuotasForInstallment = DistrictQuota::where('installment_id', $installmentId)->get();
        
        $quota = DistrictQuota::where('installment_id', $installmentId)
            ->where('district_id', $districtId)
            ->first();
        
        if ($quota) {
            $schemeId = $request->get('scheme_id');
            
            // Get schemes for this district quota
            $schemes = $quota->schemeDistributions()->with(['scheme.categories'])->get()->map(function($distribution) {
                $scheme = $distribution->scheme;
                $hasCategories = $scheme && $scheme->categories && $scheme->categories->count() > 0;
                // If scheme has no categories, it's a lump sum scheme
                $isLumpSum = !$hasCategories;
                
                return [
                    'id' => $distribution->scheme_id,
                    'name' => $scheme->name ?? 'Unknown',
                    'percentage' => $distribution->percentage,
                    'amount' => $distribution->amount,
                    'beneficiaries_count' => $distribution->beneficiaries_count,
                    'is_lump_sum' => $isLumpSum,
                    'has_categories' => $hasCategories,
                ];
            });
            
            // If scheme_id is provided, calculate scheme-specific quotas
            if ($schemeId) {
                $schemeId = (int) $schemeId;
                $schemeDistribution = $quota->schemeDistributions()->where('scheme_id', $schemeId)->first();
                
                if ($schemeDistribution) {
                    // Get existing phases for this specific scheme
                    $existingPhases = collect([]);
                    if (Schema::hasColumn('phases', 'installment_id') && Schema::hasColumn('phases', 'scheme_id')) {
                        $existingPhases = Phase::where('installment_id', $installmentId)
                            ->where('district_id', $districtId)
                            ->where('scheme_id', $schemeId)
                            ->get();
                    }
                    
                    $usedBeneficiaries = $existingPhases->sum('max_beneficiaries');
                    $usedAmount = $existingPhases->sum('max_amount');
                    
                    // Get next phase number for this scheme
                    $nextPhaseNumber = $existingPhases->max('phase_number') ? $existingPhases->max('phase_number') + 1 : 1;
                    
                    // Scheme-specific totals
                    $schemeTotalBeneficiaries = $schemeDistribution->beneficiaries_count;
                    $schemeTotalAmount = $schemeDistribution->amount;
                    
                    return response()->json([
                        'total_beneficiaries' => $quota->total_beneficiaries,
                        'total_amount' => $quota->total_amount,
                        'used_beneficiaries' => $usedBeneficiaries,
                        'used_amount' => $usedAmount,
                        'remaining_beneficiaries' => $schemeTotalBeneficiaries - $usedBeneficiaries,
                        'remaining_amount' => $schemeTotalAmount - $usedAmount,
                        'next_phase_number' => $nextPhaseNumber,
                        'schemes' => $schemes,
                        'scheme_specific' => true,
                        'scheme_total_beneficiaries' => $schemeTotalBeneficiaries,
                        'scheme_total_amount' => $schemeTotalAmount,
                    ]);
                }
            }
            
            // Default: Calculate overall district quota (for backward compatibility)
            // Note: Phase number cannot be calculated without a scheme, so return null
            $existingPhases = collect([]);
            if (Schema::hasColumn('phases', 'installment_id')) {
                $existingPhases = Phase::where('installment_id', $installmentId)
                    ->where('district_id', $districtId)
                    ->get();
            }
            
            $usedBeneficiaries = $existingPhases->sum('max_beneficiaries');
            $usedAmount = $existingPhases->sum('max_amount');
            
            return response()->json([
                'total_beneficiaries' => $quota->total_beneficiaries,
                'total_amount' => $quota->total_amount,
                'used_beneficiaries' => $usedBeneficiaries,
                'used_amount' => $usedAmount,
                'remaining_beneficiaries' => $quota->total_beneficiaries - $usedBeneficiaries,
                'remaining_amount' => $quota->total_amount - $usedAmount,
                'next_phase_number' => null, // Cannot calculate without scheme
                'schemes' => $schemes,
                'scheme_specific' => false,
            ]);
        }
        
        // Return more detailed error message for debugging
        $availableDistricts = $allQuotasForInstallment->pluck('district_id')->toArray();
        $availableDistrictNames = $allQuotasForInstallment->map(function($q) {
            return $q->district ? $q->district->name : 'Unknown';
        })->toArray();
        
        return response()->json([
            'error' => 'No quota found for this installment and district combination.',
            'debug' => [
                'installment_id' => $installmentId,
                'installment_number' => $installment ? $installment->installment_number : 'N/A',
                'district_id' => $districtId,
                'total_quotas_for_installment' => $allQuotasForInstallment->count(),
                'available_district_ids' => $availableDistricts,
                'available_district_names' => $availableDistrictNames
            ]
        ], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'district_id' => 'required|exists:districts,id',
            'scheme_id' => 'required|exists:schemes,id',
            'name' => 'nullable|string|max:255',
            'phase_number' => 'required|integer|min:1',
            'max_beneficiaries' => 'required|integer|min:1',
            'max_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:open,closed',
        ]);

        // Get installment, district, and scheme for auto-generating name
        $installment = Installment::findOrFail($validated['installment_id']);
        $district = District::findOrFail($validated['district_id']);
        $scheme = \App\Models\Scheme::findOrFail($validated['scheme_id']);
        
        // Get district quota and scheme distribution to calculate max_amount
        $districtQuota = \App\Models\DistrictQuota::where('installment_id', $validated['installment_id'])
            ->where('district_id', $validated['district_id'])
            ->first();
        
        if (!$districtQuota) {
            return back()->withErrors(['district_id' => 'No quota found for this installment and district.'])->withInput();
        }
        
        $schemeDistribution = $districtQuota->schemeDistributions()
            ->where('scheme_id', $validated['scheme_id'])
            ->first();
        
        if (!$schemeDistribution) {
            return back()->withErrors(['scheme_id' => 'No scheme distribution found for this district quota.'])->withInput();
        }
        
        // Check if scheme has categories - if not, it's a lump sum scheme
        $scheme->load('categories');
        $hasCategories = $scheme->categories && $scheme->categories->count() > 0;
        $isLumpSum = !$hasCategories;
        
        // Calculate max_amount based on scheme type
        if ($isLumpSum) {
            // For lump sum schemes (no categories), max_amount is required and provided by user
            if (!isset($validated['max_amount']) || $validated['max_amount'] <= 0) {
                return back()->withErrors(['max_amount' => 'Max amount is required for lump sum schemes (schemes without categories).'])->withInput();
            }
        } else {
            // For schemes with categories, calculate max_amount based on max_beneficiaries and scheme's per-beneficiary amount
            $amountPerBeneficiary = $schemeDistribution->beneficiaries_count > 0 
                ? ($schemeDistribution->amount / $schemeDistribution->beneficiaries_count) 
                : 0;
            
            $validated['max_amount'] = $amountPerBeneficiary * $validated['max_beneficiaries'];
        }

        // Check if phase already exists
        $exists = Phase::where('installment_id', $validated['installment_id'])
            ->where('district_id', $validated['district_id'])
            ->where('scheme_id', $validated['scheme_id'])
            ->where('phase_number', $validated['phase_number'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['phase_number' => 'Phase number already exists for this installment, district, and scheme combination.'])->withInput();
        }

        // Auto-generate name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = Phase::generateName($installment, $district, $validated['phase_number'], $scheme);
        }

        // Get all existing phases for this specific scheme, district, and installment
        $existingPhases = Phase::where('installment_id', $validated['installment_id'])
            ->where('district_id', $validated['district_id'])
            ->where('scheme_id', $validated['scheme_id'])
            ->get();

        $totalMaxBeneficiaries = $existingPhases->sum('max_beneficiaries') + $validated['max_beneficiaries'];
        $totalMaxAmount = $existingPhases->sum('max_amount') + $validated['max_amount'];

        // Validate against scheme-specific quota
        if ($totalMaxBeneficiaries > $schemeDistribution->beneficiaries_count) {
            return back()->withErrors(['max_beneficiaries' => 'Combined phases exceed scheme quota of ' . number_format($schemeDistribution->beneficiaries_count) . ' beneficiaries.'])->withInput();
        }

        if ($totalMaxAmount > $schemeDistribution->amount) {
            return back()->withErrors(['max_beneficiaries' => 'Combined phases exceed scheme quota amount of Rs. ' . number_format($schemeDistribution->amount, 2) . '.'])->withInput();
        }

        $phase = Phase::create($validated);

        // Notify district users about phase creation
        $this->notifyDistrictUsers($phase, 'created', 'Phase Created', 'A new phase "' . $phase->name . '" has been created for your district.');

        return redirect()->route('phases.index')
            ->with('success', 'Phase created successfully.');
    }

    public function show(Phase $phase)
    {
        // Close expired phases before loading
        Phase::closeExpiredPhases();
        
        // Reload phase to get updated status if it was just closed
        $phase->refresh();
        $phase->load([
            'installment.fundAllocation.financialYear', 
            'district', 
            'scheme.categories', 
            'beneficiaries.scheme', 
            'beneficiaries.localZakatCommittee',
            'beneficiaries.schemeCategory',
            'beneficiaries.representative'
        ]);
        
        // Get current beneficiaries count and amount
        $currentBeneficiariesCount = $phase->beneficiaries()
            ->whereIn('status', ['submitted', 'approved', 'paid'])
            ->count();
        $currentAmount = $phase->beneficiaries()
            ->whereIn('status', ['submitted', 'approved', 'paid'])
            ->sum('amount');
        
        // Get schemes and committees for adding beneficiaries
        $user = auth()->user();
        $schemes = \App\Models\Scheme::where('is_active', true)->with('categories')->get();
        
        $committeesQuery = \App\Models\LocalZakatCommittee::where('is_active', true)->with('district');
        if ($user->isDistrictUser() && $user->district_id) {
            $committeesQuery->where('district_id', $user->district_id);
        }
        $committees = $committeesQuery->get();
        
        // Get institutions for institutional schemes
        $institutions = \App\Models\Institution::where('is_active', true)
            ->with(['district', 'tehsil', 'unionCouncil', 'village', 'mohalla'])
            ->orderBy('name')
            ->get();
        
        // Get unique schemes, categories, and statuses from beneficiaries for filtering
        $beneficiaries = $phase->beneficiaries;
        $uniqueSchemes = $beneficiaries->pluck('scheme')->filter()->unique('id')->pluck('name')->sort()->values();
        $uniqueCategories = $beneficiaries->pluck('schemeCategory')->filter()->unique('id')->pluck('name')->sort()->values();
        $uniqueCommittees = $beneficiaries->pluck('localZakatCommittee')->filter()->unique('id')->pluck('name')->sort()->values();
        $uniqueStatuses = $beneficiaries->pluck('status')->filter()->unique()->sort()->values();
        
        return view('phases.show', compact(
            'phase', 
            'currentBeneficiariesCount', 
            'currentAmount', 
            'schemes', 
            'committees',
            'institutions',
            'uniqueSchemes',
            'uniqueCategories',
            'uniqueCommittees',
            'uniqueStatuses'
        ));
    }

    public function edit(Phase $phase)
    {
        // Close expired phases before loading
        Phase::closeExpiredPhases();
        
        // Reload phase to get updated status if it was just closed
        $phase->refresh();
        $phase->load(['installment.fundAllocation.financialYear', 'district', 'scheme']);
        $installments = Installment::with(['fundAllocation.financialYear', 'districtQuotas.district', 'districtQuotas.schemeDistributions.scheme'])
            ->whereHas('fundAllocation', function($query) {
                $query->where('status', '!=', 'completed');
            })
            ->get();
        
        // Get districts that have quotas in at least one installment
        $districts = District::where('is_active', true)
            ->whereHas('districtQuotas')
            ->get();
            
        return view('phases.edit', compact('phase', 'installments', 'districts'));
    }

    public function update(Request $request, Phase $phase)
    {
        $validated = $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'district_id' => 'required|exists:districts,id',
            'scheme_id' => 'required|exists:schemes,id',
            'name' => 'nullable|string|max:255',
            'phase_number' => 'required|integer|min:1',
            'max_beneficiaries' => 'required|integer|min:1',
            'max_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:open,closed',
        ]);

        // Get installment, district, and scheme for auto-generating name
        $installment = Installment::findOrFail($validated['installment_id']);
        $district = District::findOrFail($validated['district_id']);
        $scheme = \App\Models\Scheme::findOrFail($validated['scheme_id']);
        
        // Get district quota and scheme distribution to calculate max_amount
        $districtQuota = \App\Models\DistrictQuota::where('installment_id', $validated['installment_id'])
            ->where('district_id', $validated['district_id'])
            ->first();
        
        if (!$districtQuota) {
            return back()->withErrors(['district_id' => 'No quota found for this installment and district.'])->withInput();
        }
        
        $schemeDistribution = $districtQuota->schemeDistributions()
            ->where('scheme_id', $validated['scheme_id'])
            ->first();
        
        if (!$schemeDistribution) {
            return back()->withErrors(['scheme_id' => 'No scheme distribution found for this district quota.'])->withInput();
        }
        
        // Check if scheme has categories - if not, it's a lump sum scheme
        $scheme->load('categories');
        $hasCategories = $scheme->categories && $scheme->categories->count() > 0;
        $isLumpSum = !$hasCategories;
        
        // Calculate max_amount based on scheme type
        if ($isLumpSum) {
            // For lump sum schemes (no categories), max_amount is required and provided by user
            if (!isset($validated['max_amount']) || $validated['max_amount'] <= 0) {
                return back()->withErrors(['max_amount' => 'Max amount is required for lump sum schemes (schemes without categories).'])->withInput();
            }
        } else {
            // For schemes with categories, calculate max_amount based on max_beneficiaries and scheme's per-beneficiary amount
            $amountPerBeneficiary = $schemeDistribution->beneficiaries_count > 0 
                ? ($schemeDistribution->amount / $schemeDistribution->beneficiaries_count) 
                : 0;
            
            $validated['max_amount'] = $amountPerBeneficiary * $validated['max_beneficiaries'];
        }

        // Automatically set status to closed if end_date is in the past
        if (!empty($validated['end_date']) && $validated['end_date'] < now()->toDateString()) {
            $validated['status'] = 'closed';
        }

        // Check if phase number already exists (excluding current phase)
        $exists = Phase::where('installment_id', $validated['installment_id'])
            ->where('district_id', $validated['district_id'])
            ->where('scheme_id', $validated['scheme_id'])
            ->where('phase_number', $validated['phase_number'])
            ->where('id', '!=', $phase->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['phase_number' => 'Phase number already exists for this installment, district, and scheme combination.'])->withInput();
        }

        // Auto-generate name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = Phase::generateName($installment, $district, $validated['phase_number'], $scheme);
        }

        // Get all existing phases for this specific scheme, district, and installment (excluding current phase)
        $existingPhases = Phase::where('installment_id', $validated['installment_id'])
            ->where('district_id', $validated['district_id'])
            ->where('scheme_id', $validated['scheme_id'])
            ->where('id', '!=', $phase->id)
            ->get();

        $totalMaxBeneficiaries = $existingPhases->sum('max_beneficiaries') + $validated['max_beneficiaries'];
        $totalMaxAmount = $existingPhases->sum('max_amount') + $validated['max_amount'];

        // Validate against scheme-specific quota
        if ($totalMaxBeneficiaries > $schemeDistribution->beneficiaries_count) {
            return back()->withErrors(['max_beneficiaries' => 'Combined phases exceed scheme quota of ' . number_format($schemeDistribution->beneficiaries_count) . ' beneficiaries.'])->withInput();
        }

        if ($totalMaxAmount > $schemeDistribution->amount) {
            return back()->withErrors(['max_beneficiaries' => 'Combined phases exceed scheme quota amount of Rs. ' . number_format($schemeDistribution->amount, 2) . '.'])->withInput();
        }

        // Check if status changed
        $oldStatus = $phase->status;
        $statusChanged = $oldStatus !== $validated['status'];
        
        $phase->update($validated);
        $phase->refresh();

        // Notify district users about phase update
        if ($statusChanged) {
            $statusText = $validated['status'] === 'open' ? 'opened' : 'closed';
            $this->notifyDistrictUsers($phase, 'status_changed', 'Phase Status Changed', 'Phase "' . $phase->name . '" has been ' . $statusText . ' for your district.');
        } else {
            $this->notifyDistrictUsers($phase, 'updated', 'Phase Updated', 'Phase "' . $phase->name . '" has been updated for your district.');
        }

        return redirect()->route('phases.index')
            ->with('success', 'Phase updated successfully.');
    }

    public function destroy(Phase $phase)
    {
        $phaseName = $phase->name;
        $districtId = $phase->district_id;
        
        $phase->delete();
        
        // Notify district users about phase deletion
        $districtUsers = User::where('role', 'district_user')
            ->where('district_id', $districtId)
            ->get();
        
        foreach ($districtUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'phase_deleted',
                'title' => 'Phase Deleted',
                'message' => 'Phase "' . $phaseName . '" has been deleted.',
                'notifiable_type' => Phase::class,
                'notifiable_id' => null, // Phase is deleted, so no ID
            ]);
        }

        return redirect()->route('phases.index')
            ->with('success', 'Phase deleted successfully.');
    }

    /**
     * Notify all district users for a given phase
     */
    private function notifyDistrictUsers(Phase $phase, string $type, string $title, string $message)
    {
        $districtUsers = User::where('role', 'district_user')
            ->where('district_id', $phase->district_id)
            ->get();
        
        foreach ($districtUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'phase_' . $type,
                'title' => $title,
                'message' => $message,
                'notifiable_type' => Phase::class,
                'notifiable_id' => $phase->id,
            ]);
        }
    }
}
