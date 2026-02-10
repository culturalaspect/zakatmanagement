@extends('layouts.app')

@section('title', config('app.name') . ' - Phase-wise Report')
@section('page_title', 'Phase-wise Report')
@section('breadcrumb', 'Reports / Phase-wise')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.phase-wise') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">District</label>
                        <select name="district_id" class="form-select">
                            <option value="">All</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fund Allocation</label>
                        <select name="fund_allocation_id" class="form-select">
                            <option value="">All</option>
                            @foreach($allocations as $fa)
                                <option value="{{ $fa->id }}" {{ request('fund_allocation_id') == $fa->id ? 'selected' : '' }}>{{ $fa->financialYear->name ?? '' }}</option>
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
                                <th>Phase</th>
                                <th>District</th>
                                <th>Scheme</th>
                                <th>Financial Year</th>
                                <th>Installment</th>
                                <th>Status</th>
                                <th class="text-end">Beneficiaries</th>
                                <th class="text-end">Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($phases as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->district->name ?? '' }}</td>
                                    <td>{{ $p->scheme->name ?? '' }}</td>
                                    <td>{{ $p->installment->fundAllocation->financialYear->name ?? '' }}</td>
                                    <td>#{{ $p->installment->installment_number ?? '' }}</td>
                                    <td><span class="badge bg-{{ $p->status === 'open' ? 'success' : 'secondary' }}">{{ $p->status ?? '' }}</span></td>
                                    <td class="text-end">{{ number_format($p->beneficiaries_count ?? 0) }}</td>
                                    <td class="text-end">{{ number_format($p->beneficiaries_amount ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
