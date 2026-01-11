<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\FundAllocation;
use App\Models\District;
use App\Models\DistrictQuota;
use App\Models\SchemeDistribution;
use App\Models\Scheme;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function create(FundAllocation $fundAllocation)
    {
        $districts = District::where('is_active', true)->get();
        $schemes = Scheme::where('is_active', true)->get();
        
        // Get next installment number
        $nextInstallmentNumber = $fundAllocation->installments()->max('installment_number') + 1;
        
        // Calculate total of existing installments
        $totalExistingInstallments = $fundAllocation->installments()->sum('installment_amount');
        
        // Calculate remaining amount
        $remainingAmount = $fundAllocation->total_amount - $totalExistingInstallments;
        
        // Get old input for form repopulation
        $oldInput = old();
        
        return view('installments.create', compact('fundAllocation', 'districts', 'schemes', 'nextInstallmentNumber', 'totalExistingInstallments', 'remainingAmount', 'oldInput'));
    }

    public function store(Request $request, FundAllocation $fundAllocation)
    {
        // Calculate total of existing installments
        $totalExistingInstallments = $fundAllocation->installments()->sum('installment_amount');
        
        // Calculate remaining amount
        $remainingAmount = $fundAllocation->total_amount - $totalExistingInstallments;
        
        $validated = $request->validate([
            'installment_number' => 'required|integer|min:1',
            'installment_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:' . $remainingAmount,
            ],
            'release_date' => 'required|date',
            'district_quotas' => 'required|array|min:1',
            'district_quotas.*.district_id' => 'required|exists:districts,id',
            'district_quotas.*.percentage' => 'required|numeric|min:0|max:100',
            'district_quotas.*.total_beneficiaries' => 'required|numeric|min:0',
            'district_quotas.*.scheme_distributions' => 'required|array|min:1',
            'district_quotas.*.scheme_distributions.*.scheme_id' => 'required|exists:schemes,id',
            'district_quotas.*.scheme_distributions.*.percentage' => 'required|numeric|min:0|max:100',
            'district_quotas.*.scheme_distributions.*.beneficiaries_count' => 'nullable|numeric|min:0',
        ], [
            'installment_amount.max' => 'The installment amount cannot exceed the remaining amount (Rs. ' . number_format($remainingAmount, 2) . '). Total allocated: Rs. ' . number_format($fundAllocation->total_amount, 2) . ', Already allocated: Rs. ' . number_format($totalExistingInstallments, 2) . '.',
            'district_quotas.min' => 'At least one district quota must be added.',
            'district_quotas.*.scheme_distributions.min' => 'Each district must have at least one scheme.',
        ]);

        // Server-side validation: Check if installment amount exceeds remaining amount
        if ($validated['installment_amount'] > $remainingAmount) {
            return back()->withErrors([
                'installment_amount' => 'The installment amount cannot exceed the remaining amount (Rs. ' . number_format($remainingAmount, 2) . '). Total allocated: Rs. ' . number_format($fundAllocation->total_amount, 2) . ', Already allocated: Rs. ' . number_format($totalExistingInstallments, 2) . '.'
            ])->withInput();
        }

        // Server-side validation: Check if at least one district quota is added
        if (empty($validated['district_quotas']) || count($validated['district_quotas']) === 0) {
            return back()->withErrors([
                'district_quotas' => 'At least one district quota must be added.'
            ])->withInput();
        }

        // Server-side validation: Check if district percentages equal exactly 100% (with tolerance)
        $totalDistrictsPercentage = 0;
        foreach ($validated['district_quotas'] as $quotaData) {
            $totalDistrictsPercentage += $quotaData['percentage'];
        }
        
        // Use tolerance of 0.01% for percentage validation
        if (abs($totalDistrictsPercentage - 100) > 0.01) {
            return back()->withErrors([
                'district_quotas' => 'District percentages must equal exactly 100% (currently ' . number_format($totalDistrictsPercentage, 2) . '%).'
            ])->withInput();
        }

        // Server-side validation: Check if district amounts equal installment amount (with tolerance)
        $totalDistrictsAmount = ($validated['installment_amount'] * $totalDistrictsPercentage) / 100;
        $amountTolerance = max(0.01, $validated['installment_amount'] * 0.0001); // 0.01% tolerance or minimum 0.01
        if (abs($totalDistrictsAmount - $validated['installment_amount']) > $amountTolerance) {
            return back()->withErrors([
                'district_quotas' => 'District total amount (Rs. ' . number_format($totalDistrictsAmount, 2) . ') must equal installment amount (Rs. ' . number_format($validated['installment_amount'], 2) . ').'
            ])->withInput();
        }

        // Server-side validation: Check each district has at least one scheme and percentages equal 100%
        foreach ($validated['district_quotas'] as $index => $quotaData) {
            $district = District::find($quotaData['district_id']);
            $districtName = $district ? $district->name : "District Quota #" . ($index + 1);
            
            // Check if at least one scheme is added
            if (empty($quotaData['scheme_distributions']) || count($quotaData['scheme_distributions']) === 0) {
                return back()->withErrors([
                    'district_quotas.' . $index . '.scheme_distributions' => $districtName . ' must have at least one scheme added.'
                ])->withInput();
            }

            // Check if scheme percentages equal exactly 100% (with tolerance)
            $totalSchemePercentage = 0;
            foreach ($quotaData['scheme_distributions'] as $distribution) {
                $totalSchemePercentage += $distribution['percentage'];
            }
            
            // Use tolerance of 0.01% for percentage validation
            if (abs($totalSchemePercentage - 100) > 0.01) {
                return back()->withErrors([
                    'district_quotas.' . $index . '.scheme_distributions' => $districtName . ' scheme percentages must equal exactly 100% (currently ' . number_format($totalSchemePercentage, 2) . '%).'
                ])->withInput();
            }

            // Check if scheme totals don't exceed district share (with tolerance for floating point precision)
            $districtTotalBeneficiaries = $quotaData['total_beneficiaries'];
            $districtTotalAmount = ($validated['installment_amount'] * $quotaData['percentage']) / 100;
            
            $totalSchemeBeneficiaries = 0;
            $totalSchemeAmount = 0;
            
            foreach ($quotaData['scheme_distributions'] as $distribution) {
                $schemeBeneficiaries = ($distribution['percentage'] / 100) * $districtTotalBeneficiaries;
                $schemeAmount = ($districtTotalAmount * $distribution['percentage']) / 100;
                
                $totalSchemeBeneficiaries += $schemeBeneficiaries;
                $totalSchemeAmount += $schemeAmount;
            }
            
            // Use tolerance for floating point comparison (0.1 for beneficiaries, 0.01 for amounts)
            $beneficiariesTolerance = max(0.1, $districtTotalBeneficiaries * 0.0001); // 0.01% tolerance or minimum 0.1
            $amountTolerance = max(0.01, $districtTotalAmount * 0.0001); // 0.01% tolerance or minimum 0.01
            
            if (($totalSchemeBeneficiaries - $districtTotalBeneficiaries) > $beneficiariesTolerance || 
                ($totalSchemeAmount - $districtTotalAmount) > $amountTolerance) {
                return back()->withErrors([
                    'district_quotas.' . $index . '.scheme_distributions' => $districtName . ' scheme totals exceed district share. Beneficiaries: ' . number_format($totalSchemeBeneficiaries, 1) . ' > ' . number_format($districtTotalBeneficiaries, 1) . ', Amount: Rs. ' . number_format($totalSchemeAmount, 2) . ' > Rs. ' . number_format($districtTotalAmount, 2) . '.'
                ])->withInput();
            }
        }

        $installment = Installment::create([
            'fund_allocation_id' => $fundAllocation->id,
            'installment_number' => $validated['installment_number'],
            'installment_amount' => $validated['installment_amount'],
            'release_date' => $validated['release_date'],
        ]);

        foreach ($request->district_quotas as $quotaData) {
            $quota = DistrictQuota::create([
                'installment_id' => $installment->id,
                'district_id' => $quotaData['district_id'],
                'percentage' => $quotaData['percentage'],
                'total_beneficiaries' => $quotaData['total_beneficiaries'],
                'total_amount' => ($installment->installment_amount * $quotaData['percentage']) / 100,
            ]);

            foreach ($quotaData['scheme_distributions'] as $distribution) {
                SchemeDistribution::create([
                    'district_quota_id' => $quota->id,
                    'scheme_id' => $distribution['scheme_id'],
                    'percentage' => $distribution['percentage'],
                    'amount' => ($quota->total_amount * $distribution['percentage']) / 100,
                    'beneficiaries_count' => $distribution['beneficiaries_count'] ?? 0,
                ]);
            }
        }

        return redirect()->route('fund-allocations.show', $fundAllocation)
            ->with('success', 'Installment created successfully.');
    }

    public function show(Installment $installment)
    {
        $installment->load(['fundAllocation.financialYear', 'districtQuotas.district', 'districtQuotas.schemeDistributions.scheme']);
        return view('installments.show', compact('installment'));
    }

    public function print(Installment $installment)
    {
        $installment->load(['fundAllocation.financialYear', 'districtQuotas.district', 'districtQuotas.schemeDistributions.scheme']);
        return view('installments.print', compact('installment'));
    }

    public function pdf(Installment $installment)
    {
        $installment->load(['fundAllocation.financialYear', 'districtQuotas.district', 'districtQuotas.schemeDistributions.scheme']);
        
        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('installments.pdf', compact('installment'));
        
        $filename = 'Installment_' . $installment->installment_number . '_' . $installment->fundAllocation->financialYear->name . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function edit(Installment $installment)
    {
        $districts = District::where('is_active', true)->get();
        $schemes = Scheme::where('is_active', true)->get();
        $installment->load(['fundAllocation.financialYear', 'districtQuotas.district', 'districtQuotas.schemeDistributions.scheme']);
        
        // Calculate total of existing installments (excluding current installment)
        $totalExistingInstallments = $installment->fundAllocation->installments()
            ->where('id', '!=', $installment->id)
            ->sum('installment_amount');
        
        // Calculate remaining amount (including current installment amount)
        $remainingAmount = $installment->fundAllocation->total_amount - $totalExistingInstallments;
        
        // Get old input for form repopulation
        $oldInput = old();
        
        // Prepare existing installment data for JavaScript
        $existingInstallmentData = [
            'installment_number' => $installment->installment_number,
            'installment_amount' => $installment->installment_amount,
            'release_date' => $installment->release_date->format('Y-m-d'),
            'district_quotas' => $installment->districtQuotas->map(function($quota) {
                return [
                    'district_id' => $quota->district_id,
                    'percentage' => $quota->percentage,
                    'total_beneficiaries' => $quota->total_beneficiaries,
                    'total_amount' => $quota->total_amount,
                    'scheme_distributions' => $quota->schemeDistributions->map(function($dist) {
                        return [
                            'scheme_id' => $dist->scheme_id,
                            'percentage' => $dist->percentage,
                            'beneficiaries_count' => $dist->beneficiaries_count,
                            'amount' => $dist->amount,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ];
        
        return view('installments.edit', compact('installment', 'districts', 'schemes', 'totalExistingInstallments', 'remainingAmount', 'oldInput', 'existingInstallmentData'));
    }

    public function update(Request $request, Installment $installment)
    {
        // Calculate total of existing installments (excluding current installment)
        $totalExistingInstallments = $installment->fundAllocation->installments()
            ->where('id', '!=', $installment->id)
            ->sum('installment_amount');
        
        // Calculate remaining amount (including current installment amount)
        $remainingAmount = $installment->fundAllocation->total_amount - $totalExistingInstallments;
        
        $validated = $request->validate([
            'installment_number' => 'required|integer|min:1',
            'installment_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:' . $remainingAmount,
            ],
            'release_date' => 'required|date',
            'district_quotas' => 'required|array|min:1',
            'district_quotas.*.district_id' => 'required|exists:districts,id',
            'district_quotas.*.percentage' => 'required|numeric|min:0|max:100',
            'district_quotas.*.total_beneficiaries' => 'required|numeric|min:0',
            'district_quotas.*.scheme_distributions' => 'required|array|min:1',
            'district_quotas.*.scheme_distributions.*.scheme_id' => 'required|exists:schemes,id',
            'district_quotas.*.scheme_distributions.*.percentage' => 'required|numeric|min:0|max:100',
            'district_quotas.*.scheme_distributions.*.beneficiaries_count' => 'nullable|numeric|min:0',
        ], [
            'installment_amount.max' => 'The installment amount cannot exceed the remaining amount (Rs. ' . number_format($remainingAmount, 2) . '). Total allocated: Rs. ' . number_format($installment->fundAllocation->total_amount, 2) . ', Already allocated: Rs. ' . number_format($totalExistingInstallments, 2) . '.',
            'district_quotas.min' => 'At least one district quota must be added.',
            'district_quotas.*.scheme_distributions.min' => 'Each district must have at least one scheme.',
        ]);

        // Server-side validation: Check if installment amount exceeds remaining amount
        if ($validated['installment_amount'] > $remainingAmount) {
            return back()->withErrors([
                'installment_amount' => 'The installment amount cannot exceed the remaining amount (Rs. ' . number_format($remainingAmount, 2) . '). Total allocated: Rs. ' . number_format($installment->fundAllocation->total_amount, 2) . ', Already allocated: Rs. ' . number_format($totalExistingInstallments, 2) . '.'
            ])->withInput();
        }

        // Server-side validation: Check if at least one district quota is added
        if (empty($validated['district_quotas']) || count($validated['district_quotas']) === 0) {
            return back()->withErrors([
                'district_quotas' => 'At least one district quota must be added.'
            ])->withInput();
        }

        // Server-side validation: Check if district percentages equal exactly 100% (with tolerance)
        $totalDistrictsPercentage = 0;
        foreach ($validated['district_quotas'] as $quotaData) {
            $totalDistrictsPercentage += $quotaData['percentage'];
        }
        
        $percentageTolerance = max(0.01, $totalDistrictsPercentage * 0.0001); // 0.01% tolerance or minimum 0.01
        if (abs($totalDistrictsPercentage - 100) > $percentageTolerance) {
            return back()->withErrors([
                'district_quotas' => 'District percentages must equal exactly 100% (currently ' . number_format($totalDistrictsPercentage, 2) . '%).'
            ])->withInput();
        }

        // Server-side validation: Check if district amounts equal installment amount (with tolerance)
        $totalDistrictsAmount = ($validated['installment_amount'] * $totalDistrictsPercentage) / 100;
        $amountTolerance = max(0.01, $validated['installment_amount'] * 0.0001); // 0.01% tolerance or minimum 0.01
        if (abs($totalDistrictsAmount - $validated['installment_amount']) > $amountTolerance) {
            return back()->withErrors([
                'district_quotas' => 'District total amount (Rs. ' . number_format($totalDistrictsAmount, 2) . ') must equal installment amount (Rs. ' . number_format($validated['installment_amount'], 2) . ').'
            ])->withInput();
        }

        // Server-side validation: Check each district has at least one scheme and percentages equal 100%
        foreach ($validated['district_quotas'] as $index => $quotaData) {
            $district = District::find($quotaData['district_id']);
            $districtName = $district ? $district->name : "District Quota #" . ($index + 1);
            
            // Check if at least one scheme is added
            if (empty($quotaData['scheme_distributions']) || count($quotaData['scheme_distributions']) === 0) {
                return back()->withErrors([
                    'district_quotas.' . $index . '.scheme_distributions' => $districtName . ' must have at least one scheme added.'
                ])->withInput();
            }

            // Check if scheme percentages equal exactly 100% (with tolerance)
            $totalSchemePercentage = 0;
            foreach ($quotaData['scheme_distributions'] as $distribution) {
                $totalSchemePercentage += $distribution['percentage'];
            }
            
            $schemePercentageTolerance = max(0.01, $totalSchemePercentage * 0.0001); // 0.01% tolerance or minimum 0.01
            if (abs($totalSchemePercentage - 100) > $schemePercentageTolerance) {
                return back()->withErrors([
                    'district_quotas.' . $index . '.scheme_distributions' => $districtName . ' scheme percentages must equal exactly 100% (currently ' . number_format($totalSchemePercentage, 2) . '%).'
                ])->withInput();
            }

            // Check if scheme totals don't exceed district share (with tolerance)
            $districtTotalBeneficiaries = $quotaData['total_beneficiaries'];
            $districtTotalAmount = ($validated['installment_amount'] * $quotaData['percentage']) / 100;
            
            $totalSchemeBeneficiaries = 0;
            $totalSchemeAmount = 0;
            
            foreach ($quotaData['scheme_distributions'] as $distribution) {
                $schemeBeneficiaries = ($distribution['percentage'] / 100) * $districtTotalBeneficiaries;
                $schemeAmount = ($districtTotalAmount * $distribution['percentage']) / 100;
                
                $totalSchemeBeneficiaries += $schemeBeneficiaries;
                $totalSchemeAmount += $schemeAmount;
            }
            
            $beneficiariesTolerance = max(0.1, $districtTotalBeneficiaries * 0.0001); // 0.01% tolerance or minimum 0.1
            $schemeAmountTolerance = max(0.01, $districtTotalAmount * 0.0001); // 0.01% tolerance or minimum 0.01

            if (abs($totalSchemeBeneficiaries - $districtTotalBeneficiaries) > $beneficiariesTolerance || abs($totalSchemeAmount - $districtTotalAmount) > $schemeAmountTolerance) {
                return back()->withErrors([
                    'district_quotas.' . $index . '.scheme_distributions' => $districtName . ' scheme totals must equal district share. Beneficiaries: ' . number_format($totalSchemeBeneficiaries, 1) . ' vs ' . number_format($districtTotalBeneficiaries, 1) . ', Amount: Rs. ' . number_format($totalSchemeAmount, 2) . ' vs Rs. ' . number_format($districtTotalAmount, 2) . '.'
                ])->withInput();
            }
        }

        // Update installment basic information
        $installment->update([
            'installment_number' => $validated['installment_number'],
            'installment_amount' => $validated['installment_amount'],
            'release_date' => $validated['release_date'],
        ]);

        // Delete existing district quotas and scheme distributions
        foreach ($installment->districtQuotas as $quota) {
            $quota->schemeDistributions()->delete();
            $quota->delete();
        }

        // Create new district quotas and scheme distributions
        foreach ($request->district_quotas as $quotaData) {
            $quota = DistrictQuota::create([
                'installment_id' => $installment->id,
                'district_id' => $quotaData['district_id'],
                'percentage' => $quotaData['percentage'],
                'total_beneficiaries' => $quotaData['total_beneficiaries'],
                'total_amount' => ($installment->installment_amount * $quotaData['percentage']) / 100,
            ]);

            foreach ($quotaData['scheme_distributions'] as $distribution) {
                SchemeDistribution::create([
                    'district_quota_id' => $quota->id,
                    'scheme_id' => $distribution['scheme_id'],
                    'percentage' => $distribution['percentage'],
                    'amount' => ($quota->total_amount * $distribution['percentage']) / 100,
                    'beneficiaries_count' => $distribution['beneficiaries_count'] ?? 0,
                ]);
            }
        }

        return redirect()->route('installments.show', $installment)
            ->with('success', 'Installment updated successfully.');
    }

    public function destroy(Installment $installment)
    {
        $fundAllocation = $installment->fundAllocation;
        $installment->delete();
        
        return redirect()->route('fund-allocations.show', $fundAllocation)
            ->with('success', 'Installment deleted successfully.');
    }
}
