@extends('layouts.app')

@section('title', config('app.name') . ' - Edit Scheme')
@section('page_title', 'Edit Scheme')
@section('breadcrumb', 'Edit Scheme')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Scheme</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('schemes.update', $scheme) }}" method="POST" id="schemeForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $scheme->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Percentage <span class="text-danger">*</span></label>
                            <input type="number" name="percentage" class="form-control" value="{{ old('percentage', $scheme->percentage) }}" step="0.01" min="0" max="100" required>
                            @error('percentage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $scheme->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_age_restriction" id="has_age_restriction" value="1" {{ old('has_age_restriction', $scheme->has_age_restriction) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_age_restriction">Has Age Restriction</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="minAgeDiv" style="display: {{ old('has_age_restriction', $scheme->has_age_restriction) ? 'block' : 'none' }};">
                            <label class="form-label">Minimum Age</label>
                            <input type="number" name="minimum_age" class="form-control" value="{{ old('minimum_age', $scheme->minimum_age) }}" min="0">
                            @error('minimum_age')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $scheme->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
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
                        @foreach($scheme->categories as $index => $category)
                        <div class="row mb-2 category-row">
                            <div class="col-md-5">
                                <input type="text" name="categories[{{ $index }}][name]" class="form-control" value="{{ $category->name }}" placeholder="Category Name" required>
                            </div>
                            <div class="col-md-5">
                                <input type="number" name="categories[{{ $index }}][amount]" class="form-control" value="{{ $category->amount }}" placeholder="Amount" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-category">
                                    <i class="ti-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
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
    let categoryIndex = {{ $scheme->categories->count() }};
    $(document).ready(function() {
        $('#has_age_restriction').change(function() {
            if ($(this).is(':checked')) {
                $('#minAgeDiv').show();
            } else {
                $('#minAgeDiv').hide();
            }
        });
        
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

