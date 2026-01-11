@extends('layouts.app')

@section('title', config('app.name') . ' - Add Mohalla')
@section('page_title', 'Add Mohalla')
@section('breadcrumb', 'Add Mohalla')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Mohalla</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('mohallas.store') }}" method="POST">
                    @csrf
                    
                    <!-- Village Selection Section (Separated) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Select Village</h5>
                            <div class="card" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                <div class="card-body">
                                    <!-- Filters for Village Selection -->
                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Filter by District</label>
                                            <select id="filterDistrict" class="form-control form-control-sm">
                                                <option value="">All Districts</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Filter by Tehsil</label>
                                            <select id="filterTehsil" class="form-control form-control-sm">
                                                <option value="">All Tehsils</option>
                                                @foreach($tehsils as $tehsil)
                                                    <option value="{{ $tehsil->id }}" data-district-id="{{ $tehsil->district_id }}">{{ $tehsil->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Filter by Union Council</label>
                                            <select id="filterUnionCouncil" class="form-control form-control-sm">
                                                <option value="">All Union Councils</option>
                                                @foreach($unionCouncils as $unionCouncil)
                                                    <option value="{{ $unionCouncil->id }}" data-tehsil-id="{{ $unionCouncil->tehsil_id }}">{{ $unionCouncil->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Village Select2 Field -->
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Village <span class="text-danger">*</span></label>
                                            <select name="village_id" id="village_id" class="form-control" required>
                                                <option value="">Select Village</option>
                                                @foreach($villages as $village)
                                                    <option value="{{ $village->id }}" 
                                                            data-district-id="{{ $village->unionCouncil->tehsil->district_id ?? '' }}"
                                                            data-tehsil-id="{{ $village->unionCouncil->tehsil_id ?? '' }}"
                                                            data-union-council-id="{{ $village->union_council_id ?? '' }}"
                                                            {{ old('village_id') == $village->id ? 'selected' : '' }}>
                                                        {{ $village->name }} - {{ $village->unionCouncil->name ?? 'N/A' }} ({{ $village->unionCouncil->tehsil->name ?? 'N/A' }}, {{ $village->unionCouncil->tehsil->district->name ?? 'N/A' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('village_id')
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
                            <a href="{{ route('mohallas.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Initialize Select2 for village field
        $('#village_id').select2({
            placeholder: 'Search and select a village...',
            allowClear: true,
            width: '100%'
        });

        // Store all village options
        const allVillageOptions = $('#village_id option').clone();
        
        // Filter villages based on selected filters
        function filterVillages() {
            const districtId = $('#filterDistrict').val();
            const tehsilId = $('#filterTehsil').val();
            const unionCouncilId = $('#filterUnionCouncil').val();
            const currentValue = $('#village_id').val();
            
            // Clear all options except placeholder
            $('#village_id').empty().append('<option value="">Select Village</option>');
            
            // Add filtered options
            allVillageOptions.each(function() {
                if ($(this).val() === '') {
                    return; // Skip placeholder
                }
                
                const optionDistrictId = $(this).data('district-id');
                const optionTehsilId = $(this).data('tehsil-id');
                const optionUnionCouncilId = $(this).data('union-council-id');
                
                let show = true;
                
                if (districtId && optionDistrictId != districtId) {
                    show = false;
                }
                if (tehsilId && optionTehsilId != tehsilId) {
                    show = false;
                }
                if (unionCouncilId && optionUnionCouncilId != unionCouncilId) {
                    show = false;
                }
                
                if (show) {
                    const $option = $(this).clone();
                    $('#village_id').append($option);
                }
            });
            
            // Restore selection if it's still available
            if (currentValue && $('#village_id option[value="' + currentValue + '"]').length > 0) {
                $('#village_id').val(currentValue);
            } else {
                $('#village_id').val('');
            }
            
            // Trigger Select2 to refresh
            $('#village_id').trigger('change.select2');
        }

        // Filter Tehsils based on District
        $('#filterDistrict').on('change', function() {
            const districtId = $(this).val();
            const $tehsilSelect = $('#filterTehsil');
            const $unionCouncilSelect = $('#filterUnionCouncil');
            
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
            
            // Reset tehsil and union council if district changed
            if (districtId) {
                $tehsilSelect.val('').trigger('change');
            } else {
                $tehsilSelect.find('option').show();
            }
            $unionCouncilSelect.val('').trigger('change');
            
            filterVillages();
        });

        // Filter Union Councils based on Tehsil
        $('#filterTehsil').on('change', function() {
            const tehsilId = $(this).val();
            const $unionCouncilSelect = $('#filterUnionCouncil');
            
            // Filter union councils
            $unionCouncilSelect.find('option').each(function() {
                if ($(this).val() === '') {
                    return;
                }
                if (!tehsilId || $(this).data('tehsil-id') == tehsilId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Reset union council if tehsil changed
            if (tehsilId) {
                $unionCouncilSelect.val('').trigger('change');
            } else {
                $unionCouncilSelect.find('option').show();
            }
            
            filterVillages();
        });

        // Filter villages based on Union Council
        $('#filterUnionCouncil').on('change', function() {
            filterVillages();
        });
    });
</script>
@endpush

