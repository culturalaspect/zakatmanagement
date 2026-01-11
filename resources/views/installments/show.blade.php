@extends('layouts.app')

@section('title', config('app.name') . ' - Installment Details')
@section('page_title', 'Installment Details')
@section('breadcrumb', 'Installment Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Installment #{{ $installment->installment_number }} - Disbursement Plan</h3>
                    </div>
                    <div class="header_more_tool no-print">
                        <a href="{{ route('installments.edit', $installment) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                        <a href="{{ route('installments.print', $installment) }}" class="btn btn-info" target="_blank">
                            <i class="ti-printer"></i> Print
                        </a>
                        <a href="{{ route('installments.pdf', $installment) }}" class="btn btn-success" target="_blank">
                            <i class="ti-download"></i> Download PDF
                        </a>
                        <a href="{{ route('fund-allocations.show', $installment->fundAllocation) }}" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Financial Year:</label>
                        <p>{{ $installment->fundAllocation->financialYear->name }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Installment Number:</label>
                        <p>{{ $installment->installment_number }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Installment Amount:</label>
                        <p>Rs. {{ number_format($installment->installment_amount, 2) }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Release Date:</label>
                        <p>{{ $installment->release_date->format('Y-m-d') }}</p>
                    </div>
                </div>

                @if($installment->districtQuotas->count() > 0)
                <hr>
                <h5 class="mb-3">Disbursement Plan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="text-align: center;">
                        <thead style="background-color: #343a40 !important;">
                            <tr>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">District</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Percentage</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Total Beneficiaries</th>
                                <th colspan="{{ $installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id')->count() }}" class="text-center" style="vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Scheme Distributions</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Total Regular Zakat Fund (Rs.)</th>
                            </tr>
                            <tr>
                                @php
                                    $schemes = $installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id');
                                @endphp
                                @foreach($schemes as $scheme)
                                    <th style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">{{ $scheme->percentage }}% - {{ $scheme->name }} (Rs.)</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandTotalBeneficiaries = 0;
                                $grandTotalAmount = 0;
                                $schemeTotals = [];
                            @endphp
                            @foreach($installment->districtQuotas as $quota)
                                @php
                                    $grandTotalBeneficiaries += $quota->total_beneficiaries;
                                    $grandTotalAmount += $quota->total_amount;
                                    $districtTotal = 0;
                                @endphp
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;"><strong>{{ $quota->district->name }}</strong></td>
                                    <td style="text-align: center; vertical-align: middle;">{{ number_format($quota->percentage, 0) }}%</td>
                                    <td style="text-align: center; vertical-align: middle;">{{ number_format($quota->total_beneficiaries, 1) }}</td>
                                    @foreach($schemes as $scheme)
                                        @php
                                            $distribution = $quota->schemeDistributions->where('scheme_id', $scheme->id)->first();
                                            $amount = $distribution ? $distribution->amount : 0;
                                            $districtTotal += $amount;
                                            if (!isset($schemeTotals[$scheme->id])) {
                                                $schemeTotals[$scheme->id] = 0;
                                            }
                                            $schemeTotals[$scheme->id] += $amount;
                                        @endphp
                                        <td style="text-align: center; vertical-align: middle;">{{ number_format($amount, 2) }}</td>
                                    @endforeach
                                    <td style="text-align: center; vertical-align: middle;"><strong>{{ number_format($districtTotal, 2) }}</strong></td>
                                </tr>
                            @endforeach
                            <tr class="table-info fw-bold">
                                <td style="text-align: center; vertical-align: middle;"><strong>Grand Total:</strong></td>
                                <td style="text-align: center; vertical-align: middle;">100%</td>
                                <td style="text-align: center; vertical-align: middle;"><strong>{{ number_format($grandTotalBeneficiaries, 1) }}</strong></td>
                                @foreach($schemes as $scheme)
                                    <td style="text-align: center; vertical-align: middle;"><strong>{{ number_format($schemeTotals[$scheme->id] ?? 0, 2) }}</strong></td>
                                @endforeach
                                <td style="text-align: center; vertical-align: middle;"><strong>{{ number_format($grandTotalAmount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <hr>
                <p class="text-muted">No disbursement plan configured for this installment.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

