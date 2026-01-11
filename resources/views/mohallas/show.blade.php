@extends('layouts.app')

@section('title', config('app.name') . ' - View Mohalla')
@section('page_title', 'View Mohalla')
@section('breadcrumb', 'View Mohalla')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Mohalla Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('mohallas.edit', $mohalla) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p>{{ $mohalla->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Village:</strong>
                        <p>{{ $mohalla->village->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Union Council:</strong>
                        <p>{{ $mohalla->village->unionCouncil->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Tehsil:</strong>
                        <p>{{ $mohalla->village->unionCouncil->tehsil->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p>{{ $mohalla->village->unionCouncil->tehsil->district->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            @if($mohalla->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if($mohalla->localZakatCommittees->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Local Zakat Committees</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mohalla->localZakatCommittees as $committee)
                                    <tr>
                                        <td>{{ $committee->name }}</td>
                                        <td>{{ $committee->code ?? 'N/A' }}</td>
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
                        <a href="{{ route('mohallas.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

