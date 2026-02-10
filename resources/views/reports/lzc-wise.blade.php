@extends('layouts.app')

@section('title', config('app.name') . ' - LZC-wise Report')
@section('page_title', 'LZC / Committee-wise Report')
@section('breadcrumb', 'Reports / LZC-wise')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.lzc-wise') }}" class="row g-3 mb-4">
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
                                <th>Local Zakat Committee</th>
                                <th class="text-end">Beneficiaries</th>
                                <th class="text-end">Total Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lzcStats as $name => $stat)
                                <tr>
                                    <td>{{ $name }}</td>
                                    <td class="text-end">{{ number_format($stat['count']) }}</td>
                                    <td class="text-end">{{ number_format($stat['amount'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                        @if($lzcStats->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-end">{{ number_format($lzcStats->sum(fn($s) => $s['count'])) }}</th>
                                <th class="text-end">{{ number_format($lzcStats->sum(fn($s) => $s['amount']), 2) }}</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
