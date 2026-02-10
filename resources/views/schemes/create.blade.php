@extends('layouts.app')

@section('title', config('app.name') . ' - Add Scheme')
@section('page_title', 'Add Scheme')
@section('breadcrumb', 'Add Scheme')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Scheme</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('schemes.store') }}" method="POST" id="schemeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Percentage <span class="text-danger">*</span></label>
                            <input type="number" name="percentage" class="form-control" value="{{ old('percentage') }}" step="0.01" min="0" max="100" required>
                            @error('percentage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_age_restriction" id="has_age_restriction" value="1" {{ old('has_age_restriction') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_age_restriction">Has Age Restriction</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="minAgeDiv" style="display: none;">
                            <label class="form-label">Minimum Age</label>
                            <input type="number" name="minimum_age" class="form-control" value="{{ old('minimum_age') }}" min="0">
                            @error('minimum_age')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_institutional" id="is_institutional" value="1" {{ old('is_institutional') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_institutional">Institutional Stipend</label>
                                <small class="form-text text-muted d-block">Check if this scheme is for institutions (schools, hospitals, madarsas)</small>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3" id="institutionalTypeDiv" style="display: none;">
                            <label class="form-label">Institutional Type <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="institutional_type" id="institutional_educational" value="educational" {{ old('institutional_type') == 'educational' ? 'checked' : '' }}>
                                <label class="form-check-label" for="institutional_educational">Educational Stipend</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="institutional_type" id="institutional_madarsa" value="madarsa" {{ old('institutional_type') == 'madarsa' ? 'checked' : '' }}>
                                <label class="form-check-label" for="institutional_madarsa">Madarsa Stipend</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="institutional_type" id="institutional_health" value="health" {{ old('institutional_type') == 'health' ? 'checked' : '' }}>
                                <label class="form-check-label" for="institutional_health">Health Stipend</label>
                            </div>
                            @error('institutional_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="allows_representative" id="allows_representative" value="1" {{ old('allows_representative') ? 'checked' : '' }}>
                                <label class="form-check-label" for="allows_representative">Allows Representative</label>
                                <small class="form-text text-muted d-block">Check if beneficiaries can have a representative to receive the stipend</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Beneficiary Required Fields</h5>
                    <p class="text-muted mb-3">Select which fields are mandatory when adding beneficiaries for this scheme:</p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_cnic" value="cnic" {{ in_array('cnic', old('beneficiary_required_fields', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="benef_cnic">CNIC</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_full_name" value="full_name" {{ in_array('full_name', old('beneficiary_required_fields', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="benef_full_name">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_father_husband_name" value="father_husband_name" {{ in_array('father_husband_name', old('beneficiary_required_fields', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="benef_father_husband_name">Father / Husband Name</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_mobile_number" value="mobile_number" {{ in_array('mobile_number', old('beneficiary_required_fields', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="benef_mobile_number">Mobile Number</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_date_of_birth" value="date_of_birth" {{ in_array('date_of_birth', old('beneficiary_required_fields', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="benef_date_of_birth">Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_gender" value="gender" {{ in_array('gender', old('beneficiary_required_fields', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="benef_gender">Gender</label>
                            </div>
                        </div>
                    </div>

                    <div id="representativeFieldsSection" style="display: none;">
                        <hr class="my-4">
                        <h5 class="mb-3">Representative Required Fields</h5>
                        <p class="text-muted mb-3">Select which fields are mandatory for the representative when adding beneficiaries for this scheme:</p>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_cnic" value="cnic" {{ in_array('cnic', old('representative_required_fields', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rep_cnic">CNIC</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_full_name" value="full_name" {{ in_array('full_name', old('representative_required_fields', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rep_full_name">Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_father_husband_name" value="father_husband_name" {{ in_array('father_husband_name', old('representative_required_fields', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rep_father_husband_name">Father / Husband Name</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_mobile_number" value="mobile_number" {{ in_array('mobile_number', old('representative_required_fields', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rep_mobile_number">Mobile Number</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_date_of_birth" value="date_of_birth" {{ in_array('date_of_birth', old('representative_required_fields', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rep_date_of_birth">Date of Birth</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_gender" value="gender" {{ in_array('gender', old('representative_required_fields', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rep_gender">Gender</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Scheme Categories</h5>
                            <button type="button" class="btn btn-sm btn-secondary" id="addCategory">
                                <i class="ti-plus"></i> Add Category
                            </button>
                        </div>
                    </div>
                    
                    <div id="categoriesContainer">
                        <!-- Categories will be added here dynamically -->
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('schemes.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#has_age_restriction').change(function() {
            if ($(this).is(':checked')) {
                $('#minAgeDiv').show();
            } else {
                $('#minAgeDiv').hide();
            }
        }).trigger('change');
        
        $('#is_institutional').change(function() {
            if ($(this).is(':checked')) {
                $('#institutionalTypeDiv').show();
                $('input[name="institutional_type"]').prop('required', true);
            } else {
                $('#institutionalTypeDiv').hide();
                $('input[name="institutional_type"]').prop('required', false);
                $('input[name="institutional_type"]').prop('checked', false);
            }
        }).trigger('change');
        
        $('#allows_representative').change(function() {
            if ($(this).is(':checked')) {
                $('#representativeFieldsSection').show();
            } else {
                $('#representativeFieldsSection').hide();
                // Uncheck all representative fields
                $('input[name="representative_required_fields[]"]').prop('checked', false);
            }
        }).trigger('change');
        
        let categoryIndex = 0;
        $('#addCategory').click(function() {
            const categoryHtml = `
                <div class="row mb-2 category-row">
                    <div class="col-md-5">
                        <input type="text" name="categories[${categoryIndex}][name]" class="form-control" placeholder="Category Name" required>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="categories[${categoryIndex}][amount]" class="form-control" placeholder="Amount" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-danger remove-category">
                            <i class="ti-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#categoriesContainer').append(categoryHtml);
            categoryIndex++;
        });
        
        $(document).on('click', '.remove-category', function() {
            $(this).closest('.category-row').remove();
        });
    });
</script>
@endpush
@endsection

