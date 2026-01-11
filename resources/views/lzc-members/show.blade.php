@extends('layouts.app')

@section('title', config('app.name') . ' - View LZC Member')
@section('page_title', 'View LZC Member')
@section('breadcrumb', 'View LZC Member')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">LZC Member Details</h3>
                    </div>
                    <div class="header_more_tool">
                        @if($lZCMember->verification_status == 'pending')
                        <a href="{{ route('lzc-members.verify', $lZCMember) }}" class="btn btn-warning">
                            <i class="ti-check-box"></i> Verify
                        </a>
                        @endif
                        <a href="{{ route('lzc-members.edit', $lZCMember) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                        <a href="{{ route('lzc-members.index') }}" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>CNIC:</strong>
                        <p><strong class="text-primary">{{ $lZCMember->cnic ?? 'N/A' }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p>{{ $lZCMember->full_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father/Husband Name:</strong>
                        <p>{{ $lZCMember->father_husband_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mobile Number:</strong>
                        <p>{{ $lZCMember->mobile_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p>{{ $lZCMember->date_of_birth ? \Carbon\Carbon::parse($lZCMember->date_of_birth)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p>
                            @if($lZCMember->gender == 'male')
                                <span class="badge bg-primary">Male</span>
                            @elseif($lZCMember->gender == 'female')
                                <span class="badge bg-pink">Female</span>
                            @else
                                <span class="badge bg-secondary">Other</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Start Date:</strong>
                        <p>{{ $lZCMember->start_date ? \Carbon\Carbon::parse($lZCMember->start_date)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>End Date:</strong>
                        <p>
                            @if($lZCMember->end_date)
                                {{ \Carbon\Carbon::parse($lZCMember->end_date)->format('d M Y') }}
                            @else
                                <span class="text-muted">Ongoing</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Local Zakat Committee:</strong>
                        <p>
                            @if($lZCMember->localZakatCommittee)
                                {{ $lZCMember->localZakatCommittee->name }}<br>
                                <small class="text-muted">Code: {{ $lZCMember->localZakatCommittee->code ?? 'N/A' }}</small><br>
                                <small class="text-muted">District: {{ $lZCMember->localZakatCommittee->district->name ?? 'N/A' }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Verification Status:</strong>
                        <p>
                            @if($lZCMember->verification_status == 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($lZCMember->verification_status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Active Status:</strong>
                        <p>
                            @if($lZCMember->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    @if($lZCMember->verification_remarks)
                    <div class="col-md-12 mb-3">
                        <strong>Verification Remarks:</strong>
                        <p>{{ $lZCMember->verification_remarks }}</p>
                    </div>
                    @endif
                    @if($lZCMember->rejection_reason)
                    <div class="col-md-12 mb-3">
                        <strong>Rejection Reason:</strong>
                        <p class="text-danger">{{ $lZCMember->rejection_reason }}</p>
                    </div>
                    @endif
                    @if($lZCMember->verified_at)
                    <div class="col-md-6 mb-3">
                        <strong>Verified At:</strong>
                        <p>{{ \Carbon\Carbon::parse($lZCMember->verified_at)->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                    @if($lZCMember->rejected_at)
                    <div class="col-md-6 mb-3">
                        <strong>Rejected At:</strong>
                        <p>{{ \Carbon\Carbon::parse($lZCMember->rejected_at)->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

