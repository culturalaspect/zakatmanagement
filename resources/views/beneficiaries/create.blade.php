@extends('layouts.app')

@section('title', config('app.name') . ' - Register Beneficiary')
@section('page_title', 'Register Beneficiary')
@section('breadcrumb', 'Register Beneficiary')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Register New Beneficiary</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('beneficiaries.store') }}" method="POST" id="beneficiaryForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phase <span class="text-danger">*</span></label>
                            <select name="phase_id" id="phase_id" class="form-control" required>
                                <option value="">Select Phase</option>
                                @foreach($phases as $phase)
                                <option value="{{ $phase->id }}" 
                                        data-allocation="{{ $phase->installment->fundAllocation->id ?? '' }}"
                                        {{ (isset($selectedPhaseId) && $selectedPhaseId == $phase->id) || old('phase_id') == $phase->id ? 'selected' : '' }}>
                                    {{ $phase->name }} - {{ $phase->installment->fundAllocation->financialYear->name ?? '' }} ({{ $phase->district->name ?? '' }})
                                </option>
                                @endforeach
                            </select>
                            @error('phase_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Scheme <span class="text-danger">*</span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control" required>
                                <option value="">Select Scheme</option>
                                @foreach($schemes as $scheme)
                                <option value="{{ $scheme->id }}" data-has-age="{{ $scheme->has_age_restriction ? '1' : '0' }}" data-min-age="{{ $scheme->minimum_age ?? '' }}">
                                    {{ $scheme->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('scheme_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3" id="schemeCategoryDiv" style="display: none;">
                            <label class="form-label">Scheme Category <span class="text-danger">*</span></label>
                            <select name="scheme_category_id" id="scheme_category_id" class="form-control">
                                <option value="">Select Category</option>
                            </select>
                            @error('scheme_category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Local Zakat Committee <span class="text-danger">*</span></label>
                            <select name="local_zakat_committee_id" class="form-control" required>
                                <option value="">Select Committee</option>
                                @foreach($committees as $committee)
                                <option value="{{ $committee->id }}">{{ $committee->name }} ({{ $committee->district->name ?? '' }})</option>
                                @endforeach
                            </select>
                            @error('local_zakat_committee_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Beneficiary Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CNIC <span class="text-danger">*</span></label>
                            <input type="text" name="cnic" class="form-control" value="{{ old('cnic') }}" required>
                            @error('cnic')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                            <input type="text" name="father_husband_name" class="form-control" value="{{ old('father_husband_name') }}" required>
                            @error('father_husband_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number') }}">
                            @error('mobile_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" step="0.01" min="0" required>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requires_representative" id="requires_representative" value="1" {{ old('requires_representative') ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_representative">Requires Representative</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="representativeDiv" style="display: none;">
                        <hr>
                        <h5>Representative Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CNIC <span class="text-danger">*</span></label>
                                <input type="text" name="representative[cnic]" class="form-control" value="{{ old('representative.cnic') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="representative[full_name]" class="form-control" value="{{ old('representative.full_name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                                <input type="text" name="representative[father_husband_name]" class="form-control" value="{{ old('representative.father_husband_name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="representative[mobile_number]" class="form-control" value="{{ old('representative.mobile_number') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="representative[date_of_birth]" class="form-control" value="{{ old('representative.date_of_birth') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="representative[gender]" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('representative.gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('representative.gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('representative.gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                <input type="text" name="representative[relationship]" class="form-control" value="{{ old('representative.relationship') }}" placeholder="e.g., Father, Mother, Brother">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Register Beneficiary</button>
                            <a href="{{ route('beneficiaries.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Load scheme categories when scheme is selected
        $('#scheme_id').change(function() {
            const schemeId = $(this).val();
            if (schemeId) {
                $.ajax({
                    url: '{{ route("beneficiaries.scheme-categories", ":id") }}'.replace(':id', schemeId),
                    type: 'GET',
                    success: function(data) {
                        const categorySelect = $('#scheme_category_id');
                        categorySelect.empty();
                        if (data.length > 0) {
                            categorySelect.append('<option value="">Select Category</option>');
                            $('#schemeCategoryDiv').show();
                            data.forEach(function(category) {
                                categorySelect.append(`<option value="${category.id}" data-amount="${category.amount}">${category.name} (Rs. ${category.amount})</option>`);
                            });
                        } else {
                            $('#schemeCategoryDiv').hide();
                        }
                    }
                });
            } else {
                $('#schemeCategoryDiv').hide();
            }
        });
        
        // Auto-set amount when category is selected
        $('#scheme_category_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const amount = selectedOption.data('amount');
            if (amount) {
                $('#amount').val(amount);
            }
        });
        
        // Show/hide representative section
        $('#requires_representative').change(function() {
            if ($(this).is(':checked')) {
                $('#representativeDiv').show();
            } else {
                $('#representativeDiv').hide();
            }
        }).trigger('change');
        
        // Check age and auto-require representative if needed
        $('#date_of_birth').change(function() {
            const dob = new Date($(this).val());
            const today = new Date();
            const age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            if (age < 18) {
                $('#requires_representative').prop('checked', true).trigger('change');
            }
        });
    });
</script>
@endpush
@endsection

