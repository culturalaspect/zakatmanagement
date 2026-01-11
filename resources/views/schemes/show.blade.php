@extends('layouts.app')

@section('title', config('app.name') . ' - View Scheme')
@section('page_title', 'View Scheme')
@section('breadcrumb', 'View Scheme')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Scheme Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('schemes.edit', $scheme) }}" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p>{{ $scheme->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Percentage:</strong>
                        <p>{{ number_format($scheme->percentage, 2) }}%</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <strong>Description:</strong>
                        <p>{{ $scheme->description ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Age Restriction:</strong>
                        <p>
                            @if($scheme->has_age_restriction)
                                <span class="badge bg-info">Yes (Minimum Age: {{ $scheme->minimum_age }})</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            @if($scheme->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($scheme->categories->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Scheme Categories</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scheme->categories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>Rs. {{ number_format($category->amount, 2) }}</td>
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
                        <a href="{{ route('schemes.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

