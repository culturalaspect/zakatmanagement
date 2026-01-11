<?php

namespace App\Http\Controllers;

use App\Models\FundAllocation;
use App\Models\FinancialYear;
use Illuminate\Http\Request;

class FundAllocationController extends Controller
{
    public function index()
    {
        $allocations = FundAllocation::with('financialYear')
            ->orderBy('created_at', 'desc')
            ->get();
        $financialYears = FinancialYear::orderBy('name', 'desc')->get();
        return view('fund-allocations.index', compact('allocations', 'financialYears'));
    }

    public function create()
    {
        $financialYears = FinancialYear::orderBy('start_date', 'desc')->get();
        return view('fund-allocations.create', compact('financialYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'financial_year_id' => 'required|exists:financial_years,id',
            'total_amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'source' => 'nullable|string',
            'status' => 'required|in:pending,allocated,disbursing,completed',
        ]);

        FundAllocation::create($validated);

        return redirect()->route('fund-allocations.index')
            ->with('success', 'Fund Allocation created successfully.');
    }

    public function show(FundAllocation $fundAllocation)
    {
        $fundAllocation->load(['financialYear', 'installments.districtQuotas.district', 'installments.districtQuotas.schemeDistributions.scheme']);
        return view('fund-allocations.show', compact('fundAllocation'));
    }

    public function edit(FundAllocation $fundAllocation)
    {
        $financialYears = FinancialYear::orderBy('start_date', 'desc')->get();
        $fundAllocation->load('financialYear');
        return view('fund-allocations.edit', compact('fundAllocation', 'financialYears'));
    }

    public function update(Request $request, FundAllocation $fundAllocation)
    {
        $validated = $request->validate([
            'financial_year_id' => 'required|exists:financial_years,id',
            'total_amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'source' => 'nullable|string',
            'status' => 'required|in:pending,allocated,disbursing,completed',
        ]);

        $fundAllocation->update($validated);

        return redirect()->route('fund-allocations.index')
            ->with('success', 'Fund Allocation updated successfully.');
    }

    public function destroy(FundAllocation $fundAllocation)
    {
        $fundAllocation->delete();
        return redirect()->route('fund-allocations.index')
            ->with('success', 'Fund Allocation deleted successfully.');
    }
}
