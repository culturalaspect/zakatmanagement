@extends('layouts.app')

@section('title', config('app.name') . ' - Fund Disbursement Report')
@section('page_title', 'Fund Disbursement Report')
@section('breadcrumb', 'Reports / Fund Disbursement')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.fund-disbursement') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Financial Year</label>
                        <select name="financial_year_id" class="form-select">
                            <option value="">All</option>
                            @foreach($financialYears as $fy)
                                <option value="{{ $fy->id }}" {{ request('financial_year_id') == $fy->id ? 'selected' : '' }}>{{ $fy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Apply</button>
                        @include('reports.partials.export-buttons')
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Financial Year</th>
                                <th>Date</th>
                                <th class="text-end">Total Allocated (Rs.)</th>
                                <th class="text-end">Installments Total (Rs.)</th>
                                <th class="text-end">Disbursed (Rs.)</th>
                                <th class="text-end">Paid (Rs.)</th>
                                <th class="text-end">Remaining (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $r)
                                <tr>
                                    <td>{{ $r['financial_year'] }}</td>
                                    <td>{{ $r['date'] }}</td>
                                    <td class="text-end">{{ number_format($r['total_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($r['installments_total'], 2) }}</td>
                                    <td class="text-end">{{ number_format($r['disbursed'], 2) }}</td>
                                    <td class="text-end">{{ number_format($r['paid'], 2) }}</td>
                                    <td class="text-end">{{ number_format($r['remaining'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
