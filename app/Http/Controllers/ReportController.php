<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\District;
use App\Models\Scheme;
use App\Models\FundAllocation;
use App\Models\Phase;
use App\Models\Installment;
use App\Models\FinancialYear;
use App\Models\LocalZakatCommittee;
use App\Models\Institution;
use App\Services\ReportExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Apply role-based scope to beneficiary query (district user / institution user).
     */
    protected function scopeBeneficiaries($query)
    {
        $user = auth()->user();
        if ($user->isDistrictUser() && $user->district_id) {
            $query->whereHas('phase', fn($q) => $q->where('district_id', $user->district_id));
        }
        if ($user->isInstitutionUser() && $user->institution_id) {
            $query->where('institution_id', $user->institution_id);
        }
        return $query;
    }

    /**
     * Apply role-based scope to phase query.
     */
    protected function scopePhases($query)
    {
        $user = auth()->user();
        if ($user->isDistrictUser() && $user->district_id) {
            $query->where('district_id', $user->district_id);
        }
        return $query;
    }

    /**
     * Filter by fund allocation (via phase.installment).
     */
    protected function filterByFundAllocation($query, $fundAllocationId)
    {
        if (!$fundAllocationId) {
            return $query;
        }
        return $query->whereHas('phase.installment', fn($q) => $q->where('fund_allocation_id', $fundAllocationId));
    }

    public function index()
    {
        return view('reports.index');
    }

    // ==================== Executive Summary ====================

    public function executiveSummary(Request $request)
    {
        $user = auth()->user();
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();
        $fundAllocationId = $request->get('fund_allocation_id');

        $beneficiaryQuery = Beneficiary::query();
        $this->scopeBeneficiaries($beneficiaryQuery);
        $this->filterByFundAllocation($beneficiaryQuery, $fundAllocationId);

        $data = [
            'total_beneficiaries' => (clone $beneficiaryQuery)->count(),
            'pending' => (clone $beneficiaryQuery)->where('status', 'pending')->count(),
            'submitted' => (clone $beneficiaryQuery)->where('status', 'submitted')->count(),
            'approved' => (clone $beneficiaryQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $beneficiaryQuery)->where('status', 'rejected')->count(),
            'paid' => (clone $beneficiaryQuery)->where('status', 'paid')->count(),
            'payment_failed' => (clone $beneficiaryQuery)->whereNotNull('jazzcash_status')->where('jazzcash_status', '!=', 'SUCCESS')->count(),
            'total_amount_disbursed' => (clone $beneficiaryQuery)->whereIn('status', ['approved', 'paid'])->sum('amount'),
            'total_amount_paid' => (clone $beneficiaryQuery)->where('status', 'paid')->sum('amount'),
            'total_funds_allocated' => $fundAllocationId
                ? FundAllocation::where('id', $fundAllocationId)->value('total_amount') ?? 0
                : FundAllocation::sum('total_amount'),
            'districts_count' => District::where('is_active', true)->when($user->isDistrictUser() && $user->district_id, fn($q) => $q->where('id', $user->district_id))->count(),
            'schemes_count' => Scheme::where('is_active', true)->count(),
            'phases_open' => Phase::when($user->isDistrictUser() && $user->district_id, fn($q) => $q->where('district_id', $user->district_id))->where('status', 'open')->count(),
        ];

        if ($request->get('export') === 'excel') {
            return $this->exportExecutiveSummaryExcel($data, $allocations, $fundAllocationId);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportExecutiveSummaryPdf($data, $allocations, $fundAllocationId);
        }

        return view('reports.executive-summary', compact('data', 'allocations'));
    }

    protected function exportExecutiveSummaryExcel($data, $allocations, $fundAllocationId)
    {
        $headers = ['Metric', 'Value'];
        $rows = [
            ['Total Beneficiaries', $data['total_beneficiaries']],
            ['Pending', $data['pending']],
            ['Submitted', $data['submitted']],
            ['Approved', $data['approved']],
            ['Rejected', $data['rejected']],
            ['Paid', $data['paid']],
            ['Payment Failed', $data['payment_failed']],
            ['Total Amount Disbursed (Rs.)', number_format($data['total_amount_disbursed'], 2)],
            ['Total Amount Paid (Rs.)', number_format($data['total_amount_paid'], 2)],
            ['Total Funds Allocated (Rs.)', number_format($data['total_funds_allocated'], 2)],
            ['Districts', $data['districts_count']],
            ['Schemes', $data['schemes_count']],
            ['Open Phases', $data['phases_open']],
        ];
        $filename = 'executive_summary_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Executive Summary', $headers, $rows);
    }

    protected function exportExecutiveSummaryPdf($data, $allocations, $fundAllocationId)
    {
        $pdf = Pdf::loadView('reports.pdf.executive-summary', compact('data', 'allocations', 'fundAllocationId'));
        return $pdf->download('executive_summary_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== District-wise ====================

    public function districtWise(Request $request)
    {
        $query = Beneficiary::with(['phase.district', 'scheme'])
            ->whereIn('status', ['approved', 'paid']);
        $this->scopeBeneficiaries($query);
        if ($request->filled('district_id')) {
            $query->whereHas('phase', fn($q) => $q->where('district_id', $request->district_id));
        }
        $this->filterByFundAllocation($query, $request->fund_allocation_id);

        $beneficiaries = $query->get();
        $districts = District::where('is_active', true)->orderBy('name')->get();
        if (auth()->user()->isDistrictUser() && auth()->user()->district_id) {
            $districts = $districts->where('id', auth()->user()->district_id);
        }
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();

        $districtStats = $beneficiaries->groupBy(function ($b) {
            return $b->phase && $b->phase->district ? $b->phase->district->name : 'N/A';
        })->map(fn($items) => [
            'count' => $items->count(),
            'amount' => $items->sum('amount'),
        ]);

        if ($request->get('export') === 'excel') {
            return $this->exportDistrictWiseExcel($districtStats);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportDistrictWisePdf($districtStats);
        }

        return view('reports.district-wise', compact('districtStats', 'districts', 'allocations'));
    }

    protected function exportDistrictWiseExcel($districtStats)
    {
        $headers = ['District', 'Beneficiaries Count', 'Total Amount (Rs.)'];
        $rows = $districtStats->map(fn($stat, $name) => [$name, $stat['count'], number_format($stat['amount'], 2)])->values()->toArray();
        $filename = 'district_wise_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'District Wise', $headers, $rows);
    }

    protected function exportDistrictWisePdf($districtStats)
    {
        $pdf = Pdf::loadView('reports.pdf.district-wise', compact('districtStats'));
        return $pdf->download('district_wise_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Scheme-wise ====================

    public function schemeWise(Request $request)
    {
        $query = Beneficiary::with(['scheme'])
            ->whereIn('status', ['approved', 'paid']);
        $this->scopeBeneficiaries($query);
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }
        $this->filterByFundAllocation($query, $request->fund_allocation_id);

        $beneficiaries = $query->get();
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();

        $schemeStats = $beneficiaries->groupBy(fn($b) => $b->scheme ? $b->scheme->name : 'N/A')
            ->map(fn($items) => ['count' => $items->count(), 'amount' => $items->sum('amount')]);

        if ($request->get('export') === 'excel') {
            return $this->exportSchemeWiseExcel($schemeStats);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportSchemeWisePdf($schemeStats);
        }

        return view('reports.scheme-wise', compact('schemeStats', 'schemes', 'allocations'));
    }

    protected function exportSchemeWiseExcel($schemeStats)
    {
        $headers = ['Scheme', 'Beneficiaries Count', 'Total Amount (Rs.)'];
        $rows = $schemeStats->map(fn($stat, $name) => [$name, $stat['count'], number_format($stat['amount'], 2)])->values()->toArray();
        $filename = 'scheme_wise_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Scheme Wise', $headers, $rows);
    }

    protected function exportSchemeWisePdf($schemeStats)
    {
        $pdf = Pdf::loadView('reports.pdf.scheme-wise', compact('schemeStats'));
        return $pdf->download('scheme_wise_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Beneficiary Status ====================

    public function beneficiaryStatus(Request $request)
    {
        $query = Beneficiary::query();
        $this->scopeBeneficiaries($query);
        $this->filterByFundAllocation($query, $request->fund_allocation_id);
        if ($request->filled('district_id')) {
            $query->whereHas('phase', fn($q) => $q->where('district_id', $request->district_id));
        }
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        $statusCounts = (clone $query)->select('status', DB::raw('count(*) as count'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $statuses = ['pending', 'submitted', 'approved', 'rejected', 'paid'];
        $stats = [];
        foreach ($statuses as $s) {
            $row = $statusCounts->get($s);
            $stats[$s] = [
                'count' => $row ? $row->count : 0,
                'amount' => $row ? $row->total_amount : 0,
            ];
        }
        $paymentFailed = (clone $query)->whereNotNull('jazzcash_status')->where('jazzcash_status', '!=', 'SUCCESS')->count();
        $stats['payment_failed'] = ['count' => $paymentFailed, 'amount' => 0];

        $districts = District::where('is_active', true)->orderBy('name')->get();
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();

        if ($request->get('export') === 'excel') {
            return $this->exportBeneficiaryStatusExcel($stats);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportBeneficiaryStatusPdf($stats);
        }

        return view('reports.beneficiary-status', compact('stats', 'districts', 'schemes', 'allocations'));
    }

    protected function exportBeneficiaryStatusExcel($stats)
    {
        $headers = ['Status', 'Count', 'Total Amount (Rs.)'];
        $rows = [];
        foreach ($stats as $status => $s) {
            $rows[] = [ucfirst(str_replace('_', ' ', $status)), $s['count'], number_format($s['amount'], 2)];
        }
        $filename = 'beneficiary_status_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Beneficiary Status', $headers, $rows);
    }

    protected function exportBeneficiaryStatusPdf($stats)
    {
        $pdf = Pdf::loadView('reports.pdf.beneficiary-status', compact('stats'));
        return $pdf->download('beneficiary_status_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Phase-wise ====================

    public function phaseWise(Request $request)
    {
        $query = Phase::with(['district', 'scheme', 'installment.fundAllocation.financialYear']);
        $this->scopePhases($query);
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        if ($request->filled('fund_allocation_id')) {
            $query->whereHas('installment', fn($q) => $q->where('fund_allocation_id', $request->fund_allocation_id));
        }

        $phases = $query->orderBy('start_date', 'desc')->get()->map(function ($phase) {
            $phase->beneficiaries_count = $phase->beneficiaries()->whereIn('status', ['submitted', 'approved', 'paid'])->count();
            $phase->beneficiaries_amount = $phase->beneficiaries()->whereIn('status', ['submitted', 'approved', 'paid'])->sum('amount');
            return $phase;
        });

        $districts = District::where('is_active', true)->orderBy('name')->get();
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();

        if ($request->get('export') === 'excel') {
            return $this->exportPhaseWiseExcel($phases);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportPhaseWisePdf($phases);
        }

        return view('reports.phase-wise', compact('phases', 'districts', 'allocations'));
    }

    protected function exportPhaseWiseExcel($phases)
    {
        $headers = ['Phase', 'District', 'Scheme', 'Financial Year', 'Installment', 'Status', 'Beneficiaries', 'Amount (Rs.)'];
        $rows = $phases->map(fn($p) => [
            $p->name,
            $p->district->name ?? '',
            $p->scheme->name ?? '',
            $p->installment->fundAllocation->financialYear->name ?? '',
            $p->installment->installment_number ?? '',
            $p->status ?? '',
            $p->beneficiaries_count ?? 0,
            number_format($p->beneficiaries_amount ?? 0, 2),
        ])->toArray();
        $filename = 'phase_wise_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Phase Wise', $headers, $rows);
    }

    protected function exportPhaseWisePdf($phases)
    {
        $pdf = Pdf::loadView('reports.pdf.phase-wise', compact('phases'));
        return $pdf->download('phase_wise_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Fund Disbursement ====================

    public function fundDisbursement(Request $request)
    {
        $allocations = FundAllocation::with(['financialYear', 'installments'])
            ->when($request->filled('financial_year_id'), fn($q) => $q->where('financial_year_id', $request->financial_year_id))
            ->orderBy('date', 'desc')
            ->get();

        $rows = [];
        foreach ($allocations as $fa) {
            $totalAllocated = $fa->total_amount;
            $installmentsSum = $fa->installments->sum('installment_amount');
            $beneficiaryQuery = Beneficiary::whereHas('phase.installment', fn($q) => $q->where('fund_allocation_id', $fa->id))
                ->whereIn('status', ['approved', 'paid']);
            $this->scopeBeneficiaries($beneficiaryQuery);
            $disbursed = $beneficiaryQuery->sum('amount');
            $paid = (clone $beneficiaryQuery)->where('status', 'paid')->sum('amount');
            $rows[] = [
                'financial_year' => $fa->financialYear->name ?? 'N/A',
                'date' => $fa->date?->format('d M Y'),
                'total_amount' => $totalAllocated,
                'installments_total' => $installmentsSum,
                'disbursed' => $disbursed,
                'paid' => $paid,
                'remaining' => $totalAllocated - $disbursed,
            ];
        }

        $financialYears = FinancialYear::orderBy('name', 'desc')->get();

        if ($request->get('export') === 'excel') {
            return $this->exportFundDisbursementExcel($rows);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportFundDisbursementPdf($rows);
        }

        return view('reports.fund-disbursement', compact('rows', 'financialYears'));
    }

    protected function exportFundDisbursementExcel($rows)
    {
        $headers = ['Financial Year', 'Date', 'Total Allocated (Rs.)', 'Installments Total (Rs.)', 'Disbursed (Rs.)', 'Paid (Rs.)', 'Remaining (Rs.)'];
        $data = array_map(fn($r) => [
            $r['financial_year'],
            $r['date'],
            number_format($r['total_amount'], 2),
            number_format($r['installments_total'], 2),
            number_format($r['disbursed'], 2),
            number_format($r['paid'], 2),
            number_format($r['remaining'], 2),
        ], $rows);
        $filename = 'fund_disbursement_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Fund Disbursement', $headers, $data);
    }

    protected function exportFundDisbursementPdf($rows)
    {
        $pdf = Pdf::loadView('reports.pdf.fund-disbursement', compact('rows'));
        return $pdf->download('fund_disbursement_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Financial Year Summary ====================

    public function financialYearSummary(Request $request)
    {
        $years = FinancialYear::with('fundAllocations')->orderBy('name', 'desc')->get()->map(function ($fy) {
            $faIds = $fy->fundAllocations->pluck('id');
            $totalAllocated = $fy->fundAllocations->sum('total_amount');
            $beneficiaryQuery = Beneficiary::whereHas('phase.installment', fn($q) => $q->whereIn('fund_allocation_id', $faIds))
                ->whereIn('status', ['approved', 'paid']);
            $this->scopeBeneficiaries($beneficiaryQuery);
            $fy->beneficiaries_count = (clone $beneficiaryQuery)->count();
            $fy->amount_disbursed = (clone $beneficiaryQuery)->sum('amount');
            $fy->amount_paid = (clone $beneficiaryQuery)->where('status', 'paid')->sum('amount');
            $fy->total_allocated = $totalAllocated;
            return $fy;
        });

        if ($request->get('export') === 'excel') {
            return $this->exportFinancialYearExcel($years);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportFinancialYearPdf($years);
        }

        return view('reports.financial-year-summary', compact('years'));
    }

    protected function exportFinancialYearExcel($years)
    {
        $headers = ['Financial Year', 'Start', 'End', 'Total Allocated (Rs.)', 'Beneficiaries', 'Disbursed (Rs.)', 'Paid (Rs.)'];
        $rows = $years->map(fn($fy) => [
            $fy->name,
            $fy->start_date?->format('d M Y'),
            $fy->end_date?->format('d M Y'),
            number_format($fy->total_allocated ?? 0, 2),
            $fy->beneficiaries_count ?? 0,
            number_format($fy->amount_disbursed ?? 0, 2),
            number_format($fy->amount_paid ?? 0, 2),
        ])->toArray();
        $filename = 'financial_year_summary_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Financial Year Summary', $headers, $rows);
    }

    protected function exportFinancialYearPdf($years)
    {
        $pdf = Pdf::loadView('reports.pdf.financial-year-summary', compact('years'));
        return $pdf->download('financial_year_summary_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== LZC / Committee-wise ====================

    public function lzcWise(Request $request)
    {
        $query = Beneficiary::with(['localZakatCommittee.district', 'scheme'])
            ->whereIn('status', ['approved', 'paid']);
        $this->scopeBeneficiaries($query);
        if ($request->filled('district_id')) {
            $query->whereHas('phase', fn($q) => $q->where('district_id', $request->district_id));
        }
        $this->filterByFundAllocation($query, $request->fund_allocation_id);

        $beneficiaries = $query->get();
        $lzcStats = $beneficiaries->groupBy(function ($b) {
            return $b->localZakatCommittee ? $b->localZakatCommittee->name . ' (' . ($b->localZakatCommittee->district->name ?? '') . ')' : 'N/A';
        })->map(fn($items) => ['count' => $items->count(), 'amount' => $items->sum('amount')]);

        $districts = District::where('is_active', true)->orderBy('name')->get();
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();

        if ($request->get('export') === 'excel') {
            return $this->exportLzcWiseExcel($lzcStats);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportLzcWisePdf($lzcStats);
        }

        return view('reports.lzc-wise', compact('lzcStats', 'districts', 'allocations'));
    }

    protected function exportLzcWiseExcel($lzcStats)
    {
        $headers = ['Local Zakat Committee', 'Beneficiaries Count', 'Total Amount (Rs.)'];
        $rows = $lzcStats->map(fn($stat, $name) => [$name, $stat['count'], number_format($stat['amount'], 2)])->values()->toArray();
        $filename = 'lzc_wise_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'LZC Wise', $headers, $rows);
    }

    protected function exportLzcWisePdf($lzcStats)
    {
        $pdf = Pdf::loadView('reports.pdf.lzc-wise', compact('lzcStats'));
        return $pdf->download('lzc_wise_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Institution-wise ====================

    public function institutionWise(Request $request)
    {
        $query = Beneficiary::with(['institution', 'scheme'])
            ->whereIn('status', ['approved', 'paid'])
            ->whereNotNull('institution_id');
        $this->scopeBeneficiaries($query);
        if ($request->filled('district_id')) {
            $query->whereHas('phase', fn($q) => $q->where('district_id', $request->district_id));
        }

        $beneficiaries = $query->get();
        $instStats = $beneficiaries->groupBy(fn($b) => $b->institution ? $b->institution->name : 'N/A')
            ->map(fn($items) => ['count' => $items->count(), 'amount' => $items->sum('amount')]);

        $districts = District::where('is_active', true)->orderBy('name')->get();

        if ($request->get('export') === 'excel') {
            return $this->exportInstitutionWiseExcel($instStats);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportInstitutionWisePdf($instStats);
        }

        return view('reports.institution-wise', compact('instStats', 'districts'));
    }

    protected function exportInstitutionWiseExcel($instStats)
    {
        $headers = ['Institution', 'Beneficiaries Count', 'Total Amount (Rs.)'];
        $rows = $instStats->map(fn($stat, $name) => [$name, $stat['count'], number_format($stat['amount'], 2)])->values()->toArray();
        $filename = 'institution_wise_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Institution Wise', $headers, $rows);
    }

    protected function exportInstitutionWisePdf($instStats)
    {
        $pdf = Pdf::loadView('reports.pdf.institution-wise', compact('instStats'));
        return $pdf->download('institution_wise_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Payment Report ====================

    public function paymentReport(Request $request)
    {
        $query = Beneficiary::with(['phase.district', 'scheme', 'representative'])
            ->whereIn('status', ['approved', 'paid']);
        $this->scopeBeneficiaries($query);
        if ($request->filled('district_id')) {
            $query->whereHas('phase', fn($q) => $q->where('district_id', $request->district_id));
        }
        $this->filterByFundAllocation($query, $request->fund_allocation_id);

        $paid = (clone $query)->where('status', 'paid')->get();
        $approved = (clone $query)->where('status', 'approved')->get();
        $paymentFailed = Beneficiary::with(['phase.district', 'scheme'])
            ->whereNotNull('jazzcash_status')
            ->where('jazzcash_status', '!=', 'SUCCESS')
            ->when(auth()->user()->isDistrictUser() && auth()->user()->district_id, fn($q) => $q->whereHas('phase', fn($q2) => $q2->where('district_id', auth()->user()->district_id)))
            ->when(auth()->user()->isInstitutionUser() && auth()->user()->institution_id, fn($q) => $q->where('institution_id', auth()->user()->institution_id))
            ->get();

        $summary = [
            'paid_count' => $paid->count(),
            'paid_amount' => $paid->sum('amount'),
            'pending_count' => $approved->count(),
            'pending_amount' => $approved->sum('amount'),
            'failed_count' => $paymentFailed->count(),
            'failed_amount' => $paymentFailed->sum('amount'),
        ];

        $districts = District::where('is_active', true)->orderBy('name')->get();
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();

        if ($request->get('export') === 'excel') {
            return $this->exportPaymentReportExcel($paid, $approved, $paymentFailed, $summary);
        }
        if ($request->get('export') === 'pdf') {
            return $this->exportPaymentReportPdf($paid, $approved, $paymentFailed, $summary);
        }

        return view('reports.payment-report', compact('paid', 'approved', 'paymentFailed', 'summary', 'districts', 'allocations'));
    }

    protected function exportPaymentReportExcel($paid, $approved, $paymentFailed, $summary)
    {
        $headers = ['Status', 'CNIC', 'Name', 'District', 'Scheme', 'Amount (Rs.)', 'JazzCash Status', 'Remarks'];
        $rows = [];
        foreach ($paid as $b) {
            $rows[] = ['Paid', $b->cnic ?? $b->representative?->cnic, $b->full_name, $b->phase->district->name ?? '', $b->scheme->name ?? '', number_format($b->amount, 2), $b->jazzcash_status ?? 'SUCCESS', $b->jazzcash_reason ?? ''];
        }
        foreach ($approved as $b) {
            $rows[] = ['Pending Payment', $b->cnic ?? $b->representative?->cnic, $b->full_name, $b->phase->district->name ?? '', $b->scheme->name ?? '', number_format($b->amount, 2), '-', ''];
        }
        foreach ($paymentFailed as $b) {
            $rows[] = ['Payment Failed', $b->cnic ?? $b->representative?->cnic, $b->full_name, $b->phase->district->name ?? '', $b->scheme->name ?? '', number_format($b->amount, 2), $b->jazzcash_status ?? '', $b->jazzcash_reason ?? ''];
        }
        $filename = 'payment_report_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Payment Report', $headers, $rows);
    }

    protected function exportPaymentReportPdf($paid, $approved, $paymentFailed, $summary)
    {
        $pdf = Pdf::loadView('reports.pdf.payment-report', compact('paid', 'approved', 'paymentFailed', 'summary'));
        return $pdf->download('payment_report_' . date('Y-m-d_His') . '.pdf');
    }

    // ==================== Beneficiary List (Detailed) ====================

    public function beneficiaryList(Request $request)
    {
        $query = Beneficiary::with(['phase.district', 'phase.installment.fundAllocation.financialYear', 'scheme', 'schemeCategory', 'localZakatCommittee', 'institution', 'representative']);
        $this->scopeBeneficiaries($query);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['submitted', 'approved', 'paid']);
        }
        if ($request->filled('district_id')) {
            $query->whereHas('phase', fn($q) => $q->where('district_id', $request->district_id));
        }
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }
        $this->filterByFundAllocation($query, $request->fund_allocation_id);
        if ($request->filled('phase_id')) {
            $query->where('phase_id', $request->phase_id);
        }

        $beneficiaries = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();

        $districts = District::where('is_active', true)->orderBy('name')->get();
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $allocations = FundAllocation::with('financialYear')->orderBy('date', 'desc')->get();

        if ($request->get('export') === 'excel') {
            $queryNoPaginate = clone $query;
            $all = $queryNoPaginate->orderBy('created_at', 'desc')->get();
            return $this->exportBeneficiaryListExcel($all);
        }
        if ($request->get('export') === 'pdf') {
            $queryNoPaginate = clone $query;
            $all = $queryNoPaginate->orderBy('created_at', 'desc')->limit(500)->get();
            return $this->exportBeneficiaryListPdf($all);
        }

        return view('reports.beneficiary-list', compact('beneficiaries', 'districts', 'schemes', 'allocations'));
    }

    protected function exportBeneficiaryListExcel($beneficiaries)
    {
        $headers = ['Sr', 'CNIC', 'Name', 'Father/Husband', 'Mobile', 'DOB', 'Gender', 'District', 'Scheme', 'Category', 'Amount (Rs.)', 'Status', 'LZC', 'Institution', 'Phase', 'Submitted At'];
        $rows = [];
        $sr = 1;
        foreach ($beneficiaries as $b) {
            $rows[] = [
                $sr++,
                $b->cnic ?? $b->representative?->cnic ?? '',
                $b->full_name,
                $b->father_husband_name ?? '',
                $b->mobile_number ?? '',
                $b->date_of_birth?->format('d M Y'),
                $b->gender ?? '',
                $b->phase->district->name ?? '',
                $b->scheme->name ?? '',
                $b->schemeCategory->name ?? '',
                number_format($b->amount, 2),
                $b->status,
                $b->localZakatCommittee->name ?? '',
                $b->institution->name ?? '',
                $b->phase->name ?? '',
                $b->submitted_at?->format('d M Y H:i'),
            ];
        }
        $filename = 'beneficiary_list_' . date('Y-m-d_His') . '.xlsx';
        return ReportExportService::excelFromArray($filename, 'Beneficiary List', $headers, $rows);
    }

    protected function exportBeneficiaryListPdf($beneficiaries)
    {
        $pdf = Pdf::loadView('reports.pdf.beneficiary-list', compact('beneficiaries'));
        return $pdf->download('beneficiary_list_' . date('Y-m-d_His') . '.pdf');
    }
}
