@extends('layouts.app')

@section('title', config('app.name') . ' - Fund Allocation Details')
@section('page_title', 'Fund Allocation Details')
@section('breadcrumb', 'Fund Allocation Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Fund Allocation Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('installments.create', $fundAllocation) }}" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Installment
                        </a>
                        <a href="{{ route('fund-allocations.edit', $fundAllocation) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                        <a href="{{ route('fund-allocations.index') }}" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Financial Year:</label>
                        <p>{{ $fundAllocation->financialYear->name }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Total Amount:</label>
                        <p>Rs. {{ number_format($fundAllocation->total_amount, 2) }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Date:</label>
                        <p>{{ $fundAllocation->date->format('Y-m-d') }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Source:</label>
                        <p>{{ $fundAllocation->source ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status:</label>
                        <p>
                            @if($fundAllocation->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($fundAllocation->status == 'allocated')
                                <span class="badge bg-info">Allocated</span>
                            @elseif($fundAllocation->status == 'disbursing')
                                <span class="badge bg-primary">Disbursing</span>
                            @elseif($fundAllocation->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($fundAllocation->installments->count() > 0)
                <hr>
                <h5 class="mb-3">Installments ({{ $fundAllocation->installments->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background-color: #343a40 !important;">
                            <tr>
                                <th style="color: #ffffff !important; background-color: #343a40 !important;">Installment #</th>
                                <th style="color: #ffffff !important; background-color: #343a40 !important;">Installment Amount</th>
                                <th style="color: #ffffff !important; background-color: #343a40 !important;">Release Date</th>
                                <th style="color: #ffffff !important; background-color: #343a40 !important;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fundAllocation->installments as $installment)
                            <tr>
                                <td>{{ $installment->installment_number }}</td>
                                <td>Rs. {{ number_format($installment->installment_amount, 2) }}</td>
                                <td>{{ $installment->release_date->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('installments.show', $installment) }}" class="action_btn mr_10" title="View Disbursement Plan">
                                        <i class="ti-eye"></i>
                                    </a>
                                    <a href="{{ route('installments.edit', $installment) }}" class="action_btn mr_10" title="Edit">
                                        <i class="ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('installments.destroy', $installment) }}" method="POST" class="d-inline mr_10" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this installment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action_btn" title="Delete">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <hr>
                <div class="alert alert-info">
                    <p class="mb-0">No installments added yet. Click "Add Installment" to create the first installment with disbursement plan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
