@extends('layouts.app')

@section('title', config('app.name') . ' - Institution Details')
@section('page_title', 'Institution Details')
@section('breadcrumb', 'Institution Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">{{ $institution->name }}</h3>
                        <p class="text-muted mb-0">Institution Details</p>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('institutions.edit', $institution) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                        <a href="{{ route('institutions.index') }}" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Name</th>
                                <td>{{ $institution->name }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td><span class="badge bg-primary">{{ $institution->type_label }}</span></td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td><strong>{{ $institution->code ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Registration Number</th>
                                <td>{{ $institution->registration_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($institution->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Contact Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Principal/Director</th>
                                <td>{{ $institution->principal_director_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $institution->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $institution->email ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Address Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="20%">District</th>
                                <td>{{ $institution->district->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Tehsil</th>
                                <td>{{ $institution->tehsil->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Union Council</th>
                                <td>{{ $institution->unionCouncil->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Village</th>
                                <td>{{ $institution->village->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mohalla</th>
                                <td>{{ $institution->mohalla->name ?? 'N/A' }}</td>
                            </tr>
                            @if($institution->address)
                            <tr>
                                <th>Additional Address</th>
                                <td>{{ $institution->address }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="{{ route('institutions.edit', $institution) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit Institution
                        </a>
                        <a href="{{ route('institutions.index') }}" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
