@extends('layouts.app')

@section('title', config('app.name') . ' - Add Fund Allocation')
@section('page_title', 'Add Fund Allocation')
@section('breadcrumb', 'Add Fund Allocation')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Fund Allocation</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('fund-allocations.store') }}" method="POST" id="fundAllocationForm">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Financial Year <span class="text-danger">*</span></label>
                            <select name="financial_year_id" class="form-control" required>
                                <option value="">Select Financial Year</option>
                                @foreach($financialYears as $fy)
                                    <option value="{{ $fy->id }}" {{ old('financial_year_id') == $fy->id ? 'selected' : '' }}>
                                        {{ $fy->name }} 
                                        @if($fy->is_current)
                                            <span class="badge bg-success">Current</span>
                                        @endif
                                        ({{ $fy->start_date->format('Y-m-d') }} to {{ $fy->end_date->format('Y-m-d') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('financial_year_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                            <input type="number" name="total_amount" class="form-control" value="{{ old('total_amount') }}" step="0.01" min="0" required>
                            @error('total_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Source</label>
                            <input type="text" name="source" class="form-control" value="{{ old('source') }}" placeholder="e.g., Ministry of Poverty Alleviation and Social Safety Islamabad">
                            @error('source')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="allocated" {{ old('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                <option value="disbursing" {{ old('status') == 'disbursing' ? 'selected' : '' }}>Disbursing</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('fund-allocations.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
