<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\FundAllocation;
use App\Models\District;
use App\Models\Phase;
use App\Models\Scheme;
use App\Models\LocalZakatCommittee;
use App\Models\FinancialYear;
use App\Models\Installment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        if ($user->isSuperAdmin() || $user->isAdministratorHQ()) {
            // Overall statistics
            $data['total_beneficiaries'] = Beneficiary::count();
            $data['pending_beneficiaries'] = Beneficiary::where('status', 'pending')->count();
            $data['submitted_beneficiaries'] = Beneficiary::where('status', 'submitted')->count();
            $data['approved_beneficiaries'] = Beneficiary::where('status', 'approved')->count();
            $data['rejected_beneficiaries'] = Beneficiary::where('status', 'rejected')->count();
            $data['paid_beneficiaries'] = Beneficiary::where('status', 'paid')->count();
            $data['payment_failed_beneficiaries'] = Beneficiary::whereNotNull('jazzcash_status')
                ->where('jazzcash_status', '!=', 'SUCCESS')->count();
            
            $data['total_funds'] = FundAllocation::sum('total_amount');
            $data['disbursed_funds'] = Beneficiary::whereIn('status', ['approved', 'paid'])->sum('amount');
            $data['paid_funds'] = Beneficiary::where('status', 'paid')->sum('amount');
            $data['pending_funds'] = Beneficiary::where('status', 'approved')->sum('amount');
            
            $data['districts'] = District::where('is_active', true)->count();
            $data['active_phases'] = Phase::where('status', 'open')->count();
            $data['total_phases'] = Phase::count();
            $data['schemes'] = Scheme::where('is_active', true)->count();
            $data['committees'] = LocalZakatCommittee::where('is_active', true)->count();
            $data['financial_years'] = FinancialYear::count();
            $data['active_financial_year'] = FinancialYear::where('is_current', true)->first();
            
            // Status distribution for charts
            $data['status_distribution'] = Beneficiary::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            
            // Payment status distribution
            $data['payment_status'] = [
                'paid' => Beneficiary::where('status', 'paid')->count(),
                'payment_failed' => Beneficiary::whereNotNull('jazzcash_status')
                    ->where('jazzcash_status', '!=', 'SUCCESS')->count(),
                'pending_payment' => Beneficiary::where('status', 'approved')->count(),
            ];
            
            // District-wise statistics
            $data['district_stats'] = District::withCount(['beneficiaries' => function($query) {
                $query->whereIn('beneficiaries.status', ['submitted', 'approved', 'paid']);
            }])->where('is_active', true)->orderBy('name')->get();
            
            // Scheme-wise statistics
            $data['scheme_stats'] = Scheme::withCount(['beneficiaries' => function($query) {
                $query->whereIn('beneficiaries.status', ['submitted', 'approved', 'paid']);
            }])->where('is_active', true)->orderBy('name')->get();
            
            // Monthly trends (last 12 months)
            $data['monthly_trends'] = Beneficiary::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count'),
                DB::raw('sum(amount) as total_amount')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
            // Fund allocation trends
            $data['fund_trends'] = FundAllocation::select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('sum(total_amount) as total_amount')
            )
            ->where('date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
            // Recent beneficiaries (last 10)
            $data['recent_beneficiaries'] = Beneficiary::with(['scheme', 'phase.district', 'localZakatCommittee'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Active phases
            $data['active_phases_list'] = Phase::with(['district', 'scheme', 'installment.fundAllocation.financialYear'])
                ->where('status', 'open')
                ->orderBy('start_date', 'desc')
                ->limit(10)
                ->get();
            
            // Recent fund allocations
            $data['recent_allocations'] = FundAllocation::with('financialYear')
                ->orderBy('date', 'desc')
                ->limit(10)
                ->get();
            
            // Top districts by beneficiaries
            $data['top_districts'] = District::withCount(['beneficiaries' => function($query) {
                $query->whereIn('beneficiaries.status', ['submitted', 'approved', 'paid']);
            }])
            ->where('is_active', true)
            ->orderBy('beneficiaries_count', 'desc')
            ->limit(10)
            ->get();
            
            // Top schemes by beneficiaries
            $data['top_schemes'] = Scheme::withCount(['beneficiaries' => function($query) {
                $query->whereIn('beneficiaries.status', ['submitted', 'approved', 'paid']);
            }])
            ->where('is_active', true)
            ->orderBy('beneficiaries_count', 'desc')
            ->limit(10)
            ->get();
            
        } elseif ($user->isDistrictUser() && $user->district_id) {
            // District user statistics
            $districtId = $user->district_id;
            $data['total_beneficiaries'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->count();
            $data['pending_beneficiaries'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->where('status', 'pending')->count();
            $data['submitted_beneficiaries'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->where('status', 'submitted')->count();
            $data['approved_beneficiaries'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->where('status', 'approved')->count();
            $data['rejected_beneficiaries'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->where('status', 'rejected')->count();
            $data['paid_beneficiaries'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->where('status', 'paid')->count();
            
            $data['disbursed_funds'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })->whereIn('status', ['approved', 'paid'])->sum('amount');
            
            // Status distribution for charts
            $data['status_distribution'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
            // Scheme-wise statistics for district
            $data['scheme_stats'] = Scheme::withCount(['beneficiaries' => function($query) use ($districtId) {
                $query->whereHas('phase', function($q) use ($districtId) {
                    $q->where('district_id', $districtId);
                })->whereIn('beneficiaries.status', ['submitted', 'approved', 'paid']);
            }])->where('is_active', true)->orderBy('name')->get();
            
            // Recent beneficiaries for district
            $data['recent_beneficiaries'] = Beneficiary::with(['scheme', 'phase.district', 'localZakatCommittee'])
                ->whereHas('phase', function($q) use ($districtId) {
                    $q->where('district_id', $districtId);
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Active phases for district
            $data['active_phases_list'] = Phase::with(['district', 'scheme', 'installment.fundAllocation.financialYear'])
                ->withCount('beneficiaries')
                ->where('district_id', $districtId)
                ->where('status', 'open')
                ->orderBy('start_date', 'desc')
                ->limit(10)
                ->get();
            
            // Monthly trends for district (last 12 months)
            $data['monthly_trends'] = Beneficiary::whereHas('phase', function($q) use ($districtId) {
                $q->where('district_id', $districtId);
            })
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count'),
                DB::raw('sum(amount) as total_amount')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        } elseif ($user->isInstitutionUser() && $user->institution) {
            // Institution user statistics - restricted to this institution's beneficiaries and relevant schemes
            $institution = $user->institution;
            $institutionId = $institution->id;
            $districtId = $institution->district_id;

            // Map institution.type to scheme.institutional_type
            $institutionType = $institution->type; // middle_school, high_school, college, university, madarsa, hospital
            $institutionalTypeForScheme = null;
            if (in_array($institutionType, ['middle_school', 'high_school', 'college', 'university'])) {
                $institutionalTypeForScheme = 'educational';
            } elseif ($institutionType === 'madarsa') {
                $institutionalTypeForScheme = 'madarsa';
            } elseif ($institutionType === 'hospital') {
                $institutionalTypeForScheme = 'health';
            }

            // Base beneficiary query for this institution
            $beneficiaryBase = Beneficiary::where('institution_id', $institutionId);

            $data['total_beneficiaries'] = (clone $beneficiaryBase)->count();
            $data['pending_beneficiaries'] = (clone $beneficiaryBase)->where('status', 'pending')->count();
            $data['submitted_beneficiaries'] = (clone $beneficiaryBase)->where('status', 'submitted')->count();
            $data['approved_beneficiaries'] = (clone $beneficiaryBase)->where('status', 'approved')->count();
            $data['rejected_beneficiaries'] = (clone $beneficiaryBase)->where('status', 'rejected')->count();
            $data['paid_beneficiaries'] = (clone $beneficiaryBase)->where('status', 'paid')->count();

            $data['disbursed_funds'] = (clone $beneficiaryBase)
                ->whereIn('status', ['approved', 'paid'])
                ->sum('amount');

            // Status distribution for charts (institution-level)
            $data['status_distribution'] = (clone $beneficiaryBase)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Scheme-wise statistics for this institution (only institutional schemes of correct type)
            $data['scheme_stats'] = Scheme::where('is_institutional', true)
                ->when($institutionalTypeForScheme, function ($q) use ($institutionalTypeForScheme) {
                    $q->where('institutional_type', $institutionalTypeForScheme);
                })
                ->withCount(['beneficiaries' => function($query) use ($institutionId) {
                    $query->where('institution_id', $institutionId)
                          ->whereIn('beneficiaries.status', ['submitted', 'approved', 'paid']);
                }])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            // Recent beneficiaries for this institution
            $data['recent_beneficiaries'] = Beneficiary::with(['scheme', 'phase.district', 'institution'])
                ->where('institution_id', $institutionId)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Active phases for this institution: in its district and matching institutional type
            $data['active_phases_list'] = Phase::with(['district', 'scheme', 'installment.fundAllocation.financialYear'])
                ->where('district_id', $districtId)
                ->where('status', 'open')
                ->whereHas('scheme', function ($q) use ($institutionalTypeForScheme) {
                    $q->where('is_institutional', true);
                    if ($institutionalTypeForScheme) {
                        $q->where('institutional_type', $institutionalTypeForScheme);
                    }
                })
                ->orderBy('start_date', 'desc')
                ->limit(10)
                ->get();

            // Monthly trends for this institution (last 12 months)
            $data['monthly_trends'] = Beneficiary::where('institution_id', $institutionId)
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('count(*) as count'),
                    DB::raw('sum(amount) as total_amount')
                )
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            // Fallback: no specific district or institution, show minimal stats
            $data['total_beneficiaries'] = Beneficiary::count();
            $data['submitted_beneficiaries'] = Beneficiary::where('status', 'submitted')->count();
            $data['approved_beneficiaries'] = Beneficiary::where('status', 'approved')->count();
            $data['paid_beneficiaries'] = Beneficiary::where('status', 'paid')->count();
            $data['recent_beneficiaries'] = Beneficiary::with(['scheme', 'phase.district'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            $data['monthly_trends'] = Beneficiary::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('count(*) as count'),
                    DB::raw('sum(amount) as total_amount')
                )
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return view('dashboard.index', compact('data'));
    }
}
