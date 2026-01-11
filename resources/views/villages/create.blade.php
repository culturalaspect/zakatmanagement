@extends('layouts.app')

@section('title', config('app.name') . ' - Add Village')
@section('page_title', 'Add Village')
@section('breadcrumb', 'Add Village')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Village</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('villages.store') }}" method="POST">
                    @csrf
                    
                    <!-- Union Council Selection Section (Separated) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Select Union Council</h5>
                            <div class="card" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                <div class="card-body">
                                    <!-- Filters for Union Council Selection -->
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
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Filter by Tehsil</label>
                                            <select id="filterTehsil" class="form-control form-control-sm">
                                                <option value="">All Tehsils</option>
                                                @foreach($tehsils as $tehsil)
                                                    <option value="{{ $tehsil->id }}" data-district-id="{{ $tehsil->district_id }}">{{ $tehsil->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Union Council Select2 Field -->
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Union Council <span class="text-danger">*</span></label>
                                            <select name="union_council_id" id="union_council_id" class="form-control" required>
                                                <option value="">Select Union Council</option>
                                                @foreach($unionCouncils as $uc)
                                                    <option value="{{ $uc->id }}" 
                                                            data-district-id="{{ $uc->tehsil->district_id ?? '' }}"
                                                            data-tehsil-id="{{ $uc->tehsil_id ?? '' }}"
                                                            {{ old('union_council_id') == $uc->id ? 'selected' : '' }}>
                                                        {{ $uc->name }} ({{ $uc->tehsil->name ?? 'N/A' }}, {{ $uc->tehsil->district->name ?? 'N/A' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('union_council_id')
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
                            <a href="{{ route('villages.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Initialize Select2 for union council field
        $('#union_council_id').select2({
            placeholder: 'Search and select a union council...',
            allowClear: true,
            width: '100%'
        });

        // Store all union council options
        const allUnionCouncilOptions = $('#union_council_id option').clone();
        
        // Filter union councils based on selected filters
        function filterUnionCouncils() {
            const districtId = $('#filterDistrict').val();
            const tehsilId = $('#filterTehsil').val();
            const currentValue = $('#union_council_id').val();
            
            // Clear all options except placeholder
            $('#union_council_id').empty().append('<option value="">Select Union Council</option>');
            
            // Add filtered options
            allUnionCouncilOptions.each(function() {
                if ($(this).val() === '') {
                    return; // Skip placeholder
                }
                
                const optionDistrictId = $(this).data('district-id');
                const optionTehsilId = $(this).data('tehsil-id');
                
                let show = true;
                
                if (districtId && optionDistrictId != districtId) {
                    show = false;
                }
                if (tehsilId && optionTehsilId != tehsilId) {
                    show = false;
                }
                
                if (show) {
                    const $option = $(this).clone();
                    $('#union_council_id').append($option);
                }
            });
            
            // Restore selection if it's still available
            if (currentValue && $('#union_council_id option[value="' + currentValue + '"]').length > 0) {
                $('#union_council_id').val(currentValue);
            } else {
                $('#union_council_id').val('');
            }
            
            // Trigger Select2 to refresh
            $('#union_council_id').trigger('change.select2');
        }

        // Filter Tehsils based on District
        $('#filterDistrict').on('change', function() {
            const districtId = $(this).val();
            const $tehsilSelect = $('#filterTehsil');
            
            // Filter tehsils
            $tehsilSelect.find('option').each(function() {
                if ($(this).val() === '') {
                    return;
                }
                if (!districtId || $(this).data('district-id') == districtId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Reset tehsil if district changed
            if (districtId) {
                $tehsilSelect.val('').trigger('change');
            } else {
                $tehsilSelect.find('option').show();
            }
            
            filterUnionCouncils();
        });

        // Filter union councils based on Tehsil
        $('#filterTehsil').on('change', function() {
            filterUnionCouncils();
        });
    });
</script>
@endpush

