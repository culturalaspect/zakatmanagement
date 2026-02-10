@extends('layouts.app')

@section('title', config('app.name') . ' - Beneficiary Status Report')
@section('page_title', 'Beneficiary Status Report')
@section('breadcrumb', 'Reports / Beneficiary Status')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.beneficiary-status') }}" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">District</label>
                        <select name="district_id" class="form-select">
                            <option value="">All</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Scheme</label>
                        <select name="scheme_id" class="form-select">
                            <option value="">All</option>
                            @foreach($schemes as $s)
                                <option value="{{ $s->id }}" {{ request('scheme_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
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
                                <th>Status</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Total Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats as $status => $s)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td>
                                    <td class="text-end">{{ number_format($s['count']) }}</td>
                                    <td class="text-end">{{ number_format($s['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
