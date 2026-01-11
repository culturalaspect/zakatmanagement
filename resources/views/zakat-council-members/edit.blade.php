@extends('layouts.app')

@section('title', config('app.name') . ' - Edit Zakat Council Member')
@section('page_title', 'Edit Zakat Council Member')
@section('breadcrumb', 'Edit Zakat Council Member')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Zakat Council Member</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('zakat-council-members.update', $zakatCouncilMember) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $zakatCouncilMember->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control" value="{{ old('designation', $zakatCouncilMember->designation) }}" required>
                            @error('designation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role in Committee <span class="text-danger">*</span></label>
                            <input type="text" name="role_in_committee" class="form-control" value="{{ old('role_in_committee', $zakatCouncilMember->role_in_committee) }}" required>
                            @error('role_in_committee')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $zakatCouncilMember->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $zakatCouncilMember->end_date ? $zakatCouncilMember->end_date->format('Y-m-d') : '') }}">
                            @error('end_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $zakatCouncilMember->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('zakat-council-members.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

