@extends('layouts.app')

@section('title', config('app.name') . ' - Rejected Case Details')
@section('page_title', 'Rejected Case Details')
@section('breadcrumb', 'Rejected Case Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Rejected Beneficiary Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('admin-hq.all-cases') }}?tab=rejected" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back to Rejected Cases
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="alert alert-danger" role="alert">
                            <i class="ti-close"></i> <strong>Status:</strong> This beneficiary has been rejected.
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Beneficiary Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>CNIC:</strong>
                        <p class="mb-0">{{ $beneficiary->cnic ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0">{{ $beneficiary->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father/Husband Name:</strong>
                        <p class="mb-0">{{ $beneficiary->father_husband_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mobile Number:</strong>
                        <p class="mb-0">{{ $beneficiary->mobile_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p class="mb-0">{{ $beneficiary->date_of_birth ? \Carbon\Carbon::parse($beneficiary->date_of_birth)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">
                            @if($beneficiary->gender === 'male')
                                <span class="badge bg-primary">Male</span>
                            @elseif($beneficiary->gender === 'female')
                                <span class="badge bg-info">Female</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($beneficiary->gender ?? 'N/A') }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Amount:</strong>
                        <p class="mb-0"><strong class="text-success">Rs. {{ number_format($beneficiary->amount ?? 0, 2) }}</strong></p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Phase & Scheme Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phase:</strong>
                        <p class="mb-0">{{ $beneficiary->phase->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p class="mb-0">{{ $beneficiary->phase->district->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Scheme:</strong>
                        <p class="mb-0">{{ $beneficiary->scheme->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Scheme Category:</strong>
                        <p class="mb-0">{{ $beneficiary->schemeCategory->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Local Zakat Committee:</strong>
                        <p class="mb-0">{{ $beneficiary->localZakatCommittee->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative Required:</strong>
                        <p class="mb-0">
                            @if($beneficiary->requires_representative)
                                <span class="badge bg-warning">Yes (Age < 18)</span>
                            @else
                                <span class="badge bg-success">No (Age ≥ 18)</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($beneficiary->representative)
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Representative Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>CNIC:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->cnic ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father/Husband Name:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->father_husband_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mobile Number:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->mobile_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->date_of_birth ? \Carbon\Carbon::parse($beneficiary->representative->date_of_birth)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">
                            @if($beneficiary->representative->gender === 'male')
                                <span class="badge bg-primary">Male</span>
                            @elseif($beneficiary->representative->gender === 'female')
                                <span class="badge bg-info">Female</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($beneficiary->representative->gender ?? 'N/A') }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Relationship:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->relationship ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif

                @if($beneficiary->admin_remarks || $beneficiary->district_remarks || $beneficiary->rejection_remarks)
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Remarks</h5>
                    </div>
                    @if($beneficiary->district_remarks)
                    <div class="col-md-12 mb-3">
                        <strong>District Remarks:</strong>
                        <p class="mb-0">{{ $beneficiary->district_remarks }}</p>
                    </div>
                    @endif
                    @if($beneficiary->admin_remarks)
                    <div class="col-md-12 mb-3">
                        <strong>Rejection Remarks:</strong>
                        <p class="mb-0 text-danger"><strong>{{ $beneficiary->admin_remarks }}</strong></p>
                    </div>
                    @endif
                    @if($beneficiary->rejection_remarks)
                    <div class="col-md-12 mb-3">
                        <strong>Additional Rejection Remarks:</strong>
                        <p class="mb-0 text-danger">{{ $beneficiary->rejection_remarks }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Timeline</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Created At:</strong>
                        <p class="mb-0">{{ $beneficiary->created_at->format('d M Y H:i:s') }}</p>
                    </div>
                    @if($beneficiary->submitted_at)
                    <div class="col-md-6 mb-3">
                        <strong>Submitted At:</strong>
                        <p class="mb-0">{{ $beneficiary->submitted_at->format('d M Y H:i:s') }}</p>
                    </div>
                    @endif
                    @if($beneficiary->submittedBy)
                    <div class="col-md-6 mb-3">
                        <strong>Submitted By:</strong>
                        <p class="mb-0">{{ $beneficiary->submittedBy->name ?? 'N/A' }}</p>
                    </div>
                    @endif
                    @if($beneficiary->rejected_at)
                    <div class="col-md-6 mb-3">
                        <strong>Rejected At:</strong>
                        <p class="mb-0">{{ $beneficiary->rejected_at->format('d M Y H:i:s') }}</p>
                    </div>
                    @endif
                    <div class="col-md-6 mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0">{{ $beneficiary->updated_at->format('d M Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


