@extends('layouts.app')

@section('title', config('app.name') . ' - Add Union Council')
@section('page_title', 'Add Union Council')
@section('breadcrumb', 'Add Union Council')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Union Council</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('union-councils.store') }}" method="POST">
                    @csrf
                    
                    <!-- Tehsil Selection Section (Separated) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Select Tehsil</h5>
                            <div class="card" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                <div class="card-body">
                                    <!-- Filters for Tehsil Selection -->
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Filter by District</label>
                                            <select id="filterDistrict" class="form-control form-control-sm">
                                                <option value="">All Districts</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Tehsil Select2 Field -->
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Tehsil <span class="text-danger">*</span></label>
                                            <select name="tehsil_id" id="tehsil_id" class="form-control" required>
                                                <option value="">Select Tehsil</option>
                                                @foreach($tehsils as $tehsil)
                                                    <option value="{{ $tehsil->id }}" 
                                                            data-district-id="{{ $tehsil->district_id ?? '' }}"
                                                            {{ old('tehsil_id') == $tehsil->id ? 'selected' : '' }}>
                                                        {{ $tehsil->name }} ({{ $tehsil->district->name ?? 'N/A' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tehsil_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Other Form Fields -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('union-councils.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-results__option {
        padding: 8px 12px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for tehsil field
        $('#tehsil_id').select2({
            placeholder: 'Search and select a tehsil...',
            allowClear: true,
            width: '100%'
        });

        // Store all tehsil options
        const allTehsilOptions = $('#tehsil_id option').clone();
        
        // Filter tehsils based on selected district
        function filterTehsils() {
            const districtId = $('#filterDistrict').val();
            const currentValue = $('#tehsil_id').val();
            
            // Clear all options except placeholder
            $('#tehsil_id').empty().append('<option value="">Select Tehsil</option>');
            
            // Add filtered options
            allTehsilOptions.each(function() {
                if ($(this).val() === '') {
                    return; // Skip placeholder
                }
                
                const optionDistrictId = $(this).data('district-id');
                
                let show = true;
                
                if (districtId && optionDistrictId != districtId) {
                    show = false;
                }
                
                if (show) {
                    const $option = $(this).clone();
                    $('#tehsil_id').append($option);
                }
            });
            
            // Restore selection if it's still available
            if (currentValue && $('#tehsil_id option[value="' + currentValue + '"]').length > 0) {
                $('#tehsil_id').val(currentValue);
            } else {
                $('#tehsil_id').val('');
            }
            
            // Trigger Select2 to refresh
            $('#tehsil_id').trigger('change.select2');
        }

        // Filter tehsils based on District
        $('#filterDistrict').on('change', function() {
            filterTehsils();
        });
    });
</script>
@endpush

