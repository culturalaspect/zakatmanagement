@extends('layouts.app')

@section('title', config('app.name') . ' - View District')
@section('page_title', 'View District')
@section('breadcrumb', 'View District')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">District Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('districts.edit', $district) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p>{{ $district->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Population:</strong>
                        <p>{{ number_format($district->population) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            @if($district->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('districts.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

