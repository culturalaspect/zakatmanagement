@extends('layouts.app')

@section('title', config('app.name') . ' - Payment Report')
@section('page_title', 'Payment Report')
@section('breadcrumb', 'Reports / Payment Report')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.payment-report') }}" class="row g-3 mb-4">
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

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6>Paid</h6>
                                <h4>{{ number_format($summary['paid_count']) }}</h4>
                                <p class="mb-0">Rs. {{ number_format($summary['paid_amount'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h6>Pending Payment</h6>
                                <h4>{{ number_format($summary['pending_count']) }}</h4>
                                <p class="mb-0">Rs. {{ number_format($summary['pending_amount'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h6>Payment Failed</h6>
                                <h4>{{ number_format($summary['failed_count']) }}</h4>
                                <p class="mb-0">Rs. {{ number_format($summary['failed_amount'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#paid">Paid ({{ $paid->count() }})</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pending">Pending ({{ $approved->count() }})</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#failed">Failed ({{ $paymentFailed->count() }})</a></li>
                </ul>
                <div class="tab-content mt-3">
                    <div id="paid" class="tab-pane active">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-dark"><tr><th>CNIC</th><th>Name</th><th>District</th><th>Scheme</th><th class="text-end">Amount</th></tr></thead>
                                <tbody>
                                    @foreach($paid->take(100) as $b)
                                        <tr>
                                            <td>{{ $b->cnic ?? $b->representative?->cnic }}</td>
                                            <td>{{ $b->full_name }}</td>
                                            <td>{{ $b->phase->district->name ?? '' }}</td>
                                            <td>{{ $b->scheme->name ?? '' }}</td>
                                            <td class="text-end">{{ number_format($b->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($paid->count() > 100)<p class="small text-muted">Showing first 100. Export to Excel for full list.</p>@endif
                        </div>
                    </div>
                    <div id="pending" class="tab-pane">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-dark"><tr><th>CNIC</th><th>Name</th><th>District</th><th>Scheme</th><th class="text-end">Amount</th></tr></thead>
                                <tbody>
                                    @foreach($approved->take(100) as $b)
                                        <tr>
                                            <td>{{ $b->cnic ?? $b->representative?->cnic }}</td>
                                            <td>{{ $b->full_name }}</td>
                                            <td>{{ $b->phase->district->name ?? '' }}</td>
                                            <td>{{ $b->scheme->name ?? '' }}</td>
                                            <td class="text-end">{{ number_format($b->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($approved->count() > 100)<p class="small text-muted">Showing first 100. Export to Excel for full list.</p>@endif
                        </div>
                    </div>
                    <div id="failed" class="tab-pane">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-dark"><tr><th>CNIC</th><th>Name</th><th>District</th><th>Scheme</th><th>JazzCash Status</th><th>Reason</th><th class="text-end">Amount</th></tr></thead>
                                <tbody>
                                    @foreach($paymentFailed->take(100) as $b)
                                        <tr>
                                            <td>{{ $b->cnic ?? $b->representative?->cnic }}</td>
                                            <td>{{ $b->full_name }}</td>
                                            <td>{{ $b->phase->district->name ?? '' }}</td>
                                            <td>{{ $b->scheme->name ?? '' }}</td>
                                            <td>{{ $b->jazzcash_status ?? '' }}</td>
                                            <td>{{ $b->jazzcash_reason ?? '' }}</td>
                                            <td class="text-end">{{ number_format($b->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($paymentFailed->count() > 100)<p class="small text-muted">Showing first 100. Export to Excel for full list.</p>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
