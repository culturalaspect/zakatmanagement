@extends('layouts.app')

@section('title', config('app.name') . ' - Add Local Zakat Committee')
@section('page_title', 'Add Local Zakat Committee')
@section('breadcrumb', 'Add Local Zakat Committee')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Local Zakat Committee</h3>
                        <p class="text-muted mb-0">A unique code will be automatically generated for this committee</p>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('local-zakat-committees.store') }}" method="POST" id="committeeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">District <span class="text-danger">*</span></label>
                            <select name="district_id" id="district_id" class="form-control" required>
                                <option value="">Select District</option>
                                @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }} data-district-name="{{ $district->name }}">
                                    {{ $district->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Committee Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Area Coverage</label>
                            <textarea name="area_coverage" class="form-control" rows="3">{{ old('area_coverage') }}</textarea>
                            @error('area_coverage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Formation Date <span class="text-danger">*</span></label>
                            <input type="date" name="formation_date" class="form-control" value="{{ old('formation_date') }}" required>
                            @error('formation_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tenure (Years) <span class="text-danger">*</span></label>
                            <input type="number" name="tenure_years" class="form-control" value="{{ old('tenure_years', 3) }}" min="1" required>
                            <small class="text-muted">Tenure end date will be calculated automatically</small>
                            @error('tenure_years')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Select Mohallas <span class="text-danger">*</span></h5>
                    <p class="text-muted mb-3">Use the filters below to find mohallas, then select multiple mohallas that this committee will serve.</p>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter by District</label>
                            <select id="filter_district" class="form-control">
                                <option value="">All Districts</option>
                                @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter by Tehsil</label>
                            <select id="filter_tehsil" class="form-control">
                                <option value="">All Tehsils</option>
                                @foreach($tehsils as $tehsil)
                                <option value="{{ $tehsil->id }}" data-district-id="{{ $tehsil->district_id }}">{{ $tehsil->name }} ({{ $tehsil->district->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter by Union Council</label>
                            <select id="filter_union_council" class="form-control">
                                <option value="">All Union Councils</option>
                                @foreach($unionCouncils as $uc)
                                <option value="{{ $uc->id }}" data-tehsil-id="{{ $uc->tehsil_id }}">{{ $uc->name }} ({{ $uc->tehsil->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter by Village</label>
                            <select id="filter_village" class="form-control">
                                <option value="">All Villages</option>
                                @foreach($villages as $village)
                                <option value="{{ $village->id }}" data-union-council-id="{{ $village->union_council_id }}">{{ $village->name }} ({{ $village->unionCouncil->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mohallas <span class="text-danger">*</span> <small class="text-muted">(Select at least one)</small></label>
                        <select name="mohalla_ids[]" id="mohalla_ids" class="form-control" multiple="multiple" required>
                            @foreach($mohallas as $mohalla)
                            <option value="{{ $mohalla->id }}" 
                                    data-district-id="{{ $mohalla->village->unionCouncil->tehsil->district_id }}"
                                    data-tehsil-id="{{ $mohalla->village->unionCouncil->tehsil_id }}"
                                    data-union-council-id="{{ $mohalla->village->union_council_id }}"
                                    data-village-id="{{ $mohalla->village_id }}"
                                    {{ in_array($mohalla->id, old('mohalla_ids', [])) ? 'selected' : '' }}>
                                {{ $mohalla->name }} - {{ $mohalla->village->name }} (Village), {{ $mohalla->village->unionCouncil->name }} (UC), {{ $mohalla->village->unionCouncil->tehsil->name }} (Tehsil), {{ $mohalla->village->unionCouncil->tehsil->district->name }} (District)
                            </option>
                            @endforeach
                        </select>
                        @error('mohalla_ids')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('mohalla_ids.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('local-zakat-committees.index') }}" class="btn btn-secondary">Cancel</a>
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
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #567AED;
        border: 1px solid #567AED;
        color: white;
        padding: 2px 8px;
        margin: 3px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Store all mohalla options data
        const allMohallas = [];
        $('#mohalla_ids option').each(function() {
            allMohallas.push({
                value: $(this).val(),
                text: $(this).text(),
                districtId: $(this).data('district-id'),
                tehsilId: $(this).data('tehsil-id'),
                unionCouncilId: $(this).data('union-council-id'),
                villageId: $(this).data('village-id')
            });
        });
        
        // Store currently selected values
        let selectedValues = [];
        @if(old('mohalla_ids'))
            selectedValues = @json(old('mohalla_ids'));
        @endif
        
        // Function to rebuild Select2 options based on filters
        function rebuildMohallaOptions() {
            const districtId = $('#filter_district').val();
            const tehsilId = $('#filter_tehsil').val();
            const unionCouncilId = $('#filter_union_council').val();
            const villageId = $('#filter_village').val();
            
            // Get currently selected values before clearing
            const currentSelected = $('#mohalla_ids').val() || [];
            
            // Clear existing options
            $('#mohalla_ids').empty();
            
            // Filter and add matching options
            allMohallas.forEach(function(mohalla) {
                let show = true;
                
                // Convert to strings for comparison
                const mohallaDistrictId = String(mohalla.districtId);
                const mohallaTehsilId = String(mohalla.tehsilId);
                const mohallaUnionCouncilId = String(mohalla.unionCouncilId);
                const mohallaVillageId = String(mohalla.villageId);
                
                if (districtId && mohallaDistrictId !== String(districtId)) {
                    show = false;
                }
                if (tehsilId && mohallaTehsilId !== String(tehsilId)) {
                    show = false;
                }
                if (unionCouncilId && mohallaUnionCouncilId !== String(unionCouncilId)) {
                    show = false;
                }
                if (villageId && mohallaVillageId !== String(villageId)) {
                    show = false;
                }
                
                if (show) {
                    const $option = $('<option></option>')
                        .attr('value', mohalla.value)
                        .text(mohalla.text)
                        .data('district-id', mohalla.districtId)
                        .data('tehsil-id', mohalla.tehsilId)
                        .data('union-council-id', mohalla.unionCouncilId)
                        .data('village-id', mohalla.villageId);
                    
                    // Preserve selection if it was previously selected
                    const valueStr = String(mohalla.value);
                    const currentSelectedStr = currentSelected.map(v => String(v));
                    const selectedValuesStr = selectedValues.map(v => String(v));
                    
                    if (currentSelectedStr.includes(valueStr) || selectedValuesStr.includes(valueStr)) {
                        $option.prop('selected', true);
                    }
                    
                    $('#mohalla_ids').append($option);
                }
            });
            
            // Reinitialize Select2 to reflect changes
            $('#mohalla_ids').select2('destroy').select2({
                placeholder: 'Select mohallas...',
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });
        }
        
        // Initialize Select2 for mohallas
        $('#mohalla_ids').select2({
            placeholder: 'Select mohallas...',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });
        
        // Filter mohallas based on selections
        function filterMohallas() {
            rebuildMohallaOptions();
        }
        
        // Filter event handlers
        $('#filter_district').on('change', function() {
            const districtId = $(this).val();
            
            // Filter tehsils
            $('#filter_tehsil option').each(function() {
                if ($(this).val() === '') {
                    return true;
                }
                if (districtId === '' || $(this).data('district-id') == districtId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Reset and filter
            $('#filter_tehsil').val('');
            $('#filter_union_council').val('');
            $('#filter_village').val('');
            filterMohallas();
        });
        
        $('#filter_tehsil').on('change', function() {
            const tehsilId = $(this).val();
            
            // Filter union councils
            $('#filter_union_council option').each(function() {
                if ($(this).val() === '') {
                    return true;
                }
                if (tehsilId === '' || $(this).data('tehsil-id') == tehsilId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Reset and filter
            $('#filter_union_council').val('');
            $('#filter_village').val('');
            filterMohallas();
        });
        
        $('#filter_union_council').on('change', function() {
            const unionCouncilId = $(this).val();
            
            // Filter villages
            $('#filter_village option').each(function() {
                if ($(this).val() === '') {
                    return true;
                }
                if (unionCouncilId === '' || $(this).data('union-council-id') == unionCouncilId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Reset and filter
            $('#filter_village').val('');
            filterMohallas();
        });
        
        $('#filter_village').on('change', function() {
            filterMohallas();
        });
        
        // Form validation
        $('#committeeForm').on('submit', function(e) {
            const selectedMohallas = $('#mohalla_ids').val();
            if (!selectedMohallas || selectedMohallas.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select at least one mohalla for this committee.',
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });
    });
</script>
@endpush
