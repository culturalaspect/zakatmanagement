@extends('layouts.app')

@section('title', config('app.name') . ' - View Village')
@section('page_title', 'View Village')
@section('breadcrumb', 'View Village')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Village Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('villages.edit', $village) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p>{{ $village->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Union Council:</strong>
                        <p>{{ $village->unionCouncil->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Tehsil:</strong>
                        <p>{{ $village->unionCouncil->tehsil->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p>{{ $village->unionCouncil->tehsil->district->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            @if($village->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mohallas:</strong>
                        <p>{{ $village->mohallas->count() ?? 0 }}</p>
                    </div>
                </div>
                @if($village->mohallas->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Mohallas</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($village->mohallas as $mohalla)
                                    <tr>
                                        <td>{{ $mohalla->name }}</td>
                                        <td>
                                            @if($mohalla->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('villages.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

