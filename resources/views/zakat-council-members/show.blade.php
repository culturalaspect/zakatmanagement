@extends('layouts.app')

@section('title', config('app.name') . ' - View Zakat Council Member')
@section('page_title', 'View Zakat Council Member')
@section('breadcrumb', 'View Zakat Council Member')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Zakat Council Member Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('zakat-council-members.edit', $zakatCouncilMember) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p>{{ $zakatCouncilMember->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Designation:</strong>
                        <p>{{ $zakatCouncilMember->designation }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Role in Committee:</strong>
                        <p>{{ $zakatCouncilMember->role_in_committee }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Start Date:</strong>
                        <p>{{ $zakatCouncilMember->start_date->format('Y-m-d') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>End Date:</strong>
                        <p>{{ $zakatCouncilMember->end_date ? $zakatCouncilMember->end_date->format('Y-m-d') : 'Current' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            @if($zakatCouncilMember->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('zakat-council-members.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

