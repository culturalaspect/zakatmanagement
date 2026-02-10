@extends('layouts.app')

@section('title', config('app.name') . ' - Add Institution')
@section('page_title', 'Add Institution')
@section('breadcrumb', 'Add Institution')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Institution</h3>
                        <p class="text-muted mb-0">A unique code will be automatically generated if not provided</p>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('institutions.store') }}" method="POST" id="institutionForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Institution Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Institution Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Select Type</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Leave empty to auto-generate">
                            <small class="text-muted">Leave empty to auto-generate based on type</small>
                            @error('code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registration Number</label>
                            <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number') }}">
                            @error('registration_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Address Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">District</label>
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">Select District</option>
                                @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tehsil</label>
                            <select name="tehsil_id" id="tehsil_id" class="form-control">
                                <option value="">Select Tehsil</option>
                                @foreach($tehsils as $tehsil)
                                <option value="{{ $tehsil->id }}" 
                                        data-district-id="{{ $tehsil->district_id }}"
                                        {{ old('tehsil_id') == $tehsil->id ? 'selected' : '' }}>
                                    {{ $tehsil->name }} ({{ $tehsil->district->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('tehsil_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Union Council</label>
                            <select name="union_council_id" id="union_council_id" class="form-control">
                                <option value="">Select Union Council</option>
                                @foreach($unionCouncils as $uc)
                                <option value="{{ $uc->id }}" 
                                        data-tehsil-id="{{ $uc->tehsil_id }}"
                                        {{ old('union_council_id') == $uc->id ? 'selected' : '' }}>
                                    {{ $uc->name }} ({{ $uc->tehsil->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('union_council_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Village</label>
                            <select name="village_id" id="village_id" class="form-control">
                                <option value="">Select Village</option>
                                @foreach($villages as $village)
                                <option value="{{ $village->id }}" 
                                        data-union-council-id="{{ $village->union_council_id }}"
                                        {{ old('village_id') == $village->id ? 'selected' : '' }}>
                                    {{ $village->name }} ({{ $village->unionCouncil->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('village_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mohalla</label>
                            <select name="mohalla_id" id="mohalla_id" class="form-control">
                                <option value="">Select Mohalla</option>
                                @foreach($mohallas as $mohalla)
                                <option value="{{ $mohalla->id }}" 
                                        data-village-id="{{ $mohalla->village_id }}"
                                        {{ old('mohalla_id') == $mohalla->id ? 'selected' : '' }}>
                                    {{ $mohalla->name }} ({{ $mohalla->village->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('mohalla_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Additional address details">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Contact Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Principal/Director Name</label>
                            <input type="text" name="principal_director_name" class="form-control" value="{{ old('principal_director_name') }}">
                            @error('principal_director_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g., 0355-1234567">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="e.g., info@institution.com">
                            @error('email')
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
                            <a href="{{ route('institutions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Filter tehsils based on district
        $('#district_id').on('change', function() {
            const districtId = $(this).val();
            $('#tehsil_id option').show();
            if (districtId) {
                $('#tehsil_id option').not('[value=""]').not('[data-district-id="' + districtId + '"]').hide();
                $('#tehsil_id').val('').trigger('change');
            }
        });

        // Filter union councils based on tehsil
        $('#tehsil_id').on('change', function() {
            const tehsilId = $(this).val();
            $('#union_council_id option').show();
            if (tehsilId) {
                $('#union_council_id option').not('[value=""]').not('[data-tehsil-id="' + tehsilId + '"]').hide();
                $('#union_council_id').val('').trigger('change');
            }
        });

        // Filter villages based on union council
        $('#union_council_id').on('change', function() {
            const unionCouncilId = $(this).val();
            $('#village_id option').show();
            if (unionCouncilId) {
                $('#village_id option').not('[value=""]').not('[data-union-council-id="' + unionCouncilId + '"]').hide();
                $('#village_id').val('').trigger('change');
            }
        });

        // Filter mohallas based on village
        $('#village_id').on('change', function() {
            const villageId = $(this).val();
            $('#mohalla_id option').show();
            if (villageId) {
                $('#mohalla_id option').not('[value=""]').not('[data-village-id="' + villageId + '"]').hide();
                $('#mohalla_id').val('').trigger('change');
            }
        });

        // Initialize on page load
        $('#district_id').trigger('change');
    });
</script>
@endpush
@endsection
