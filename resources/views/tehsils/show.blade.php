@extends('layouts.app')

@section('title', config('app.name') . ' - View Tehsil')
@section('page_title', 'View Tehsil')
@section('breadcrumb', 'View Tehsil')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Tehsil Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('tehsils.edit', $tehsil) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p>{{ $tehsil->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p>{{ $tehsil->district->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            @if($tehsil->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Union Councils:</strong>
                        <p>{{ $tehsil->unionCouncils->count() ?? 0 }}</p>
                    </div>
                </div>
                @if($tehsil->unionCouncils->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Union Councils</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tehsil->unionCouncils as $uc)
                                    <tr>
                                        <td>{{ $uc->name }}</td>
                                        <td>
                                            @if($uc->is_active)
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
                        <a href="{{ route('tehsils.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

