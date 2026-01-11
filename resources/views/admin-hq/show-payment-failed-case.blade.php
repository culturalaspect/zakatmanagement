@extends('layouts.app')

@section('title', config('app.name') . ' - Payment Failed Case Details')
@section('page_title', 'Payment Failed Case Details')
@section('breadcrumb', 'Payment Failed Case Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Payment Failed Beneficiary Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('admin-hq.all-cases') }}#payment-failed" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back to Payment Failed Cases
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="alert alert-danger" role="alert">
                            <i class="ti-close"></i> <strong>Status:</strong> Payment transaction with JazzCash has failed.
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
                        <p class="mb-0">{{ $beneficiary->date_of_birth ? $beneficiary->date_of_birth->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">{{ ucfirst($beneficiary->gender ?? 'N/A') }}</p>
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
                        <strong>Amount:</strong>
                        <p class="mb-0">Rs. {{ number_format($beneficiary->amount, 2) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phase:</strong>
                        <p class="mb-0">{{ $beneficiary->phase->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Payment Failure Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>JazzCash Status:</strong>
                        <p class="mb-0"><span class="badge bg-danger">{{ $beneficiary->jazzcash_status ?? 'N/A' }}</span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Failure Reason:</strong>
                        <p class="mb-0 text-danger">{{ $beneficiary->jazzcash_reason ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Transaction ID (TID):</strong>
                        <p class="mb-0">{{ $beneficiary->jazzcash_tid ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>JazzCash Amount:</strong>
                        <p class="mb-0">Rs. {{ number_format($beneficiary->jazzcash_amount ?? 0, 2) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>JazzCash Charges:</strong>
                        <p class="mb-0">Rs. {{ number_format($beneficiary->jazzcash_charges ?? 0, 2) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Total Amount:</strong>
                        <p class="mb-0">Rs. {{ number_format($beneficiary->jazzcash_total ?? 0, 2) }}</p>
                    </div>
                    @if($beneficiary->requires_representative && $beneficiary->representative)
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Representative Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative CNIC:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->cnic ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative Name:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative Mobile:</strong>
                        <p class="mb-0">{{ $beneficiary->representative->mobile_number ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

