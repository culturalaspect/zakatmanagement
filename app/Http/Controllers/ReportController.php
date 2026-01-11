<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\District;
use App\Models\Scheme;
use App\Models\FundAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function districtWise(Request $request)
    {
        $query = Beneficiary::with(['phase.district', 'scheme'])
            ->whereIn('status', ['approved', 'paid']);

        if ($request->has('district_id')) {
            $query->whereHas('phase', function($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        if ($request->has('fund_allocation_id')) {
            $query->whereHas('phase', function($q) use ($request) {
                $q->where('fund_allocation_id', $request->fund_allocation_id);
            });
        }

        $beneficiaries = $query->get();
        $districts = District::where('is_active', true)->get();
        $allocations = FundAllocation::all();

        $districtStats = $beneficiaries->groupBy('phase.district.name')->map(function($items) {
            return [
                'count' => $items->count(),
                'amount' => $items->sum('amount'),
            ];
        });

        return view('reports.district-wise', compact('districtStats', 'districts', 'allocations'));
    }

    public function schemeWise(Request $request)
    {
        $query = Beneficiary::with(['scheme'])
            ->whereIn('status', ['approved', 'paid']);

        if ($request->has('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        if ($request->has('fund_allocation_id')) {
            $query->whereHas('phase', function($q) use ($request) {
                $q->where('fund_allocation_id', $request->fund_allocation_id);
            });
        }

        $beneficiaries = $query->get();
        $schemes = Scheme::where('is_active', true)->get();
        $allocations = FundAllocation::all();

        $schemeStats = $beneficiaries->groupBy('scheme.name')->map(function($items) {
            return [
                'count' => $items->count(),
                'amount' => $items->sum('amount'),
            ];
        });

        return view('reports.scheme-wise', compact('schemeStats', 'schemes', 'allocations'));
    }
}
