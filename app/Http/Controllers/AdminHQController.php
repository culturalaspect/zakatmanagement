<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Phase;
use App\Models\District;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminHQController extends Controller
{
    public function pendingApprovals()
    {
        $user = auth()->user();
        $query = Beneficiary::where('status', 'submitted')
            ->with(['phase.district', 'phase.installment.fundAllocation.financialYear', 'scheme', 'localZakatCommittee', 'submittedBy']);

        if ($user->isDistrictUser() && $user->district_id) {
            $query->whereHas('phase', function($q) use ($user) {
                $q->where('district_id', $user->district_id);
            });
        }

        $beneficiaries = $query->orderBy('submitted_at', 'asc')->get();

        // Get filter options
        $districtsQuery = District::where('is_active', true)->orderBy('name');
        if ($user->isDistrictUser() && $user->district_id) {
            $districtsQuery->where('id', $user->district_id);
        }
        $districts = $districtsQuery->get();

        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        
        $financialYears = \App\Models\FinancialYear::orderBy('name', 'desc')->get();

        return view('admin-hq.pending-approvals', compact('beneficiaries', 'districts', 'schemes', 'financialYears'));
    }

    public function exportApproved(Request $request)
    {
        $query = Beneficiary::where('status', 'approved')
            ->with(['phase.district', 'scheme', 'schemeCategory', 'representative', 'localZakatCommittee']);

        // Filter by phase if provided
        if ($request->has('phase_id') && $request->phase_id) {
            $query->where('phase_id', $request->phase_id);
        }

        // Filter by district if provided
        if ($request->has('district_id') && $request->district_id) {
            $query->whereHas('phase', function($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        // Filter by scheme if provided
        if ($request->has('scheme_id') && $request->scheme_id) {
            $query->where('scheme_id', $request->scheme_id);
        }

        $beneficiaries = $query->orderBy('approved_at', 'asc')->get();

        $filename = 'approved_beneficiaries_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($beneficiaries) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Sr. No.',
                'CNIC',
                'Name',
                'Father/Husband Name',
                'Mobile Number',
                'Date of Birth',
                'Gender',
                'District',
                'Scheme',
                'Scheme Category',
                'Amount',
                'Representative CNIC',
                'Representative Name',
                'Representative Mobile',
                'Local Zakat Committee',
                'Phase',
                'Approved Date'
            ]);

            $srNo = 1;
            foreach ($beneficiaries as $beneficiary) {
                // Use representative CNIC if exists, otherwise beneficiary CNIC
                $cnic = $beneficiary->requires_representative && $beneficiary->representative 
                    ? $beneficiary->representative->cnic 
                    : $beneficiary->cnic;
                
                $name = $beneficiary->full_name;
                $mobile = $beneficiary->mobile_number;
                
                // If representative exists, use their details for JazzCash
                if ($beneficiary->requires_representative && $beneficiary->representative) {
                    $repCnic = $beneficiary->representative->cnic;
                    $repName = $beneficiary->representative->full_name;
                    $repMobile = $beneficiary->representative->mobile_number;
                } else {
                    $repCnic = '';
                    $repName = '';
                    $repMobile = '';
                }

                fputcsv($file, [
                    $srNo++,
                    $cnic,
                    $name,
                    $beneficiary->father_husband_name,
                    $mobile,
                    $beneficiary->date_of_birth->format('Y-m-d'),
                    ucfirst($beneficiary->gender),
                    $beneficiary->phase->district->name ?? '',
                    $beneficiary->scheme->name ?? '',
                    $beneficiary->schemeCategory->name ?? '',
                    number_format($beneficiary->amount, 2),
                    $repCnic,
                    $repName,
                    $repMobile,
                    $beneficiary->localZakatCommittee->name ?? '',
                    $beneficiary->phase->name ?? '',
                    $beneficiary->approved_at ? $beneficiary->approved_at->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function approve(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'admin_remarks' => 'nullable|string',
        ]);

        $beneficiary->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_remarks' => $request->admin_remarks,
        ]);

        // Create notification
        if ($beneficiary->submittedBy) {
            \App\Models\Notification::create([
                'user_id' => $beneficiary->submitted_by,
                'type' => 'beneficiary_approved',
                'title' => 'Beneficiary Approved',
                'message' => 'Beneficiary ' . $beneficiary->full_name . ' (CNIC: ' . $beneficiary->cnic . ') has been approved.',
                'notifiable_type' => Beneficiary::class,
                'notifiable_id' => $beneficiary->id,
            ]);
        }

        // Handle AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Beneficiary approved successfully.'
            ]);
        }

        // Redirect back to the page that called this action
        $redirectTo = $request->get('redirect_to', route('beneficiaries.show', $beneficiary));
        return redirect($redirectTo)
            ->with('success', 'Beneficiary approved successfully.');
    }

    public function reject(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'admin_remarks' => 'required|string',
        ]);

        $beneficiary->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'admin_remarks' => $request->admin_remarks,
        ]);

        // Create notification
        if ($beneficiary->submittedBy) {
            \App\Models\Notification::create([
                'user_id' => $beneficiary->submitted_by,
                'type' => 'beneficiary_rejected',
                'title' => 'Beneficiary Rejected',
                'message' => 'Beneficiary ' . $beneficiary->full_name . ' (CNIC: ' . $beneficiary->cnic . ') has been rejected. Remarks: ' . $request->admin_remarks,
                'notifiable_type' => Beneficiary::class,
                'notifiable_id' => $beneficiary->id,
            ]);
        }

        // Handle AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Beneficiary rejected successfully.'
            ]);
        }

        // Redirect back to the page that called this action
        $redirectTo = $request->get('redirect_to', route('beneficiaries.show', $beneficiary));
        return redirect($redirectTo)
            ->with('success', 'Beneficiary rejected successfully.');
    }

    public function approvedCases()
    {
        $user = auth()->user();
        $query = Beneficiary::where('status', 'approved')
            ->with(['phase.district', 'phase.installment.fundAllocation.financialYear', 'scheme', 'schemeCategory', 'localZakatCommittee', 'approvedBy']);

        // Filter by district if user is district user
        if ($user->isDistrictUser() && $user->district_id) {
            $query->whereHas('phase', function($q) use ($user) {
                $q->where('district_id', $user->district_id);
            });
        }

        $beneficiaries = $query->orderBy('approved_at', 'desc')->get();

        // Get filter options
        $districtsQuery = District::where('is_active', true)->orderBy('name');
        if ($user->isDistrictUser() && $user->district_id) {
            $districtsQuery->where('id', $user->district_id);
        }
        $districts = $districtsQuery->get();

        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        
        $financialYears = \App\Models\FinancialYear::orderBy('name', 'desc')->get();

        return view('admin-hq.approved-cases', compact('beneficiaries', 'districts', 'schemes', 'financialYears'));
    }

    public function rejectedCases()
    {
        $user = auth()->user();
        $query = Beneficiary::where('status', 'rejected')
            ->with(['phase.district', 'phase.installment.fundAllocation.financialYear', 'scheme', 'schemeCategory', 'localZakatCommittee']);

        // Filter by district if user is district user
        if ($user->isDistrictUser() && $user->district_id) {
            $query->whereHas('phase', function($q) use ($user) {
                $q->where('district_id', $user->district_id);
            });
        }

        $beneficiaries = $query->orderBy('rejected_at', 'desc')->get();

        // Get filter options
        $districtsQuery = District::where('is_active', true)->orderBy('name');
        if ($user->isDistrictUser() && $user->district_id) {
            $districtsQuery->where('id', $user->district_id);
        }
        $districts = $districtsQuery->get();

        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        
        $financialYears = \App\Models\FinancialYear::orderBy('name', 'desc')->get();

        return view('admin-hq.rejected-cases', compact('beneficiaries', 'districts', 'schemes', 'financialYears'));
    }

    public function showApprovedCase(Beneficiary $beneficiary)
    {
        // Check if beneficiary is approved
        if ($beneficiary->status !== 'approved') {
            abort(404, 'Beneficiary is not approved.');
        }

        $user = auth()->user();
        
        // Check if district user can access this beneficiary
        if ($user->isDistrictUser() && $user->district_id) {
            if ($beneficiary->phase->district_id !== $user->district_id) {
                abort(403, 'You can only view beneficiaries from your district.');
            }
        }

        $beneficiary->load([
            'phase.district',
            'scheme',
            'schemeCategory',
            'localZakatCommittee',
            'representative',
            'approvedBy',
            'submittedBy'
        ]);

        return view('admin-hq.show-approved-case', compact('beneficiary'));
    }

    public function showRejectedCase(Beneficiary $beneficiary)
    {
        // Check if beneficiary is rejected
        if ($beneficiary->status !== 'rejected') {
            abort(404, 'Beneficiary is not rejected.');
        }

        $user = auth()->user();
        
        // Check if district user can access this beneficiary
        if ($user->isDistrictUser() && $user->district_id) {
            if ($beneficiary->phase->district_id !== $user->district_id) {
                abort(403, 'You can only view beneficiaries from your district.');
            }
        }

        $beneficiary->load([
            'phase.district',
            'scheme',
            'schemeCategory',
            'localZakatCommittee',
            'representative',
            'submittedBy'
        ]);

        return view('admin-hq.show-rejected-case', compact('beneficiary'));
    }

    public function allCases()
    {
        $user = auth()->user();
        
        // Base query with common relationships
        $baseQuery = function($status = null) use ($user) {
            $query = Beneficiary::with([
                'phase.district', 
                'phase.installment.fundAllocation.financialYear', 
                'scheme', 
                'schemeCategory', 
                'localZakatCommittee', 
                'submittedBy',
                'approvedBy'
            ]);

            if ($status) {
                $query->where('status', $status);
            }

            // Filter by district if user is district user
            if ($user->isDistrictUser() && $user->district_id) {
                $query->whereHas('phase', function($q) use ($user) {
                    $q->where('district_id', $user->district_id);
                });
            }

            return $query;
        };

        // Get all cases
        $pendingBeneficiaries = (clone $baseQuery('submitted'))->orderBy('submitted_at', 'asc')->get();
        $approvedBeneficiaries = (clone $baseQuery('approved'))->orderBy('approved_at', 'desc')->get();
        $rejectedBeneficiaries = (clone $baseQuery('rejected'))->orderBy('rejected_at', 'desc')->get();
        
        // Paid cases: status = 'paid' and jazzcash_status = 'SUCCESS'
        $paidQuery = (clone $baseQuery('paid'))
            ->where('jazzcash_status', 'SUCCESS')
            ->orderBy('payment_received_at', 'desc');
        $paidBeneficiaries = $paidQuery->get();
        
        // Payment failed cases: status = 'approved' but jazzcash_status is not 'SUCCESS' or is null
        $paymentFailedQuery = (clone $baseQuery('approved'))
            ->where(function($q) {
                $q->whereNull('jazzcash_status')
                  ->orWhere('jazzcash_status', '!=', 'SUCCESS');
            })
            ->whereNotNull('jazzcash_status') // Only those that have been processed by JazzCash
            ->orderBy('updated_at', 'desc');
        $paymentFailedBeneficiaries = $paymentFailedQuery->get();

        // Get filter options
        $districtsQuery = District::where('is_active', true)->orderBy('name');
        if ($user->isDistrictUser() && $user->district_id) {
            $districtsQuery->where('id', $user->district_id);
        }
        $districts = $districtsQuery->get();

        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        
        $financialYears = \App\Models\FinancialYear::orderBy('name', 'desc')->get();

        return view('admin-hq.all-cases', compact(
            'pendingBeneficiaries',
            'approvedBeneficiaries',
            'rejectedBeneficiaries',
            'paidBeneficiaries',
            'paymentFailedBeneficiaries',
            'districts',
            'schemes',
            'financialYears'
        ));
    }

    public function showPaidCase(Beneficiary $beneficiary)
    {
        // Check if beneficiary is paid
        if ($beneficiary->status !== 'paid' || $beneficiary->jazzcash_status !== 'SUCCESS') {
            abort(404, 'Beneficiary is not paid.');
        }

        $user = auth()->user();
        
        // Check if district user can access this beneficiary
        if ($user->isDistrictUser() && $user->district_id) {
            if ($beneficiary->phase->district_id !== $user->district_id) {
                abort(403, 'You can only view beneficiaries from your district.');
            }
        }

        $beneficiary->load([
            'phase.district',
            'scheme',
            'schemeCategory',
            'localZakatCommittee',
            'representative',
            'approvedBy',
            'submittedBy'
        ]);

        return view('admin-hq.show-paid-case', compact('beneficiary'));
    }

    public function showPaymentFailedCase(Beneficiary $beneficiary)
    {
        // Check if beneficiary payment failed
        if ($beneficiary->status !== 'approved' || 
            ($beneficiary->jazzcash_status === 'SUCCESS' || empty($beneficiary->jazzcash_status))) {
            abort(404, 'Beneficiary payment did not fail.');
        }

        $user = auth()->user();
        
        // Check if district user can access this beneficiary
        if ($user->isDistrictUser() && $user->district_id) {
            if ($beneficiary->phase->district_id !== $user->district_id) {
                abort(403, 'You can only view beneficiaries from your district.');
            }
        }

        $beneficiary->load([
            'phase.district',
            'scheme',
            'schemeCategory',
            'localZakatCommittee',
            'representative',
            'approvedBy',
            'submittedBy'
        ]);

        return view('admin-hq.show-payment-failed-case', compact('beneficiary'));
    }
}
