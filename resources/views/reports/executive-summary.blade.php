@extends('layouts.app')

@section('title', config('app.name') . ' - Executive Summary Report')
@section('page_title', 'Executive Summary Report')
@section('breadcrumb', 'Reports / Executive Summary')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.executive-summary') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Fund Allocation</label>
                        <select name="fund_allocation_id" class="form-select">
                            <option value="">All</option>
                            @foreach($allocations as $fa)
                                <option value="{{ $fa->id }}" {{ request('fund_allocation_id') == $fa->id ? 'selected' : '' }}>
                                    {{ $fa->financialYear->name ?? '' }} - {{ $fa->date?->format('d M Y') }} (Rs. {{ number_format($fa->total_amount, 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Apply</button>
                        @include('reports.partials.export-buttons')
                    </div>
                </form>

                <h5 class="mb-3">Summary</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr><th width="40%">Total Beneficiaries</th><td>{{ number_format($data['total_beneficiaries']) }}</td></tr>
                            <tr><th>Pending</th><td>{{ number_format($data['pending']) }}</td></tr>
                            <tr><th>Submitted</th><td>{{ number_format($data['submitted']) }}</td></tr>
                            <tr><th>Approved</th><td>{{ number_format($data['approved']) }}</td></tr>
                            <tr><th>Rejected</th><td>{{ number_format($data['rejected']) }}</td></tr>
                            <tr><th>Paid</th><td>{{ number_format($data['paid']) }}</td></tr>
                            <tr><th>Payment Failed</th><td>{{ number_format($data['payment_failed']) }}</td></tr>
                            <tr><th>Total Amount Disbursed (Rs.)</th><td>{{ number_format($data['total_amount_disbursed'], 2) }}</td></tr>
                            <tr><th>Total Amount Paid (Rs.)</th><td>{{ number_format($data['total_amount_paid'], 2) }}</td></tr>
                            <tr><th>Total Funds Allocated (Rs.)</th><td>{{ number_format($data['total_funds_allocated'], 2) }}</td></tr>
                            <tr><th>Districts</th><td>{{ $data['districts_count'] }}</td></tr>
                            <tr><th>Schemes</th><td>{{ $data['schemes_count'] }}</td></tr>
                            <tr><th>Open Phases</th><td>{{ $data['phases_open'] }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
