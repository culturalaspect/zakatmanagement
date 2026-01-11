<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Http\Request;

class FinancialYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financialYears = FinancialYear::orderBy('start_date', 'desc')->get();
        return view('financial-years.index', compact('financialYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:financial_years,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
            'total_allocation' => 'nullable|numeric|min:0',
        ]);

        // If setting as current, unset all others
        if ($request->has('is_current') && $request->is_current) {
            FinancialYear::where('is_current', true)->update(['is_current' => false]);
        }

        FinancialYear::create($validated);

        return redirect()->route('financial-years.index')
            ->with('success', 'Financial Year created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialYear $financialYear)
    {
        $financialYear->load(['fundAllocations' => function($query) {
            $query->orderBy('installment_number', 'asc');
        }, 'fundAllocations.districtQuotas.district', 'fundAllocations.districtQuotas.schemeDistributions.scheme']);
        return view('financial-years.show', compact('financialYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialYear $financialYear)
    {
        return view('financial-years.edit', compact('financialYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialYear $financialYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:financial_years,name,' . $financialYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
            'total_allocation' => 'nullable|numeric|min:0',
        ]);

        // If setting as current, unset all others
        if ($request->has('is_current') && $request->is_current) {
            FinancialYear::where('is_current', true)
                ->where('id', '!=', $financialYear->id)
                ->update(['is_current' => false]);
        }

        $financialYear->update($validated);

        return redirect()->route('financial-years.index')
            ->with('success', 'Financial Year updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialYear $financialYear)
    {
        // Check if there are any fund allocations using this financial year
        if ($financialYear->fundAllocations()->count() > 0) {
            return redirect()->route('financial-years.index')
                ->with('error', 'Cannot delete financial year. It has associated fund allocations.');
        }

        $financialYear->delete();

        return redirect()->route('financial-years.index')
            ->with('success', 'Financial Year deleted successfully.');
    }

    /**
     * Set a financial year as current
     */
    public function setCurrent(FinancialYear $financialYear)
    {
        $financialYear->setAsCurrent();

        return redirect()->route('financial-years.index')
            ->with('success', 'Financial Year set as current successfully.');
    }
}
