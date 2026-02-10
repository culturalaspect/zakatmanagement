@extends('layouts.app')

@section('title', config('app.name') . ' - Beneficiary List Report')
@section('page_title', 'Beneficiary List Report')
@section('breadcrumb', 'Reports / Beneficiary List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.beneficiary-list') }}" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Submitted / Approved / Paid</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Apply</button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        @include('reports.partials.export-buttons')
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>CNIC</th>
                                <th>Name</th>
                                <th>Father/Husband</th>
                                <th>Mobile</th>
                                <th>District</th>
                                <th>Scheme</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                                <th>LZC</th>
                                <th>Submitted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($beneficiaries as $b)
                                <tr>
                                    <td>{{ $b->cnic ?? $b->representative?->cnic ?? '-' }}</td>
                                    <td>{{ $b->full_name }}</td>
                                    <td>{{ $b->father_husband_name ?? '-' }}</td>
                                    <td>{{ $b->mobile_number ?? '-' }}</td>
                                    <td>{{ $b->phase->district->name ?? '' }}</td>
                                    <td>{{ $b->scheme->name ?? '' }}</td>
                                    <td class="text-end">{{ number_format($b->amount, 2) }}</td>
                                    <td><span class="badge bg-{{ $b->status === 'paid' ? 'success' : ($b->status === 'approved' ? 'info' : ($b->status === 'rejected' ? 'danger' : 'secondary')) }}">{{ $b->status }}</span></td>
                                    <td>{{ $b->localZakatCommittee->name ?? ($b->institution->name ?? '-') }}</td>
                                    <td>{{ $b->submitted_at?->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="small text-muted">Showing {{ $beneficiaries->firstItem() ?? 0 }} to {{ $beneficiaries->lastItem() ?? 0 }} of {{ $beneficiaries->total() }} records.</div>
                    <div>{{ $beneficiaries->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
