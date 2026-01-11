@extends('layouts.app')

@section('title', config('app.name') . ' - Financial Year Details')
@section('page_title', 'Financial Year Details')
@section('breadcrumb', 'Financial Year Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Financial Year Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('financial-years.edit', $financialYear) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                        <a href="{{ route('financial-years.index') }}" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Name:</label>
                        <p>{{ $financialYear->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <p>
                            @if($financialYear->is_current)
                                <span class="badge bg-success">Current</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Start Date:</label>
                        <p>{{ $financialYear->start_date->format('Y-m-d') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">End Date:</label>
                        <p>{{ $financialYear->end_date->format('Y-m-d') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Total Allocation:</label>
                        <p>{{ $financialYear->total_allocation ? 'Rs. ' . number_format($financialYear->total_allocation, 2) : 'Not Set' }}</p>
                    </div>
                </div>

                @if($financialYear->fundAllocations->count() > 0)
                <hr>
                <h5>Fund Allocations ({{ $financialYear->fundAllocations->count() }})</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Installment</th>
                                <th>Installment Amount</th>
                                <th>Release Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($financialYear->fundAllocations as $allocation)
                            <tr>
                                <td>{{ $allocation->installment_number }}</td>
                                <td>Rs. {{ number_format($allocation->installment_amount, 2) }}</td>
                                <td>{{ $allocation->release_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($allocation->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($allocation->status == 'allocated')
                                        <span class="badge bg-info">Allocated</span>
                                    @elseif($allocation->status == 'disbursing')
                                        <span class="badge bg-primary">Disbursing</span>
                                    @elseif($allocation->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('fund-allocations.show', $allocation) }}" class="action_btn" title="View">
                                        <i class="ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <hr>
                <p class="text-muted">No fund allocations for this financial year.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

