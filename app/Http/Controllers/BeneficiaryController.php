<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Phase;
use App\Models\Scheme;
use App\Models\SchemeCategory;
use App\Models\LocalZakatCommittee;
use App\Models\LZCMember;
use App\Models\BeneficiaryRepresentative;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Beneficiary::with(['phase.district', 'phase.installment.fundAllocation.financialYear', 'scheme', 'localZakatCommittee']);

        if ($user->isDistrictUser() && $user->district_id) {
            $query->whereHas('phase', function($q) use ($user) {
                $q->where('district_id', $user->district_id);
            });
        }

        $beneficiaries = $query->orderBy('created_at', 'desc')->get();
        
        // Get schemes and committees for modals
        $schemes = Scheme::where('is_active', true)->with('categories')->get();
        
        $committeesQuery = LocalZakatCommittee::where('is_active', true)->with('district');
        if ($user->isDistrictUser() && $user->district_id) {
            $committeesQuery->where('district_id', $user->district_id);
        }
        $committees = $committeesQuery->get();
        
        // Get filter options
        $districtsQuery = \App\Models\District::where('is_active', true)->orderBy('name');
        if ($user->isDistrictUser() && $user->district_id) {
            $districtsQuery->where('id', $user->district_id);
        }
        $districts = $districtsQuery->get();
        
        $financialYears = \App\Models\FinancialYear::orderBy('name', 'desc')->get();
        
        return view('beneficiaries.index', compact('beneficiaries', 'schemes', 'committees', 'districts', 'financialYears'));
    }

    public function applications()
    {
        // Close expired phases before fetching
        Phase::closeExpiredPhases();
        
        $user = auth()->user();
        $phasesQuery = Phase::where('status', 'open')
            ->with(['installment.fundAllocation.financialYear', 'district', 'scheme']);

        if ($user->isDistrictUser() && $user->district_id) {
            $phasesQuery->where('district_id', $user->district_id);
        }

        $phases = $phasesQuery->orderBy('start_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($phase) {
                $phase->current_beneficiaries_count = $phase->beneficiaries()
                    ->whereIn('status', ['submitted', 'approved', 'paid'])
                    ->count();
                $phase->current_amount = $phase->beneficiaries()
                    ->whereIn('status', ['submitted', 'approved', 'paid'])
                    ->sum('amount');
                $phase->remaining_beneficiaries = $phase->max_beneficiaries - $phase->current_beneficiaries_count;
                $phase->remaining_amount = $phase->max_amount - $phase->current_amount;
                return $phase;
            });

        // Get filter options
        $districtsQuery = \App\Models\District::where('is_active', true)->orderBy('name');
        if ($user->isDistrictUser() && $user->district_id) {
            $districtsQuery->where('id', $user->district_id);
        }
        $districts = $districtsQuery->get();

        $schemes = \App\Models\Scheme::where('is_active', true)->orderBy('name')->get();
        
        $financialYears = \App\Models\FinancialYear::orderBy('name', 'desc')->get();

        return view('beneficiaries.applications', compact('phases', 'districts', 'schemes', 'financialYears'));
    }

    public function phaseApplications(Phase $phase)
    {
        // Close expired phases before loading
        Phase::closeExpiredPhases();
        
        // Check if user has access to this phase
        $user = auth()->user();
        if ($user->isDistrictUser() && $user->district_id && $phase->district_id != $user->district_id) {
            abort(403, 'You do not have access to this phase.');
        }

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
        $schemes = Scheme::where('is_active', true)->with('categories')->get();

        $committeesQuery = LocalZakatCommittee::where('is_active', true)->with('district');
        if ($user->isDistrictUser() && $user->district_id) {
            $committeesQuery->where('district_id', $user->district_id);
        } else {
            $committeesQuery->where('district_id', $phase->district_id);
        }
        $committees = $committeesQuery->get();

        // Get unique schemes, categories, committees, and statuses from beneficiaries for filtering
        $beneficiaries = $phase->beneficiaries;
        $uniqueSchemes = $beneficiaries->pluck('scheme')->filter()->unique('id')->pluck('name')->sort()->values();
        $uniqueCategories = $beneficiaries->pluck('schemeCategory')->filter()->unique('id')->pluck('name')->sort()->values();
        $uniqueCommittees = $beneficiaries->pluck('localZakatCommittee')->filter()->unique('id')->pluck('name')->sort()->values();
        $uniqueStatuses = $beneficiaries->pluck('status')->filter()->unique()->sort()->values();

        return view('beneficiaries.phase-applications', compact(
            'phase', 
            'currentBeneficiariesCount', 
            'currentAmount', 
            'schemes', 
            'committees',
            'uniqueSchemes',
            'uniqueCategories',
            'uniqueCommittees',
            'uniqueStatuses'
        ));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $phasesQuery = Phase::where('status', 'open');

        if ($user->isDistrictUser() && $user->district_id) {
            $phasesQuery->where('district_id', $user->district_id);
        }

        $phases = $phasesQuery->with(['installment.fundAllocation.financialYear', 'district', 'scheme'])->get();
        $schemes = Scheme::where('is_active', true)->with('categories')->get();
        
        $committeesQuery = LocalZakatCommittee::where('is_active', true);
        if ($user->isDistrictUser() && $user->district_id) {
            $committeesQuery->where('district_id', $user->district_id);
        }
        $committees = $committeesQuery->get();

        // Pre-select phase if coming from applications page
        $selectedPhaseId = $request->get('phase_id');

        return view('beneficiaries.create', compact('phases', 'schemes', 'committees', 'selectedPhaseId'));
    }
    
    public function getSchemeCategories(Scheme $scheme)
    {
        $categories = $scheme->categories;
        return response()->json($categories);
    }

    public function getSchemeRemainingAmount(Request $request)
    {
        $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'scheme_id' => 'required|exists:schemes,id',
            'beneficiary_id' => 'nullable|exists:beneficiaries,id', // Optional: for edit mode
        ]);

        $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($request->phase_id);
        $scheme = Scheme::with('categories')->findOrFail($request->scheme_id);
        $financialYear = $phase->installment->fundAllocation->financialYear;

        // Check if it's a lump sum scheme
        $isLumpSum = $scheme->categories->isEmpty();

        if (!$isLumpSum) {
            return response()->json([
                'success' => false,
                'message' => 'This endpoint is only for lump sum schemes.'
            ], 400);
        }

        if ($financialYear) {
            // Get all phases for this scheme in the same financial year
            $phasesForScheme = Phase::where('scheme_id', $request->scheme_id)
                ->whereHas('installment.fundAllocation', function($query) use ($financialYear) {
                    $query->where('financial_year_id', $financialYear->id);
                })
                ->get();

            // Calculate total max amount allowed for this scheme (sum of max_amount for all phases)
            $totalMaxAmount = $phasesForScheme->sum('max_amount');

            // Calculate current total amount given to beneficiaries for this scheme across all phases
            $phaseIdsForScheme = $phasesForScheme->pluck('id');
            $beneficiaryQuery = Beneficiary::whereIn('phase_id', $phaseIdsForScheme)
                ->whereIn('status', ['submitted', 'approved', 'paid']);
            
            // If editing, exclude the current beneficiary's amount from the calculation
            if ($request->beneficiary_id) {
                $beneficiaryQuery->where('id', '!=', $request->beneficiary_id);
            }
            
            $currentTotalAmount = $beneficiaryQuery->sum('amount');

            $remainingAmount = $totalMaxAmount - $currentTotalAmount;

            return response()->json([
                'success' => true,
                'total_max_amount' => $totalMaxAmount,
                'current_total_amount' => $currentTotalAmount,
                'remaining_amount' => $remainingAmount,
                'financial_year' => $financialYear->name,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Financial year not found for this phase.'
        ], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'scheme_id' => 'required|exists:schemes,id',
            'scheme_category_id' => 'nullable|exists:scheme_categories,id',
            'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
            'cnic' => [
                'required',
                'string',
                Rule::unique('beneficiaries')->where(function ($query) use ($request) {
                    return $query->where('phase_id', $request->phase_id)
                                 ->where('scheme_id', $request->scheme_id);
                }),
            ],
            'full_name' => 'required|string|max:255',
            'father_husband_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'amount' => 'required|numeric|min:0',
            'requires_representative' => 'boolean',
            'representative' => 'nullable|array',
            'representative.cnic' => 'nullable|string',
            'representative.full_name' => 'nullable|string|max:255',
            'representative.father_husband_name' => 'nullable|string|max:255',
            'representative.mobile_number' => 'nullable|string|max:20',
            'representative.date_of_birth' => 'nullable|date',
            'representative.gender' => 'nullable|in:male,female,other',
            'representative.relationship' => 'nullable|string|max:255',
        ]);

        // Check if beneficiary is a committee member
        $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($validated['phase_id']);
        
        // Check 1: Check if CNIC matches any active LZC member (regardless of committee)
        $activeLZCMember = LZCMember::where('cnic', $validated['cnic'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->first();

        if ($activeLZCMember) {
            return response()->json([
                'success' => false,
                'message' => 'Active LZC Members are not eligible for any scheme. The member must be inactive to be eligible.',
                'errors' => ['cnic' => ['Active LZC Members are not eligible for any scheme. The member must be inactive to be eligible.']]
            ], 422);
        }

        // Check 2: Check if CNIC already exists in any scheme for the same financial year
        $financialYear = $phase->installment->fundAllocation->financialYear;
        
        if ($financialYear) {
            // Normalize CNIC for comparison (remove dashes and spaces)
            $inputCnic = trim($validated['cnic']);
            $normalizedInputCnic = str_replace(['-', ' '], '', $inputCnic);
            
            // Get all phases in the same financial year
            $phasesInFinancialYear = Phase::whereHas('installment.fundAllocation', function($query) use ($financialYear) {
                $query->where('financial_year_id', $financialYear->id);
            })->pluck('id');
            
            // Check if CNIC already exists in any beneficiary in any phase of this financial year
            // Compare normalized CNICs (handle both with and without dashes)
            // Check ALL statuses - if a beneficiary exists with any status, they can't register again
            $existingBeneficiaryInFinancialYear = Beneficiary::whereIn('phase_id', $phasesInFinancialYear)
                ->where('id', '!=', $request->id ?? 0) // Exclude current beneficiary if updating
                ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                    $query->where('cnic', $inputCnic)
                          ->orWhere('cnic', $normalizedInputCnic)
                          ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                          ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                })
                ->exists();

            if ($existingBeneficiaryInFinancialYear) {
                // Get the existing beneficiary details for better error message
                $existingBeneficiary = Beneficiary::whereIn('phase_id', $phasesInFinancialYear)
                    ->where('id', '!=', $request->id ?? 0)
                    ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                        $query->where('cnic', $inputCnic)
                              ->orWhere('cnic', $normalizedInputCnic)
                              ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                              ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                    })
                    ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                    ->orderBy('created_at', 'desc') // Get the most recent one
                    ->first();
                
                $existingSchemeName = $existingBeneficiary && $existingBeneficiary->scheme 
                    ? $existingBeneficiary->scheme->name 
                    : 'a scheme';
                
                $existingPhaseName = $existingBeneficiary && $existingBeneficiary->phase 
                    ? $existingBeneficiary->phase->name 
                    : 'N/A';
                
                $existingDistrictName = $existingBeneficiary && $existingBeneficiary->phase && $existingBeneficiary->phase->district
                    ? $existingBeneficiary->phase->district->name 
                    : 'N/A';
                
                $existingCommitteeName = $existingBeneficiary && $existingBeneficiary->localZakatCommittee
                    ? $existingBeneficiary->localZakatCommittee->name 
                    : 'N/A';
                
                $existingStatus = $existingBeneficiary ? ucfirst($existingBeneficiary->status) : 'N/A';
                
                return response()->json([
                    'success' => false,
                    'error_type' => 'duplicate_cnic',
                    'message' => 'This CNIC has already been registered in ' . $existingSchemeName . ' for the financial year ' . $financialYear->name . '. A beneficiary can only be registered in one scheme per financial year.',
                    'duplicate_details' => [
                        'cnic' => $existingBeneficiary ? $existingBeneficiary->cnic : $validated['cnic'],
                        'full_name' => $existingBeneficiary ? $existingBeneficiary->full_name : 'N/A',
                        'scheme_name' => $existingSchemeName,
                        'phase_name' => $existingPhaseName,
                        'district_name' => $existingDistrictName,
                        'committee_name' => $existingCommitteeName,
                        'status' => $existingStatus,
                        'financial_year' => $financialYear->name,
                        'registered_date' => $existingBeneficiary && $existingBeneficiary->created_at 
                            ? $existingBeneficiary->created_at->format('d M Y, h:i A') 
                            : 'N/A',
                    ],
                    'errors' => ['cnic' => ['This CNIC has already been registered in ' . $existingSchemeName . ' for the financial year ' . $financialYear->name . '. A beneficiary can only be registered in one scheme per financial year.']]
                ], 422);
            }
        }

        // Check if beneficiary already exists in this specific phase (additional check)
        $existingBeneficiaryInPhase = Beneficiary::where('cnic', $validated['cnic'])
            ->where('phase_id', $validated['phase_id'])
            ->where('id', '!=', $request->id ?? 0)
            ->exists();

        if ($existingBeneficiaryInPhase) {
            return response()->json([
                'success' => false,
                'message' => 'This beneficiary is already registered in this phase.',
                'errors' => ['cnic' => ['This beneficiary is already registered in this phase.']]
            ], 422);
        }

        // Check age restrictions and automatically determine if representative is required
        $scheme = Scheme::with('categories')->findOrFail($validated['scheme_id']);
        $age = Carbon::parse($validated['date_of_birth'])->age;
        
        // Auto-set amount from scheme category if selected
        if ($validated['scheme_category_id']) {
            $category = SchemeCategory::findOrFail($validated['scheme_category_id']);
            $validated['amount'] = $category->amount;
        }
        
        // Check 3: Check if total beneficiaries for this scheme exceed the sum of max_beneficiaries across all phases
        if ($financialYear) {
            // Get all phases for this scheme in the same financial year
            $phasesForScheme = Phase::where('scheme_id', $validated['scheme_id'])
                ->whereHas('installment.fundAllocation', function($query) use ($financialYear) {
                    $query->where('financial_year_id', $financialYear->id);
                })
                ->get();
            
            // Calculate total max beneficiaries allowed for this scheme (sum of max_beneficiaries for all phases)
            $totalMaxBeneficiaries = $phasesForScheme->sum('max_beneficiaries');
            
            // Calculate current total beneficiaries for this scheme across all phases
            $phaseIdsForScheme = $phasesForScheme->pluck('id');
            $currentTotalBeneficiaries = Beneficiary::whereIn('phase_id', $phaseIdsForScheme)
                ->whereIn('status', ['submitted', 'approved', 'paid'])
                ->where('id', '!=', $request->id ?? 0) // Exclude current beneficiary if updating
                ->count();
            
            // Check if adding this beneficiary would exceed the limit
            if (($currentTotalBeneficiaries + 1) > $totalMaxBeneficiaries) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total beneficiaries for ' . $scheme->name . ' scheme cannot exceed ' . number_format($totalMaxBeneficiaries) . ' beneficiaries across all phases in the financial year ' . $financialYear->name . '. Current count: ' . number_format($currentTotalBeneficiaries) . ', Remaining: ' . number_format($totalMaxBeneficiaries - $currentTotalBeneficiaries) . '.',
                    'errors' => ['scheme_id' => ['Total beneficiaries for ' . $scheme->name . ' scheme cannot exceed ' . number_format($totalMaxBeneficiaries) . ' beneficiaries across all phases in the financial year ' . $financialYear->name . '.']]
                ], 422);
            }
            
            // Check 4: For lump sum schemes (no categories), check if total amount exceeds sum of max_amount
            $isLumpSum = $scheme->categories->isEmpty();
            
            if ($isLumpSum) {
                // Calculate total max amount allowed for this scheme (sum of max_amount for all phases)
                $totalMaxAmount = $phasesForScheme->sum('max_amount');
                
                // Calculate current total amount given to beneficiaries for this scheme across all phases
                $currentTotalAmount = Beneficiary::whereIn('phase_id', $phaseIdsForScheme)
                    ->whereIn('status', ['submitted', 'approved', 'paid'])
                    ->where('id', '!=', $request->id ?? 0) // Exclude current beneficiary if updating
                    ->sum('amount');
                
                // Check if adding this beneficiary's amount would exceed the limit
                if (($currentTotalAmount + $validated['amount']) > $totalMaxAmount) {
                    $remainingAmount = $totalMaxAmount - $currentTotalAmount;
                    return response()->json([
                        'success' => false,
                        'message' => 'Total amount for ' . $scheme->name . ' scheme (lump sum) cannot exceed Rs. ' . number_format($totalMaxAmount, 2) . ' across all phases in the financial year ' . $financialYear->name . '. Current total: Rs. ' . number_format($currentTotalAmount, 2) . ', Remaining: Rs. ' . number_format($remainingAmount, 2) . '.',
                        'errors' => ['amount' => ['Total amount for ' . $scheme->name . ' scheme (lump sum) cannot exceed Rs. ' . number_format($totalMaxAmount, 2) . ' across all phases. Remaining: Rs. ' . number_format($remainingAmount, 2) . '.']]
                    ], 422);
                }
            }
        }
        
        // Automatically determine if representative is required based on age
        // If age < 18, representative is mandatory
        $requiresRepresentative = $age < 18 ? 1 : 0;
        $validated['requires_representative'] = $requiresRepresentative;
        
        // If representative is required (age < 18), validate representative fields
        if ($requiresRepresentative) {
            if (!$request->has('representative') || empty($request->representative)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Representative information is required for beneficiaries under 18 years of age.',
                    'errors' => ['representative' => ['Representative information is required for beneficiaries under 18 years of age.']]
                ], 422);
            }
            
            // Validate representative fields
            $representativeRules = [
                'representative.cnic' => 'required|string',
                'representative.full_name' => 'required|string|max:255',
                'representative.father_husband_name' => 'required|string|max:255',
                'representative.date_of_birth' => 'required|date',
                'representative.gender' => 'required|in:male,female,other',
                'representative.relationship' => 'required|string|max:255',
            ];
            
            $representativeValidator = \Validator::make($request->all(), $representativeRules);
            if ($representativeValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please fill all required representative fields.',
                    'errors' => $representativeValidator->errors()
                ], 422);
            }
        }
        
        // Check scheme age restriction
        if ($scheme->has_age_restriction && $age < $scheme->minimum_age) {
            return response()->json([
                'success' => false,
                'message' => 'This scheme requires minimum age of ' . $scheme->minimum_age . ' years.',
                'errors' => ['date_of_birth' => ['This scheme requires minimum age of ' . $scheme->minimum_age . ' years.']]
            ], 422);
        }

        // Check phase limits
        $phase = Phase::findOrFail($validated['phase_id']);
        $currentCount = $phase->beneficiaries()->whereIn('status', ['submitted', 'approved', 'paid'])->count();
        $currentAmount = $phase->beneficiaries()->whereIn('status', ['submitted', 'approved', 'paid'])->sum('amount');

        if ($currentCount >= $phase->max_beneficiaries) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum beneficiaries limit reached for this phase.',
                'errors' => ['phase_id' => ['Maximum beneficiaries limit reached for this phase.']]
            ], 422);
        }

        if (($currentAmount + $validated['amount']) > $phase->max_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum amount limit will be exceeded for this phase.',
                'errors' => ['amount' => ['Maximum amount limit will be exceeded for this phase.']]
            ], 422);
        }

        $validated['submitted_by'] = auth()->id();
        $validated['submitted_at'] = now();
        $validated['status'] = 'submitted';

        $beneficiary = Beneficiary::create($validated);

        // Create representative only if required (age < 18) and representative data is provided
        if ($requiresRepresentative && $request->has('representative') && !empty($request->representative)) {
            // Double-check that representative data is not empty
            $repData = $request->representative;
            if (!empty($repData['cnic']) && !empty($repData['full_name']) && !empty($repData['father_husband_name'])) {
                BeneficiaryRepresentative::create([
                    'beneficiary_id' => $beneficiary->id,
                    'cnic' => $repData['cnic'],
                    'full_name' => $repData['full_name'],
                    'father_husband_name' => $repData['father_husband_name'],
                    'mobile_number' => $repData['mobile_number'] ?? null,
                    'date_of_birth' => $repData['date_of_birth'],
                    'gender' => $repData['gender'],
                    'relationship' => $repData['relationship'],
                ]);
            }
        }

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary registered successfully.');
    }

    public function show(Beneficiary $beneficiary)
    {
        $beneficiary->load(['phase', 'scheme', 'schemeCategory', 'localZakatCommittee', 'representative', 'submittedBy', 'approvedBy']);
        return view('beneficiaries.show', compact('beneficiary'));
    }

    public function edit(Beneficiary $beneficiary)
    {
        // Redirect to index page with beneficiary ID to open edit modal
        return redirect()->route('beneficiaries.index', ['edit' => $beneficiary->id]);
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        $validated = $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'scheme_id' => 'required|exists:schemes,id',
            'scheme_category_id' => 'nullable|exists:scheme_categories,id',
            'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
            'cnic' => [
                'required',
                'string',
                Rule::unique('beneficiaries')->where(function ($query) use ($request, $beneficiary) {
                    return $query->where('phase_id', $request->phase_id)
                                 ->where('scheme_id', $request->scheme_id)
                                 ->where('id', '!=', $beneficiary->id);
                }),
            ],
            'full_name' => 'required|string|max:255',
            'father_husband_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'amount' => 'required|numeric|min:0',
            'district_remarks' => 'nullable|string',
        ]);

        if ($beneficiary->status === 'pending' || $beneficiary->status === 'rejected') {
            $beneficiary->update($validated);
            return redirect()->route('beneficiaries.index')
                ->with('success', 'Beneficiary updated successfully.');
        }

        return back()->withErrors(['status' => 'Cannot update beneficiary in current status.']);
    }

    public function destroy(Beneficiary $beneficiary)
    {
        if (in_array($beneficiary->status, ['approved', 'paid'])) {
            return back()->withErrors(['status' => 'Cannot delete approved or paid beneficiary.']);
        }

        $beneficiary->delete();
        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary deleted successfully.');
    }

    public function getDetails(Beneficiary $beneficiary)
    {
        $beneficiary->load(['scheme', 'schemeCategory', 'localZakatCommittee', 'representative', 'phase.district']);
        return response()->json([
            'success' => true,
            'beneficiary' => [
                'id' => $beneficiary->id,
                'cnic' => $beneficiary->cnic,
                'full_name' => $beneficiary->full_name,
                'father_husband_name' => $beneficiary->father_husband_name,
                'mobile_number' => $beneficiary->mobile_number,
                'date_of_birth' => $beneficiary->date_of_birth ? $beneficiary->date_of_birth->format('Y-m-d') : null,
                'gender' => $beneficiary->gender,
                'amount' => $beneficiary->amount,
                'status' => $beneficiary->status,
                'scheme_id' => $beneficiary->scheme_id,
                'scheme_name' => $beneficiary->scheme->name ?? 'N/A',
                'scheme_category_id' => $beneficiary->scheme_category_id,
                'scheme_category_name' => $beneficiary->schemeCategory->name ?? 'N/A',
                'local_zakat_committee_id' => $beneficiary->local_zakat_committee_id,
                'local_zakat_committee_name' => $beneficiary->localZakatCommittee->name ?? 'N/A',
                'requires_representative' => $beneficiary->requires_representative,
                'representative' => $beneficiary->representative ? [
                    'cnic' => $beneficiary->representative->cnic,
                    'full_name' => $beneficiary->representative->full_name,
                    'father_husband_name' => $beneficiary->representative->father_husband_name,
                    'mobile_number' => $beneficiary->representative->mobile_number,
                    'date_of_birth' => $beneficiary->representative->date_of_birth ? $beneficiary->representative->date_of_birth->format('Y-m-d') : null,
                    'gender' => $beneficiary->representative->gender,
                    'relationship' => $beneficiary->representative->relationship,
                ] : null,
                'district_remarks' => $beneficiary->district_remarks,
                'admin_remarks' => $beneficiary->admin_remarks,
                'rejection_remarks' => $beneficiary->rejection_remarks,
                'phase' => $beneficiary->phase ? [
                    'id' => $beneficiary->phase->id,
                    'district_id' => $beneficiary->phase->district_id,
                    'district_name' => $beneficiary->phase->district->name ?? 'N/A',
                ] : null,
            ]
        ]);
    }

    public function storeAjax(Request $request)
    {
        try {
            // First, do custom validation before Laravel's unique rule
            // This allows us to check normalized CNICs and provide better error messages
            $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($request->phase_id);
            
            // Normalize CNIC for comparison
            $inputCnic = trim($request->cnic);
            $normalizedInputCnic = str_replace(['-', ' '], '', $inputCnic);
            
            // Check if CNIC already exists in the same phase and scheme (with normalized comparison)
            $existingInSamePhaseScheme = Beneficiary::where('phase_id', $request->phase_id)
                ->where('scheme_id', $request->scheme_id)
                ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                    $query->where('cnic', $inputCnic)
                          ->orWhere('cnic', $normalizedInputCnic)
                          ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                          ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                })
                ->first();
            
            if ($existingInSamePhaseScheme) {
                // Get details for duplicate in same phase/scheme
                $existingBeneficiary = Beneficiary::where('id', $existingInSamePhaseScheme->id)
                    ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                    ->first();
                
                $financialYear = $phase->installment->fundAllocation->financialYear;
                
                return response()->json([
                    'success' => false,
                    'error_type' => 'duplicate_cnic',
                    'message' => 'This CNIC is already registered in this phase and scheme.',
                    'duplicate_details' => [
                        'cnic' => $existingBeneficiary ? $existingBeneficiary->cnic : $inputCnic,
                        'full_name' => $existingBeneficiary ? $existingBeneficiary->full_name : 'N/A',
                        'scheme_name' => $existingBeneficiary && $existingBeneficiary->scheme ? $existingBeneficiary->scheme->name : 'N/A',
                        'phase_name' => $existingBeneficiary && $existingBeneficiary->phase ? $existingBeneficiary->phase->name : 'N/A',
                        'district_name' => $existingBeneficiary && $existingBeneficiary->phase && $existingBeneficiary->phase->district ? $existingBeneficiary->phase->district->name : 'N/A',
                        'committee_name' => $existingBeneficiary && $existingBeneficiary->localZakatCommittee ? $existingBeneficiary->localZakatCommittee->name : 'N/A',
                        'status' => $existingBeneficiary ? ucfirst($existingBeneficiary->status) : 'N/A',
                        'financial_year' => $financialYear ? $financialYear->name : 'N/A',
                        'registered_date' => $existingBeneficiary && $existingBeneficiary->created_at ? $existingBeneficiary->created_at->format('d M Y, h:i A') : 'N/A',
                    ]
                ], 422);
            }
            
            $validated = $request->validate([
                'phase_id' => 'required|exists:phases,id',
                'scheme_id' => 'required|exists:schemes,id',
                'scheme_category_id' => 'nullable|exists:scheme_categories,id',
                'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
                'cnic' => [
                    'required',
                    'string',
                ],
                'full_name' => 'required|string|max:255',
                'father_husband_name' => 'required|string|max:255',
                'mobile_number' => 'nullable|string|max:20',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'amount' => 'required|numeric|min:0',
                'requires_representative' => 'boolean',
                'representative' => 'required_if:requires_representative,1|array',
                'representative.cnic' => 'required_if:requires_representative,1|string',
                'representative.full_name' => 'required_if:requires_representative,1|string|max:255',
                'representative.father_husband_name' => 'required_if:requires_representative,1|string|max:255',
                'representative.mobile_number' => 'nullable|string|max:20',
                'representative.date_of_birth' => 'required_if:requires_representative,1|date',
                'representative.gender' => 'required_if:requires_representative,1|in:male,female,other',
                'representative.relationship' => 'required_if:requires_representative,1|string|max:255',
            ]);

            // Check 1: Check if CNIC matches any active LZC member (regardless of committee) - with normalized CNIC
            $activeLZCMember = LZCMember::where(function($query) use ($inputCnic, $normalizedInputCnic) {
                    $query->where('cnic', $inputCnic)
                          ->orWhere('cnic', $normalizedInputCnic)
                          ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                          ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                })
                ->where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->toDateString());
                })
                ->first();

            if ($activeLZCMember) {
                return response()->json([
                    'success' => false,
                    'message' => 'Active LZC Members are not eligible for any scheme. The member must be inactive to be eligible.'
                ], 422);
            }

            // Check 2: Check if CNIC already exists in any scheme for the same financial year
            $financialYear = $phase->installment->fundAllocation->financialYear;
            
            if ($financialYear) {
                // Get all phases in the same financial year
                $phasesInFinancialYear = Phase::whereHas('installment.fundAllocation', function($query) use ($financialYear) {
                    $query->where('financial_year_id', $financialYear->id);
                })->pluck('id');
                
                // Check if CNIC already exists in any beneficiary in any phase of this financial year
                // Compare normalized CNICs (handle both with and without dashes)
                // Check ALL statuses - if a beneficiary exists with any status, they can't register again
                $existingBeneficiaryInFinancialYear = Beneficiary::whereIn('phase_id', $phasesInFinancialYear)
                    ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                        $query->where('cnic', $inputCnic)
                              ->orWhere('cnic', $normalizedInputCnic)
                              ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                              ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                    })
                    ->exists();

                if ($existingBeneficiaryInFinancialYear) {
                    // Get the existing beneficiary details for better error message
                    $existingBeneficiary = Beneficiary::whereIn('phase_id', $phasesInFinancialYear)
                        ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                            $query->where('cnic', $inputCnic)
                                  ->orWhere('cnic', $normalizedInputCnic)
                                  ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                                  ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                        })
                        ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                        ->orderBy('created_at', 'desc') // Get the most recent one
                        ->first();
                    
                    $existingSchemeName = $existingBeneficiary && $existingBeneficiary->scheme 
                        ? $existingBeneficiary->scheme->name 
                        : 'a scheme';
                    
                    $existingPhaseName = $existingBeneficiary && $existingBeneficiary->phase 
                        ? $existingBeneficiary->phase->name 
                        : 'N/A';
                    
                    $existingDistrictName = $existingBeneficiary && $existingBeneficiary->phase && $existingBeneficiary->phase->district
                        ? $existingBeneficiary->phase->district->name 
                        : 'N/A';
                    
                    $existingCommitteeName = $existingBeneficiary && $existingBeneficiary->localZakatCommittee
                        ? $existingBeneficiary->localZakatCommittee->name 
                        : 'N/A';
                    
                    $existingStatus = $existingBeneficiary ? ucfirst($existingBeneficiary->status) : 'N/A';
                    
                    return response()->json([
                        'success' => false,
                        'error_type' => 'duplicate_cnic',
                        'message' => 'This CNIC has already been registered in ' . $existingSchemeName . ' for the financial year ' . $financialYear->name . '. A beneficiary can only be registered in one scheme per financial year.',
                        'duplicate_details' => [
                            'cnic' => $existingBeneficiary ? $existingBeneficiary->cnic : $validated['cnic'],
                            'full_name' => $existingBeneficiary ? $existingBeneficiary->full_name : 'N/A',
                            'scheme_name' => $existingSchemeName,
                            'phase_name' => $existingPhaseName,
                            'district_name' => $existingDistrictName,
                            'committee_name' => $existingCommitteeName,
                            'status' => $existingStatus,
                            'financial_year' => $financialYear->name,
                            'registered_date' => $existingBeneficiary && $existingBeneficiary->created_at 
                                ? $existingBeneficiary->created_at->format('d M Y, h:i A') 
                                : 'N/A',
                        ]
                    ], 422);
                }
            }

            // Note: Duplicate check in same phase/scheme is already done earlier before validation

            $scheme = Scheme::with('categories')->findOrFail($validated['scheme_id']);
            $age = Carbon::parse($validated['date_of_birth'])->age;
            
            // Check Phase Limits FIRST (before scheme-level checks)
            // The phase is already loaded at the beginning of the method
            // Count ALL beneficiaries in this phase (any status) for phase limit check
            // The phase limit is about total beneficiaries that can be added, regardless of status
            $currentPhaseCount = $phase->beneficiaries()->count();
            
            // For amount check, only count submitted/approved/paid beneficiaries
            $currentPhaseAmount = $phase->beneficiaries()->whereIn('status', ['submitted', 'approved', 'paid'])->sum('amount');
            
            // Check phase-level beneficiary limit
            if ($currentPhaseCount >= $phase->max_beneficiaries) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum beneficiaries limit reached for this phase. Current: ' . $currentPhaseCount . ', Max allowed: ' . $phase->max_beneficiaries . '.',
                    'errors' => ['phase_id' => ['Maximum beneficiaries limit reached for this phase.']]
                ], 422);
            }
            
            // Check phase-level amount limit
            if (($currentPhaseAmount + $validated['amount']) > $phase->max_amount) {
                $remainingPhaseAmount = $phase->max_amount - $currentPhaseAmount;
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum amount limit will be exceeded for this phase. Current: Rs. ' . number_format($currentPhaseAmount, 2) . ', Max allowed: Rs. ' . number_format($phase->max_amount, 2) . ', Remaining: Rs. ' . number_format($remainingPhaseAmount, 2) . '.',
                    'errors' => ['amount' => ['Maximum amount limit will be exceeded for this phase.']]
                ], 422);
            }
            
            // Check 3: Check if total beneficiaries for this scheme exceed the sum of max_beneficiaries across all phases
            if ($financialYear) {
                // Get all phases for this scheme in the same financial year
                $phasesForScheme = Phase::where('scheme_id', $validated['scheme_id'])
                    ->whereHas('installment.fundAllocation', function($query) use ($financialYear) {
                        $query->where('financial_year_id', $financialYear->id);
                    })
                    ->get();
                
                // Calculate total max beneficiaries allowed for this scheme (sum of max_beneficiaries for all phases)
                $totalMaxBeneficiaries = $phasesForScheme->sum('max_beneficiaries');
                
                // Calculate current total beneficiaries for this scheme across all phases
                $phaseIdsForScheme = $phasesForScheme->pluck('id');
                $currentTotalBeneficiaries = Beneficiary::whereIn('phase_id', $phaseIdsForScheme)
                    ->whereIn('status', ['submitted', 'approved', 'paid'])
                    ->count();
                
                // Check if adding this beneficiary would exceed the limit
                if (($currentTotalBeneficiaries + 1) > $totalMaxBeneficiaries) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total beneficiaries for ' . $scheme->name . ' scheme cannot exceed ' . number_format($totalMaxBeneficiaries) . ' beneficiaries across all phases in the financial year ' . $financialYear->name . '. Current count: ' . number_format($currentTotalBeneficiaries) . ', Remaining: ' . number_format($totalMaxBeneficiaries - $currentTotalBeneficiaries) . '.'
                    ], 422);
                }
                
                // Check 4: For lump sum schemes (no categories), check if total amount exceeds sum of max_amount
                $isLumpSum = $scheme->categories->isEmpty();
                
                if ($isLumpSum) {
                    // Calculate total max amount allowed for this scheme (sum of max_amount for all phases)
                    $totalMaxAmount = $phasesForScheme->sum('max_amount');
                    
                    // Calculate current total amount given to beneficiaries for this scheme across all phases
                    $currentTotalAmount = Beneficiary::whereIn('phase_id', $phaseIdsForScheme)
                        ->whereIn('status', ['submitted', 'approved', 'paid'])
                        ->sum('amount');
                    
                    // Check if adding this beneficiary's amount would exceed the limit
                    if (($currentTotalAmount + $validated['amount']) > $totalMaxAmount) {
                        $remainingAmount = $totalMaxAmount - $currentTotalAmount;
                        return response()->json([
                            'success' => false,
                            'message' => 'Total amount for ' . $scheme->name . ' scheme (lump sum) cannot exceed Rs. ' . number_format($totalMaxAmount, 2) . ' across all phases in the financial year ' . $financialYear->name . '. Current total: Rs. ' . number_format($currentTotalAmount, 2) . ', Remaining: Rs. ' . number_format($remainingAmount, 2) . '.'
                        ], 422);
                    }
                }
            }
            
            if ($validated['scheme_category_id']) {
                $category = SchemeCategory::findOrFail($validated['scheme_category_id']);
                $validated['amount'] = $category->amount;
            }
            
            if ($scheme->has_age_restriction && $age < $scheme->minimum_age) {
                if (!$validated['requires_representative']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This scheme requires minimum age of ' . $scheme->minimum_age . ' years. Please add a representative.'
                    ], 422);
                }
            }
            
            if (!$scheme->has_age_restriction && $age < 18) {
                if (!$validated['requires_representative']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Beneficiaries under 18 require a representative for JazzCash transactions.'
                    ], 422);
                }
            }

            // Note: Phase limits are already checked earlier before scheme-level checks

            $beneficiary = Beneficiary::create($validated);

            if ($validated['requires_representative'] && isset($validated['representative'])) {
                $beneficiary->representative()->create($validated['representative']);
            }

            $beneficiary->load(['scheme', 'schemeCategory', 'localZakatCommittee']);

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary added successfully.',
                'beneficiary' => $beneficiary
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if the validation error is related to CNIC duplicate
            $errors = $e->errors();
            if (isset($errors['cnic']) && is_array($errors['cnic'])) {
                foreach ($errors['cnic'] as $error) {
                    if (str_contains(strtolower($error), 'already been taken') || str_contains(strtolower($error), 'duplicate')) {
                        // This is a duplicate CNIC error from Laravel's unique rule
                        // Try to get details
                        try {
                            $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($request->phase_id);
                            $inputCnic = trim($request->cnic);
                            $normalizedInputCnic = str_replace(['-', ' '], '', $inputCnic);
                            
                            $existingBeneficiary = Beneficiary::where('phase_id', $request->phase_id)
                                ->where('scheme_id', $request->scheme_id)
                                ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                                    $query->where('cnic', $inputCnic)
                                          ->orWhere('cnic', $normalizedInputCnic)
                                          ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                                          ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                                })
                                ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                                ->first();
                            
                            if ($existingBeneficiary) {
                                $financialYear = $phase->installment->fundAllocation->financialYear;
                                
                                return response()->json([
                                    'success' => false,
                                    'error_type' => 'duplicate_cnic',
                                    'message' => 'This CNIC is already registered in this phase and scheme.',
                                    'duplicate_details' => [
                                        'cnic' => $existingBeneficiary->cnic,
                                        'full_name' => $existingBeneficiary->full_name,
                                        'scheme_name' => $existingBeneficiary->scheme ? $existingBeneficiary->scheme->name : 'N/A',
                                        'phase_name' => $existingBeneficiary->phase ? $existingBeneficiary->phase->name : 'N/A',
                                        'district_name' => $existingBeneficiary->phase && $existingBeneficiary->phase->district ? $existingBeneficiary->phase->district->name : 'N/A',
                                        'committee_name' => $existingBeneficiary->localZakatCommittee ? $existingBeneficiary->localZakatCommittee->name : 'N/A',
                                        'status' => ucfirst($existingBeneficiary->status),
                                        'financial_year' => $financialYear ? $financialYear->name : 'N/A',
                                        'registered_date' => $existingBeneficiary->created_at ? $existingBeneficiary->created_at->format('d M Y, h:i A') : 'N/A',
                                    ],
                                    'errors' => $errors
                                ], 422);
                            }
                        } catch (\Exception $ex) {
                            // If we can't get details, just return the validation error
                        }
                    }
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database unique constraint violations
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'unique_beneficiary_phase_scheme') || str_contains($e->getMessage(), 'Duplicate entry')) {
                // This is a duplicate CNIC error from database constraint
                try {
                    $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($request->phase_id);
                    $inputCnic = trim($request->cnic);
                    $normalizedInputCnic = str_replace(['-', ' '], '', $inputCnic);
                    
                    $existingBeneficiary = Beneficiary::where('phase_id', $request->phase_id)
                        ->where('scheme_id', $request->scheme_id)
                        ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                            $query->where('cnic', $inputCnic)
                                  ->orWhere('cnic', $normalizedInputCnic)
                                  ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                                  ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                        })
                        ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                        ->first();
                    
                    if ($existingBeneficiary) {
                        $financialYear = $phase->installment->fundAllocation->financialYear;
                        
                        return response()->json([
                            'success' => false,
                            'error_type' => 'duplicate_cnic',
                            'message' => 'This CNIC is already registered in this phase and scheme.',
                            'duplicate_details' => [
                                'cnic' => $existingBeneficiary->cnic,
                                'full_name' => $existingBeneficiary->full_name,
                                'scheme_name' => $existingBeneficiary->scheme ? $existingBeneficiary->scheme->name : 'N/A',
                                'phase_name' => $existingBeneficiary->phase ? $existingBeneficiary->phase->name : 'N/A',
                                'district_name' => $existingBeneficiary->phase && $existingBeneficiary->phase->district ? $existingBeneficiary->phase->district->name : 'N/A',
                                'committee_name' => $existingBeneficiary->localZakatCommittee ? $existingBeneficiary->localZakatCommittee->name : 'N/A',
                                'status' => ucfirst($existingBeneficiary->status),
                                'financial_year' => $financialYear ? $financialYear->name : 'N/A',
                                'registered_date' => $existingBeneficiary->created_at ? $existingBeneficiary->created_at->format('d M Y, h:i A') : 'N/A',
                            ]
                        ], 422);
                    }
                } catch (\Exception $ex) {
                    // If we can't get details, return generic error
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAjax(Request $request, Beneficiary $beneficiary)
    {
        try {
            if (in_array($beneficiary->status, ['approved', 'paid', 'submitted'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit beneficiary with status: ' . $beneficiary->status
                ], 422);
            }

            // First, do custom validation before Laravel's unique rule
            // This allows us to check normalized CNICs and provide better error messages
            $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($beneficiary->phase_id);
            $scheme = Scheme::with('categories')->findOrFail($request->scheme_id);
            $financialYear = $phase->installment->fundAllocation->financialYear;
            
            // Normalize CNIC for comparison
            $inputCnic = trim($request->cnic);
            $normalizedInputCnic = str_replace(['-', ' '], '', $inputCnic);
            
            // Check if scheme or amount changed
            $schemeChanged = $request->scheme_id != $beneficiary->scheme_id;
            $amountChanged = $request->amount != $beneficiary->amount;
            
            // Check 0: Check if CNIC already exists in the same phase and scheme (with normalized comparison)
            // Only check if CNIC or scheme changed
            if ($inputCnic !== $beneficiary->cnic || $schemeChanged) {
                $existingInSamePhaseScheme = Beneficiary::where('phase_id', $beneficiary->phase_id)
                    ->where('scheme_id', $request->scheme_id)
                    ->where('id', '!=', $beneficiary->id) // Exclude current beneficiary
                    ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                        $query->where('cnic', $inputCnic)
                              ->orWhere('cnic', $normalizedInputCnic)
                              ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                              ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                    })
                    ->first();
                
                if ($existingInSamePhaseScheme) {
                    // Get details for duplicate in same phase/scheme
                    $existingBeneficiary = Beneficiary::where('id', $existingInSamePhaseScheme->id)
                        ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                        ->first();
                    
                    return response()->json([
                        'success' => false,
                        'error_type' => 'duplicate_cnic',
                        'message' => 'This CNIC is already registered in this phase and scheme.',
                        'duplicate_details' => [
                            'cnic' => $existingBeneficiary ? $existingBeneficiary->cnic : $inputCnic,
                            'full_name' => $existingBeneficiary ? $existingBeneficiary->full_name : 'N/A',
                            'scheme_name' => $existingBeneficiary && $existingBeneficiary->scheme ? $existingBeneficiary->scheme->name : 'N/A',
                            'phase_name' => $existingBeneficiary && $existingBeneficiary->phase ? $existingBeneficiary->phase->name : 'N/A',
                            'district_name' => $existingBeneficiary && $existingBeneficiary->phase && $existingBeneficiary->phase->district ? $existingBeneficiary->phase->district->name : 'N/A',
                            'committee_name' => $existingBeneficiary && $existingBeneficiary->localZakatCommittee ? $existingBeneficiary->localZakatCommittee->name : 'N/A',
                            'status' => $existingBeneficiary ? ucfirst($existingBeneficiary->status) : 'N/A',
                            'financial_year' => $financialYear ? $financialYear->name : 'N/A',
                            'registered_date' => $existingBeneficiary && $existingBeneficiary->created_at ? $existingBeneficiary->created_at->format('d M Y, h:i A') : 'N/A',
                        ]
                    ], 422);
                }
            }
            
            $validated = $request->validate([
                'scheme_id' => 'required|exists:schemes,id',
                'scheme_category_id' => 'nullable|exists:scheme_categories,id',
                'local_zakat_committee_id' => 'required|exists:local_zakat_committees,id',
                'cnic' => [
                    'required',
                    'string',
                ],
                'full_name' => 'required|string|max:255',
                'father_husband_name' => 'required|string|max:255',
                'mobile_number' => 'nullable|string|max:20',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'amount' => 'required|numeric|min:0',
                'requires_representative' => 'boolean',
                'representative' => 'required_if:requires_representative,1|array',
                'representative.cnic' => 'required_if:requires_representative,1|string',
                'representative.full_name' => 'required_if:requires_representative,1|string|max:255',
                'representative.father_husband_name' => 'required_if:requires_representative,1|string|max:255',
                'representative.mobile_number' => 'nullable|string|max:20',
                'representative.date_of_birth' => 'required_if:requires_representative,1|date',
                'representative.gender' => 'required_if:requires_representative,1|in:male,female,other',
                'representative.relationship' => 'required_if:requires_representative,1|string|max:255',
            ]);

            if ($validated['scheme_category_id']) {
                $category = SchemeCategory::findOrFail($validated['scheme_category_id']);
                $validated['amount'] = $category->amount;
            }
            
            // Recalculate amountChanged after category might have set it
            $amountChanged = $validated['amount'] != $beneficiary->amount;
            
            // Only check validations if CNIC is being changed
            if ($inputCnic !== $beneficiary->cnic) {
                $existingInSamePhaseScheme = Beneficiary::where('phase_id', $beneficiary->phase_id)
                    ->where('scheme_id', $validated['scheme_id'])
                    ->where('id', '!=', $beneficiary->id) // Exclude current beneficiary
                    ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                        $query->where('cnic', $inputCnic)
                              ->orWhere('cnic', $normalizedInputCnic)
                              ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                              ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                    })
                    ->first();
                
                if ($existingInSamePhaseScheme) {
                    // Get details for duplicate in same phase/scheme
                    $existingBeneficiary = Beneficiary::where('id', $existingInSamePhaseScheme->id)
                        ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                        ->first();
                    
                    return response()->json([
                        'success' => false,
                        'error_type' => 'duplicate_cnic',
                        'message' => 'This CNIC is already registered in this phase and scheme.',
                        'duplicate_details' => [
                            'cnic' => $existingBeneficiary ? $existingBeneficiary->cnic : $inputCnic,
                            'full_name' => $existingBeneficiary ? $existingBeneficiary->full_name : 'N/A',
                            'scheme_name' => $existingBeneficiary && $existingBeneficiary->scheme ? $existingBeneficiary->scheme->name : 'N/A',
                            'phase_name' => $existingBeneficiary && $existingBeneficiary->phase ? $existingBeneficiary->phase->name : 'N/A',
                            'district_name' => $existingBeneficiary && $existingBeneficiary->phase && $existingBeneficiary->phase->district ? $existingBeneficiary->phase->district->name : 'N/A',
                            'committee_name' => $existingBeneficiary && $existingBeneficiary->localZakatCommittee ? $existingBeneficiary->localZakatCommittee->name : 'N/A',
                            'status' => $existingBeneficiary ? ucfirst($existingBeneficiary->status) : 'N/A',
                            'financial_year' => $financialYear ? $financialYear->name : 'N/A',
                            'registered_date' => $existingBeneficiary && $existingBeneficiary->created_at ? $existingBeneficiary->created_at->format('d M Y, h:i A') : 'N/A',
                        ]
                    ], 422);
                }
            }
            
            // Only check validations if CNIC is being changed
            if ($inputCnic !== $beneficiary->cnic) {
                // Check 1: Check if new CNIC matches any active LZC member (regardless of committee) - with normalized CNIC
                $activeLZCMember = LZCMember::where(function($query) use ($inputCnic, $normalizedInputCnic) {
                        $query->where('cnic', $inputCnic)
                              ->orWhere('cnic', $normalizedInputCnic)
                              ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                              ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                    })
                    ->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now()->toDateString());
                    })
                    ->first();

                if ($activeLZCMember) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Active LZC Members are not eligible for any scheme. The member must be inactive to be eligible.'
                    ], 422);
                }

                // Check 2: Check if new CNIC already exists in any scheme for the same financial year
                if ($financialYear) {
                    // Get all phases in the same financial year
                    $phasesInFinancialYear = Phase::whereHas('installment.fundAllocation', function($query) use ($financialYear) {
                        $query->where('financial_year_id', $financialYear->id);
                    })->pluck('id');
                    
                    // Check if CNIC already exists in any beneficiary in any phase of this financial year (excluding current beneficiary)
                    // Compare normalized CNICs (handle both with and without dashes)
                    // Check ALL statuses - if a beneficiary exists with any status, they can't register again
                    $existingBeneficiaryInFinancialYear = Beneficiary::whereIn('phase_id', $phasesInFinancialYear)
                        ->where('id', '!=', $beneficiary->id) // Exclude current beneficiary
                        ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                            $query->where('cnic', $inputCnic)
                                  ->orWhere('cnic', $normalizedInputCnic)
                                  ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                                  ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                        })
                        ->exists();

                    if ($existingBeneficiaryInFinancialYear) {
                        // Get the existing beneficiary details for better error message
                        $existingBeneficiary = Beneficiary::whereIn('phase_id', $phasesInFinancialYear)
                            ->where('id', '!=', $beneficiary->id)
                            ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                                $query->where('cnic', $inputCnic)
                                      ->orWhere('cnic', $normalizedInputCnic)
                                      ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                                      ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                            })
                            ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                            ->orderBy('created_at', 'desc') // Get the most recent one
                            ->first();
                        
                        $existingSchemeName = $existingBeneficiary && $existingBeneficiary->scheme 
                            ? $existingBeneficiary->scheme->name 
                            : 'a scheme';
                        
                        $existingPhaseName = $existingBeneficiary && $existingBeneficiary->phase 
                            ? $existingBeneficiary->phase->name 
                            : 'N/A';
                        
                        $existingDistrictName = $existingBeneficiary && $existingBeneficiary->phase && $existingBeneficiary->phase->district
                            ? $existingBeneficiary->phase->district->name 
                            : 'N/A';
                        
                        $existingCommitteeName = $existingBeneficiary && $existingBeneficiary->localZakatCommittee
                            ? $existingBeneficiary->localZakatCommittee->name 
                            : 'N/A';
                        
                        $existingStatus = $existingBeneficiary ? ucfirst($existingBeneficiary->status) : 'N/A';
                        
                        return response()->json([
                            'success' => false,
                            'error_type' => 'duplicate_cnic',
                            'message' => 'This CNIC has already been registered in ' . $existingSchemeName . ' for the financial year ' . $financialYear->name . '. A beneficiary can only be registered in one scheme per financial year.',
                            'duplicate_details' => [
                                'cnic' => $existingBeneficiary ? $existingBeneficiary->cnic : $validated['cnic'],
                                'full_name' => $existingBeneficiary ? $existingBeneficiary->full_name : 'N/A',
                                'scheme_name' => $existingSchemeName,
                                'phase_name' => $existingPhaseName,
                                'district_name' => $existingDistrictName,
                                'committee_name' => $existingCommitteeName,
                                'status' => $existingStatus,
                                'financial_year' => $financialYear->name,
                                'registered_date' => $existingBeneficiary && $existingBeneficiary->created_at 
                                    ? $existingBeneficiary->created_at->format('d M Y, h:i A') 
                                    : 'N/A',
                            ]
                        ], 422);
                    }
                }
            }

            // Check 3 & 4: Scheme-level beneficiary and amount limits (if scheme or amount changed)
            if (($schemeChanged || $amountChanged) && $financialYear) {
                // Get all phases for this scheme in the same financial year
                $phasesForScheme = Phase::where('scheme_id', $validated['scheme_id'])
                    ->whereHas('installment.fundAllocation', function($query) use ($financialYear) {
                        $query->where('financial_year_id', $financialYear->id);
                    })
                    ->get();
                
                // Check 3: Check if total beneficiaries for this scheme exceed the sum of max_beneficiaries
                $totalMaxBeneficiaries = $phasesForScheme->sum('max_beneficiaries');
                
                $phaseIdsForScheme = $phasesForScheme->pluck('id');
                $currentTotalBeneficiaries = Beneficiary::whereIn('phase_id', $phaseIdsForScheme)
                    ->whereIn('status', ['submitted', 'approved', 'paid'])
                    ->where('id', '!=', $beneficiary->id) // Exclude current beneficiary
                    ->count();
                
                // If scheme changed, check if adding this beneficiary would exceed limit
                if ($schemeChanged && (($currentTotalBeneficiaries + 1) > $totalMaxBeneficiaries)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total beneficiaries for ' . $scheme->name . ' scheme cannot exceed ' . number_format($totalMaxBeneficiaries) . ' beneficiaries across all phases in the financial year ' . $financialYear->name . '. Current count: ' . number_format($currentTotalBeneficiaries) . ', Remaining: ' . number_format($totalMaxBeneficiaries - $currentTotalBeneficiaries) . '.'
                    ], 422);
                }
                
                // Check 4: For lump sum schemes (no categories), check if total amount exceeds sum of max_amount
                $isLumpSum = $scheme->categories->isEmpty();
                
                if ($isLumpSum) {
                    $totalMaxAmount = $phasesForScheme->sum('max_amount');
                    
                    // Calculate current total amount (excluding current beneficiary's old amount)
                    $currentTotalAmount = Beneficiary::whereIn('phase_id', $phaseIdsForScheme)
                        ->whereIn('status', ['submitted', 'approved', 'paid'])
                        ->where('id', '!=', $beneficiary->id) // Exclude current beneficiary
                        ->sum('amount');
                    
                    // Check if new amount would exceed the limit
                    if (($currentTotalAmount + $validated['amount']) > $totalMaxAmount) {
                        $remainingAmount = $totalMaxAmount - $currentTotalAmount;
                        return response()->json([
                            'success' => false,
                            'message' => 'Total amount for ' . $scheme->name . ' scheme (lump sum) cannot exceed Rs. ' . number_format($totalMaxAmount, 2) . ' across all phases in the financial year ' . $financialYear->name . '. Current total: Rs. ' . number_format($currentTotalAmount, 2) . ', Remaining: Rs. ' . number_format($remainingAmount, 2) . '.'
                        ], 422);
                    }
                }
            }

            $beneficiary->update($validated);

            if ($validated['requires_representative'] && isset($validated['representative'])) {
                if ($beneficiary->representative) {
                    $beneficiary->representative->update($validated['representative']);
                } else {
                    $beneficiary->representative()->create($validated['representative']);
                }
            } elseif ($beneficiary->representative) {
                $beneficiary->representative->delete();
            }

            $beneficiary->load(['scheme', 'schemeCategory', 'localZakatCommittee']);

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary updated successfully.',
                'beneficiary' => $beneficiary
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if the validation error is related to CNIC duplicate
            $errors = $e->errors();
            if (isset($errors['cnic']) && is_array($errors['cnic'])) {
                foreach ($errors['cnic'] as $error) {
                    if (str_contains(strtolower($error), 'already been taken') || str_contains(strtolower($error), 'duplicate')) {
                        // This is a duplicate CNIC error from Laravel's unique rule
                        // Try to get details
                        try {
                            $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($beneficiary->phase_id);
                            $inputCnic = trim($request->cnic);
                            $normalizedInputCnic = str_replace(['-', ' '], '', $inputCnic);
                            
                            $existingBeneficiary = Beneficiary::where('phase_id', $beneficiary->phase_id)
                                ->where('scheme_id', $request->scheme_id)
                                ->where('id', '!=', $beneficiary->id)
                                ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                                    $query->where('cnic', $inputCnic)
                                          ->orWhere('cnic', $normalizedInputCnic)
                                          ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                                          ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                                })
                                ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                                ->first();
                            
                            if ($existingBeneficiary) {
                                $financialYear = $phase->installment->fundAllocation->financialYear;
                                
                                return response()->json([
                                    'success' => false,
                                    'error_type' => 'duplicate_cnic',
                                    'message' => 'This CNIC is already registered in this phase and scheme.',
                                    'duplicate_details' => [
                                        'cnic' => $existingBeneficiary->cnic,
                                        'full_name' => $existingBeneficiary->full_name,
                                        'scheme_name' => $existingBeneficiary->scheme ? $existingBeneficiary->scheme->name : 'N/A',
                                        'phase_name' => $existingBeneficiary->phase ? $existingBeneficiary->phase->name : 'N/A',
                                        'district_name' => $existingBeneficiary->phase && $existingBeneficiary->phase->district ? $existingBeneficiary->phase->district->name : 'N/A',
                                        'committee_name' => $existingBeneficiary->localZakatCommittee ? $existingBeneficiary->localZakatCommittee->name : 'N/A',
                                        'status' => ucfirst($existingBeneficiary->status),
                                        'financial_year' => $financialYear ? $financialYear->name : 'N/A',
                                        'registered_date' => $existingBeneficiary->created_at ? $existingBeneficiary->created_at->format('d M Y, h:i A') : 'N/A',
                                    ],
                                    'errors' => $errors
                                ], 422);
                            }
                        } catch (\Exception $ex) {
                            // If we can't get details, just return the validation error
                        }
                    }
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database unique constraint violations
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'unique_beneficiary_phase_scheme') || str_contains($e->getMessage(), 'Duplicate entry')) {
                // This is a duplicate CNIC error from database constraint
                try {
                    $phase = Phase::with(['installment.fundAllocation.financialYear'])->findOrFail($beneficiary->phase_id);
                    $inputCnic = trim($request->cnic);
                    $normalizedInputCnic = str_replace(['-', ' '], '', $inputCnic);
                    
                    $existingBeneficiary = Beneficiary::where('phase_id', $beneficiary->phase_id)
                        ->where('scheme_id', $request->scheme_id)
                        ->where('id', '!=', $beneficiary->id)
                        ->where(function($query) use ($inputCnic, $normalizedInputCnic) {
                            $query->where('cnic', $inputCnic)
                                  ->orWhere('cnic', $normalizedInputCnic)
                                  ->orWhereRaw('TRIM(cnic) = ?', [$inputCnic])
                                  ->orWhereRaw('TRIM(REPLACE(REPLACE(cnic, "-", ""), " ", "")) = ?', [$normalizedInputCnic]);
                        })
                        ->with(['phase.scheme', 'phase.district', 'scheme', 'localZakatCommittee'])
                        ->first();
                    
                    if ($existingBeneficiary) {
                        $financialYear = $phase->installment->fundAllocation->financialYear;
                        
                        return response()->json([
                            'success' => false,
                            'error_type' => 'duplicate_cnic',
                            'message' => 'This CNIC is already registered in this phase and scheme.',
                            'duplicate_details' => [
                                'cnic' => $existingBeneficiary->cnic,
                                'full_name' => $existingBeneficiary->full_name,
                                'scheme_name' => $existingBeneficiary->scheme ? $existingBeneficiary->scheme->name : 'N/A',
                                'phase_name' => $existingBeneficiary->phase ? $existingBeneficiary->phase->name : 'N/A',
                                'district_name' => $existingBeneficiary->phase && $existingBeneficiary->phase->district ? $existingBeneficiary->phase->district->name : 'N/A',
                                'committee_name' => $existingBeneficiary->localZakatCommittee ? $existingBeneficiary->localZakatCommittee->name : 'N/A',
                                'status' => ucfirst($existingBeneficiary->status),
                                'financial_year' => $financialYear ? $financialYear->name : 'N/A',
                                'registered_date' => $existingBeneficiary->created_at ? $existingBeneficiary->created_at->format('d M Y, h:i A') : 'N/A',
                            ]
                        ], 422);
                    }
                } catch (\Exception $ex) {
                    // If we can't get details, return generic error
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyAjax(Request $request, Beneficiary $beneficiary)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:submit,reject',
                'district_remarks' => 'nullable|string',
            ]);

            $user = auth()->user();

            if ($validated['action'] === 'submit') {
                $beneficiary->update([
                    'status' => 'submitted',
                    'submitted_by' => $user->id,
                    'submitted_at' => now(),
                    'district_remarks' => $validated['district_remarks'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Beneficiary submitted successfully.',
                    'beneficiary' => $beneficiary->fresh(['scheme', 'schemeCategory', 'localZakatCommittee'])
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Rejection should be done by Administrator HQ.'
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAjax(Beneficiary $beneficiary)
    {
        try {
            if (in_array($beneficiary->status, ['approved', 'paid'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete approved or paid beneficiary.'
                ], 422);
            }

            $beneficiary->delete();

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
