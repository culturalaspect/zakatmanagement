@extends('layouts.app')

@section('title', config('app.name') . ' - Add Financial Year')
@section('page_title', 'Add Financial Year')
@section('breadcrumb', 'Add Financial Year')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Financial Year</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('financial-years.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., 2025-26" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Allocation</label>
                            <input type="number" name="total_allocation" class="form-control" value="{{ old('total_allocation') }}" step="0.01" min="0" placeholder="e.g., 1530055572">
                            <small class="text-muted">Total amount allocated for this financial year (e.g., Rs. 1,530,055,572)</small>
                            @error('total_allocation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_current" id="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_current">
                                    Set as Current Financial Year
                                </label>
                            </div>
                            <small class="text-muted">Only one financial year can be current at a time. Checking this will unset the current one.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('financial-years.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

