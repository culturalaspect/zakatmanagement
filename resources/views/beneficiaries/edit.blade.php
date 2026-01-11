@extends('layouts.app')

@section('title', config('app.name') . ' - Edit Beneficiary')
@section('page_title', 'Edit Beneficiary')
@section('breadcrumb', 'Edit Beneficiary')

@push('styles')
<style>
    /* API Data Container Styles */
    .api-data-container {
        margin-top: 5px;
        padding: 8px;
        background-color: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.875rem;
    }
    
    .api-data-value {
        flex: 1;
        color: #004085;
        font-weight: 500;
    }
    
    .api-copy-btn {
        margin-left: 8px;
        padding: 4px 8px;
        font-size: 0.75rem;
    }
    
    /* Family Tree Grid Styles */
    .family-tree-grid {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        min-height: 150px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .family-tree-root {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding-bottom: 20px;
    }
    
    .family-tree-children {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
        position: relative;
        padding-top: 20px;
    }
    
    .family-tree-node {
        position: relative;
        width: 140px;
        background: #fff;
        border: 2px solid #ced4da;
        border-radius: 8px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    
    .family-tree-node:hover {
        border-color: #567AED;
        box-shadow: 0 4px 8px rgba(86, 122, 237, 0.2);
        transform: translateY(-2px);
    }
    
    .family-tree-node.selected {
        border-color: #28a745;
        background-color: #d4edda;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }
    
    .family-tree-node.selected::after {
        content: '✓';
        position: absolute;
        top: -8px;
        right: -8px;
        background: #28a745;
        color: #fff;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .family-tree-node.root-node {
        border-color: #ffc107;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        font-weight: 600;
    }
    
    .family-tree-node.root-node::before {
        content: "👑";
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ffc107;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .family-tree-node.child-node {
        border-color: #17a2b8;
        background: linear-gradient(135deg, #e7f3ff 0%, #d1ecf1 100%);
    }
    
    .family-tree-node-name {
        font-weight: 600;
        font-size: 0.875rem;
        color: #212529;
        margin: 0 0 4px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .family-tree-node-relationship {
        font-size: 0.7rem;
        color: #6c757d;
        background-color: #e9ecef;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 500;
        display: inline-block;
    }
    
    .family-tree-node-age {
        font-size: 0.7rem;
        color: #495057;
        margin-top: 4px;
        font-weight: 500;
    }
    
    /* Custom Toast Notification Styles */
    .custom-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 300px;
        max-width: 500px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        padding: 16px;
        animation: slideInRight 0.3s ease-out;
    }
    
    .custom-toast.success {
        border-left: 4px solid #28a745;
    }
    
    .custom-toast.error {
        border-left: 4px solid #dc3545;
    }
    
    .custom-toast.warning {
        border-left: 4px solid #ffc107;
    }
    
    .custom-toast.info {
        border-left: 4px solid #17a2b8;
    }
    
    .custom-toast-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-weight: bold;
        font-size: 14px;
    }
    
    .custom-toast.success .custom-toast-icon {
        background-color: #28a745;
        color: #fff;
    }
    
    .custom-toast.error .custom-toast-icon {
        background-color: #dc3545;
        color: #fff;
    }
    
    .custom-toast.warning .custom-toast-icon {
        background-color: #ffc107;
        color: #000;
    }
    
    .custom-toast.info .custom-toast-icon {
        background-color: #17a2b8;
        color: #fff;
    }
    
    .custom-toast-content {
        flex: 1;
    }
    
    .custom-toast-title {
        font-weight: 600;
        margin-bottom: 4px;
        color: #333;
    }
    
    .custom-toast-message {
        color: #666;
        font-size: 0.9rem;
    }
    
    .custom-toast-close {
        background: none;
        border: none;
        font-size: 20px;
        color: #999;
        cursor: pointer;
        padding: 0;
        margin-left: 12px;
        line-height: 1;
    }
    
    .custom-toast-close:hover {
        color: #333;
    }
    
    .custom-toast-progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background-color: rgba(255, 255, 255, 0.3);
        animation: progressBar 3s linear forwards;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes progressBar {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
    
    .custom-toast.hiding {
        animation: slideOutRight 0.3s ease-out forwards;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Beneficiary</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('beneficiaries.update', $beneficiary) }}" method="POST" id="beneficiaryForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phase <span class="text-danger">*</span></label>
                            <select name="phase_id" id="phase_id" class="form-control" required>
                                <option value="">Select Phase</option>
                                @foreach($phases as $phase)
                                <option value="{{ $phase->id }}" 
                                        data-allocation="{{ $phase->installment->fundAllocation->id ?? '' }}"
                                        {{ old('phase_id', $beneficiary->phase_id) == $phase->id ? 'selected' : '' }}>
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
                                <option value="{{ $scheme->id }}" 
                                        data-has-age="{{ $scheme->has_age_restriction ? '1' : '0' }}" 
                                        data-min-age="{{ $scheme->minimum_age ?? '' }}"
                                        {{ old('scheme_id', $beneficiary->scheme_id) == $scheme->id ? 'selected' : '' }}>
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
                                <option value="{{ $committee->id }}" {{ old('local_zakat_committee_id', $beneficiary->local_zakat_committee_id) == $committee->id ? 'selected' : '' }}>
                                    {{ $committee->name }} ({{ $committee->district->name ?? '' }})
                                </option>
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
                            <div class="input-group">
                                <input type="text" name="cnic" id="edit_cnic" class="form-control" value="{{ old('cnic', $beneficiary->cnic) }}" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" required>
                                <button type="button" class="btn btn-primary" id="edit_fetchBeneficiaryDetailsBtn" title="Fetch details from Wheat Distribution System">
                                    <i class="ti-search"></i> Fetch Details
                                </button>
                            </div>
                            <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                            @error('cnic')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" name="full_name" id="edit_full_name" class="form-control" value="{{ old('full_name', $beneficiary->full_name) }}" required>
                                <div id="edit_apiFullName" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_full_name" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            @error('full_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" name="father_husband_name" id="edit_father_husband_name" class="form-control" value="{{ old('father_husband_name', $beneficiary->father_husband_name) }}" required>
                                <div id="edit_apiFatherHusbandName" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_father_husband_name" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            @error('father_husband_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number</label>
                            <div class="position-relative">
                                <input type="text" name="mobile_number" id="edit_mobile_number" class="form-control" value="{{ old('mobile_number', $beneficiary->mobile_number) }}" placeholder="03XX-XXXXXXX" maxlength="12">
                                <div id="edit_apiMobileNumber" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_mobile_number" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Format: 03XX-XXXXXXX</small>
                            @error('mobile_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="date" name="date_of_birth" id="edit_date_of_birth" class="form-control" value="{{ old('date_of_birth', $beneficiary->date_of_birth ? $beneficiary->date_of_birth->format('Y-m-d') : '') }}" required>
                                <div id="edit_apiDateOfBirth" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_date_of_birth" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            @error('date_of_birth')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <select name="gender" id="edit_gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $beneficiary->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $beneficiary->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $beneficiary->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div id="edit_apiGender" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_gender" data-is-select="true" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $beneficiary->amount) }}" step="0.01" min="0" required>
                            <small class="text-muted" id="amountHint"></small>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requires_representative" id="requires_representative" value="1" {{ old('requires_representative', $beneficiary->requires_representative) ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_representative">Requires Representative</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="edit_representativeDiv" style="display: {{ old('requires_representative', $beneficiary->requires_representative) ? 'block' : 'none' }};">
                        <hr>
                        <h5>Representative Information</h5>
                        <div class="alert alert-info mb-3" role="alert">
                            <i class="ti-info-alt"></i> <strong>Note:</strong> For beneficiaries below 18 years, a representative (above 18) is required. You can fetch family members from the Wheat Distribution System or enter details manually.
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary btn-sm" id="edit_fetchFamilyMembersBtn" title="Fetch family members from Wheat Distribution System">
                                    <i class="ti-search"></i> Fetch Family Members
                                </button>
                                <div id="edit_familyTreeContainer" style="display: none; margin-top: 15px;">
                                    <label class="form-label mb-2"><strong>Select Family Member (Click on any node):</strong></label>
                                    <div id="edit_familyTreeGrid" class="family-tree-grid"></div>
                                    <small class="text-muted d-block mt-2">Click on any family member node to auto-fill representative details</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CNIC <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="representative[cnic]" id="edit_rep_cnic" class="form-control" value="{{ old('representative.cnic', $beneficiary->representative->cnic ?? '') }}" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}">
                                    <button type="button" class="btn btn-primary" id="edit_fetchRepDetailsBtn" title="Fetch details from Wheat Distribution System">
                                        <i class="ti-search"></i> Fetch Details
                                    </button>
                                </div>
                                <div class="position-relative" style="margin-top: 5px;">
                                    <div id="edit_apiRepCnic" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_rep_cnic" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="representative[full_name]" id="edit_rep_full_name" class="form-control" value="{{ old('representative.full_name', $beneficiary->representative->full_name ?? '') }}">
                                    <div id="edit_apiRepFullName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_rep_full_name" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="representative[father_husband_name]" id="edit_rep_father_husband_name" class="form-control" value="{{ old('representative.father_husband_name', $beneficiary->representative->father_husband_name ?? '') }}">
                                    <div id="edit_apiRepFatherHusbandName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_rep_father_husband_name" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <div class="position-relative">
                                    <input type="text" name="representative[mobile_number]" id="edit_rep_mobile_number" class="form-control" value="{{ old('representative.mobile_number', $beneficiary->representative->mobile_number ?? '') }}" placeholder="03XX-XXXXXXX" maxlength="12">
                                    <div id="edit_apiRepMobileNumber" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_rep_mobile_number" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Format: 03XX-XXXXXXX</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="date" name="representative[date_of_birth]" id="edit_rep_date_of_birth" class="form-control" value="{{ old('representative.date_of_birth', $beneficiary->representative && $beneficiary->representative->date_of_birth ? $beneficiary->representative->date_of_birth->format('Y-m-d') : '') }}">
                                    <div id="edit_apiRepDateOfBirth" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_rep_date_of_birth" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select name="representative[gender]" id="edit_rep_gender" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('representative.gender', $beneficiary->representative->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('representative.gender', $beneficiary->representative->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('representative.gender', $beneficiary->representative->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div id="edit_apiRepGender" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_rep_gender" data-is-select="true" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="representative[relationship]" id="edit_rep_relationship" class="form-control" value="{{ old('representative.relationship', $beneficiary->representative->relationship ?? '') }}" placeholder="e.g., Father, Mother, Brother">
                                    <div id="edit_apiRepRelationship" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_rep_relationship" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Beneficiary</button>
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
        const prefix = 'edit_';
        
        // CNIC Formatting Function
        function formatCNIC(value) {
            value = value.replace(/\D/g, '');
            if (value.length > 13) value = value.substring(0, 13);
            let formatted = '';
            if (value.length > 0) {
                formatted = value.substring(0, 5);
                if (value.length > 5) {
                    formatted += '-' + value.substring(5, 12);
                    if (value.length > 12) {
                        formatted += '-' + value.substring(12, 13);
                    }
                }
            }
            return formatted;
        }

        // Mobile Number Formatting Function
        function formatMobileNumber(value) {
            value = value.replace(/\D/g, '');
            if (value.length > 11) value = value.substring(0, 11);
            let formatted = '';
            if (value.length > 0) {
                if (value.length >= 2 && value.substring(0, 2) === '03') {
                    formatted = value.substring(0, 4);
                    if (value.length > 4) {
                        formatted += '-' + value.substring(4, 11);
                    }
                } else {
                    if (value.length <= 4) {
                        formatted = value;
                    } else {
                        formatted = value.substring(0, 4) + '-' + value.substring(4, 11);
                    }
                }
            }
            return formatted;
        }
        
        // Custom Toast Notification Function
        function showCustomToast(type, title, message, duration = 3000) {
            $('.custom-toast').remove();
            const toast = $('<div>')
                .addClass('custom-toast ' + type)
                .html(`
                    <div class="custom-toast-icon">
                        ${type === 'success' ? '✓' : type === 'error' ? '✕' : type === 'warning' ? '⚠' : 'ℹ'}
                    </div>
                    <div class="custom-toast-content">
                        <div class="custom-toast-title">${title}</div>
                        <div class="custom-toast-message">${message}</div>
                    </div>
                    <button type="button" class="custom-toast-close" onclick="$(this).closest('.custom-toast').remove()">×</button>
                    <div class="custom-toast-progress-bar"></div>
                `);
            $('body').append(toast);
            if (duration > 0) {
                setTimeout(function() {
                    toast.addClass('hiding');
                    setTimeout(function() {
                        toast.remove();
                    }, 300);
                }, duration);
            }
            toast.on('mouseenter', function() {
                toast.find('.custom-toast-progress-bar').css('animation-play-state', 'paused');
            }).on('mouseleave', function() {
                toast.find('.custom-toast-progress-bar').css('animation-play-state', 'running');
            });
        }
        
        // Helper function to show API data next to a field
        function showApiData(containerId, value, displayValue = null) {
            const container = $(`#${containerId}`);
            const valueDiv = container.find('.api-data-value');
            if (value && value !== 'N/A' && value !== null && value !== '') {
                const displayText = displayValue !== null ? displayValue : value;
                valueDiv.text(displayText);
                container.data('original-value', value);
                container.show();
            } else {
                container.hide();
            }
        }

        // Helper function to hide all API data containers
        function hideAllApiData(prefix = '') {
            $(`div[id^="${prefix}api"]`).hide().find('.api-data-value').empty();
        }

        // Function to display API data for beneficiary
        function displayBeneficiaryApiData(data, prefix = '') {
            hideAllApiData(prefix);
            function formatDateForDisplay(dateString) {
                if (!dateString) return null;
                try {
                    const date = new Date(dateString + 'T00:00:00');
                    if (!isNaN(date.getTime())) {
                        return date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    }
                } catch (e) {
                    return dateString;
                }
                return dateString;
            }
            showApiData(`${prefix}apiFullName`, data.full_name);
            showApiData(`${prefix}apiFatherHusbandName`, data.father_husband_name);
            showApiData(`${prefix}apiMobileNumber`, data.mobile_number);
            showApiData(`${prefix}apiDateOfBirth`, data.date_of_birth, formatDateForDisplay(data.date_of_birth));
            showApiData(`${prefix}apiGender`, data.gender);
        }

        // Function to display API data for representative
        function displayRepresentativeApiData(data, prefix = 'rep_') {
            hideAllApiData(`${prefix}api`);
            function formatDateForDisplay(dateString) {
                if (!dateString) return null;
                try {
                    const date = new Date(dateString + 'T00:00:00');
                    if (!isNaN(date.getTime())) {
                        return date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    }
                } catch (e) {
                    return dateString;
                }
                return dateString;
            }
            showApiData(`${prefix}apiCnic`, data.cnic);
            showApiData(`${prefix}apiFullName`, data.full_name);
            showApiData(`${prefix}apiFatherHusbandName`, data.father_husband_name);
            showApiData(`${prefix}apiMobileNumber`, data.mobile_number);
            showApiData(`${prefix}apiDateOfBirth`, data.date_of_birth, formatDateForDisplay(data.date_of_birth));
            showApiData(`${prefix}apiGender`, data.gender);
            showApiData(`${prefix}apiRelationship`, data.relationship);
        }

        // Generic API Fetcher
        function fetchApiData(url, cnic, successCallback, errorCallback, finalCallback) {
            const apiBaseUrl = '{{ config("wheat_api.base_url", "http://localhost:8001/api") }}';
            const apiToken = '{{ config("wheat_api.token", "") }}';
            const apiUsername = '{{ config("wheat_api.username", "") }}';
            const apiPassword = '{{ config("wheat_api.password", "") }}';

            function makeApiCall(token) {
                $.ajax({
                    url: `${apiBaseUrl}${url}`,
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify({ cnic: cnic }),
                    contentType: 'application/json',
                    success: successCallback,
                    error: errorCallback,
                    complete: finalCallback
                });
            }

            if (apiToken) {
                makeApiCall(apiToken);
            } else {
                if (!apiUsername || !apiPassword) {
                    showCustomToast('warning', 'API Configuration Missing', 'Please configure WHEAT_API_USERNAME and WHEAT_API_PASSWORD in your .env file.');
                    if (finalCallback) finalCallback();
                    return;
                }

                $.ajax({
                    url: `${apiBaseUrl}/external/auth/login`,
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify({
                        username: apiUsername,
                        password: apiPassword
                    }),
                    contentType: 'application/json',
                    success: function(loginResponse) {
                        if (loginResponse.success && loginResponse.data && loginResponse.data.access_token) {
                            makeApiCall(loginResponse.data.access_token);
                        } else {
                            showCustomToast('error', 'Authentication Failed', 'Failed to authenticate with Wheat Distribution API. Please verify your API credentials.');
                            if (finalCallback) finalCallback();
                        }
                    },
                    error: function(loginXhr) {
                        let errorMessage = 'Failed to authenticate with Wheat Distribution API.';
                        if (loginXhr.status === 401) {
                            errorMessage = 'Invalid username or password. Please check your API credentials.';
                        } else if (loginXhr.status === 0) {
                            errorMessage = 'Unable to connect to the API server. Please check if the Wheat Distribution application is running.';
                        }
                        showCustomToast('error', 'Authentication Error', errorMessage);
                        if (finalCallback) finalCallback();
                    }
                });
            }
        }

        // Fetch Beneficiary Data
        function fetchBeneficiaryDataForEdit(cnic, callback) {
            fetchApiData(
                '/external/zakat/member/lookup',
                cnic,
                function(response) {
                    if (response.success && response.data) {
                        displayBeneficiaryApiData(response.data, prefix);
                        showCustomToast('success', 'Member Found', 'Verified member data fetched. Review and copy to form fields.');
                        checkAgeAndRequireRepresentative(response.data.date_of_birth, prefix);
                    } else {
                        showCustomToast('info', 'Member Not Found', response.message || 'Member with this CNIC was not found in the verified database. Please enter details manually.');
                        hideAllApiData(prefix);
                        $(`#${prefix}representativeDiv`).hide();
                    }
                },
                function(xhr) {
                    let errorTitle = 'API Error';
                    let errorMessage = 'Failed to fetch member details.';
                    let errorIcon = 'error';

                    if (xhr.status === 401) {
                        errorTitle = 'Authentication Failed';
                        errorMessage = 'Authentication failed. Please check API credentials in your .env file.';
                    } else if (xhr.status === 404) {
                        const response = xhr.responseJSON;
                        if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                            errorTitle = 'Member Not Found';
                            errorMessage = response.message || 'Member with this CNIC was not found in the verified database. Please enter details manually.';
                            errorIcon = 'info';
                        } else {
                            errorMessage = 'The requested resource was not found.';
                        }
                    } else if (xhr.status === 0) {
                        errorTitle = 'Connection Error';
                        errorMessage = 'Unable to connect to the API server. Please check if the Wheat Distribution application is running.';
                    }
                    showCustomToast(errorIcon === 'info' ? 'info' : 'error', errorTitle, errorMessage);
                    hideAllApiData(prefix);
                    $(`#${prefix}representativeDiv`).hide();
                },
                callback
            );
        }

        // Fetch Representative Data
        function fetchRepresentativeDataForEdit(cnic, callback) {
            fetchApiData(
                '/external/zakat/member/lookup',
                cnic,
                function(response) {
                    if (response.success && response.data) {
                        displayRepresentativeApiData(response.data, `${prefix}rep_`);
                        showCustomToast('success', 'Representative Found', 'Verified representative data fetched. Review and copy to form fields.');
                    } else {
                        showCustomToast('info', 'Representative Not Found', response.message || 'Representative with this CNIC was not found in the verified database. Please enter details manually.');
                        hideAllApiData(`${prefix}rep_`);
                    }
                },
                function(xhr) {
                    let errorTitle = 'API Error';
                    let errorMessage = 'Failed to fetch representative details.';
                    let errorIcon = 'error';

                    if (xhr.status === 401) {
                        errorTitle = 'Authentication Failed';
                        errorMessage = 'Authentication failed. Please check API credentials in your .env file.';
                    } else if (xhr.status === 404) {
                        const response = xhr.responseJSON;
                        if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                            errorTitle = 'Representative Not Found';
                            errorMessage = response.message || 'Representative with this CNIC was not found in the verified database. Please enter details manually.';
                            errorIcon = 'info';
                        } else {
                            errorMessage = 'The requested resource was not found.';
                        }
                    } else if (xhr.status === 0) {
                        errorTitle = 'Connection Error';
                        errorMessage = 'Unable to connect to the API server. Please check if the Wheat Distribution application is running.';
                    }
                    showCustomToast(errorIcon === 'info' ? 'info' : 'error', errorTitle, errorMessage);
                    hideAllApiData(`${prefix}rep_`);
                },
                callback
            );
        }

        // Fetch Family Members
        function fetchFamilyMembersForEdit(beneficiaryCnic, callback) {
            fetchApiData(
                '/external/zakat/member/family-members',
                beneficiaryCnic,
                function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        displayFamilyTree(response.data, prefix);
                        $(`#${prefix}familyTreeContainer`).show();
                        showCustomToast('success', 'Family Members Found', `Found ${response.data.length} eligible family member(s). Click on any node to auto-fill representative details.`);
                    } else {
                        $(`#${prefix}familyTreeContainer`).hide();
                        showCustomToast('info', 'No Family Members Found', response.message || 'No eligible family members found (NADRA verified, above 18 years, and active). Please enter representative details manually.');
                    }
                },
                function(xhr) {
                    $(`#${prefix}familyTreeContainer`).hide();
                    let errorTitle = 'API Error';
                    let errorMessage = 'Failed to fetch family members.';
                    let errorIcon = 'error';

                    if (xhr.status === 401) {
                        errorTitle = 'Authentication Failed';
                        errorMessage = 'Authentication failed. Please check API credentials.';
                    } else if (xhr.status === 404) {
                        const response = xhr.responseJSON;
                        if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                            errorTitle = 'Beneficiary Not Found';
                            errorMessage = response.message || 'Beneficiary member not found in the system.';
                            errorIcon = 'info';
                        } else if (response && response.error_code === 'NO_HOUSEHOLD') {
                            errorTitle = 'No Household';
                            errorMessage = response.message || 'Beneficiary is not associated with any active household.';
                            errorIcon = 'info';
                        }
                    } else if (xhr.status === 0) {
                        errorTitle = 'Connection Error';
                        errorMessage = 'Unable to connect to the API server. Please check if the Wheat Distribution application is running.';
                    }
                    showCustomToast(errorIcon === 'info' ? 'info' : 'error', errorTitle, errorMessage);
                },
                callback
            );
        }

        // Display Family Tree
        function displayFamilyTree(familyMembers, prefix = '') {
            const treeGrid = $(`#${prefix}familyTreeGrid`);
            treeGrid.empty();

            const headMember = familyMembers.find(m => m.relationship && m.relationship.toLowerCase() === 'head');
            const otherMembers = familyMembers.filter(m => !m.relationship || m.relationship.toLowerCase() !== 'head');

            const rootContainer = $('<div class="family-tree-root"></div>');
            if (otherMembers.length > 0) {
                rootContainer.addClass('has-children');
            }

            if (headMember) {
                const headNode = createFamilyTreeNode(headMember, true, prefix);
                rootContainer.append(headNode);
            }

            if (otherMembers.length > 0) {
                const childrenContainer = $('<div class="family-tree-children"></div>');
                childrenContainer.addClass('has-children');

                otherMembers.forEach(function(member) {
                    const childNode = createFamilyTreeNode(member, false, prefix);
                    childrenContainer.append(childNode);
                });

                rootContainer.append(childrenContainer);
            }

            treeGrid.append(rootContainer);

            const currentRepCnic = $(`#${prefix}rep_cnic`).val();
            if (currentRepCnic) {
                $(`[data-family-member*="${currentRepCnic}"]`).addClass('selected');
            }
        }

        // Create Family Tree Node
        function createFamilyTreeNode(member, isRoot, prefix = '') {
            const nodeClass = isRoot ? 'family-tree-node root-node' : 'family-tree-node child-node';
            const relationshipBadge = member.relationship || 'Family Member';
            const ageBadge = member.age ? `${member.age} yrs` : 'N/A';

            const nodeHtml = `
                <div class="${nodeClass}" data-family-member='${JSON.stringify(member)}' data-prefix="${prefix}">
                    <div class="family-tree-node-header">
                        <h6 class="family-tree-node-name" title="${member.full_name || 'N/A'}">${member.full_name || 'N/A'}</h6>
                        <span class="family-tree-node-relationship">${relationshipBadge}</span>
                    </div>
                    <div class="family-tree-node-age">Age: ${ageBadge}</div>
                </div>
            `;

            return $(nodeHtml);
        }

        // Check age and show/hide representative form
        function checkAgeAndRequireRepresentative(dateOfBirth, prefix = '') {
            const representativeDiv = $(`#${prefix}representativeDiv`);
            const dobInput = $(`#${prefix}date_of_birth`);

            if (dateOfBirth) {
                const birthDate = new Date(dateOfBirth);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 18) {
                    representativeDiv.show();
                    $('#requires_representative').prop('checked', true);
                    $(`#${prefix}rep_cnic`).prop('required', true);
                    $(`#${prefix}rep_full_name`).prop('required', true);
                    $(`#${prefix}rep_father_husband_name`).prop('required', true);
                    $(`#${prefix}rep_date_of_birth`).prop('required', true);
                    $(`#${prefix}rep_gender`).prop('required', true);
                    $(`#${prefix}rep_relationship`).prop('required', true);
                } else {
                    representativeDiv.hide();
                    $('#requires_representative').prop('checked', false);
                    $(`#${prefix}rep_cnic`).prop('required', false).val('');
                    $(`#${prefix}rep_full_name`).prop('required', false).val('');
                    $(`#${prefix}rep_father_husband_name`).prop('required', false).val('');
                    $(`#${prefix}rep_mobile_number`).val('');
                    $(`#${prefix}rep_date_of_birth`).prop('required', false).val('');
                    $(`#${prefix}rep_gender`).prop('required', false).val('').trigger('change');
                    $(`#${prefix}rep_relationship`).prop('required', false).val('');
                    hideAllApiData(`${prefix}rep_`);
                    $(`#${prefix}familyTreeContainer`).hide();
                }
            } else {
                representativeDiv.hide();
                $('#requires_representative').prop('checked', false);
            }
        }

        // CNIC formatting on input
        $(`#${prefix}cnic`).on('input', function() {
            $(this).val(formatCNIC($(this).val()));
        });
        $(`#${prefix}rep_cnic`).on('input', function() {
            $(this).val(formatCNIC($(this).val()));
        });

        // Mobile number formatting on input
        $(`#${prefix}mobile_number`).on('input', function() {
            $(this).val(formatMobileNumber($(this).val()));
        });
        $(`#${prefix}rep_mobile_number`).on('input', function() {
            $(this).val(formatMobileNumber($(this).val()));
        });

        // Beneficiary fetch details button
        $(`#${prefix}fetchBeneficiaryDetailsBtn`).on('click', function() {
            const fetchBtn = $(this);
            const originalHtml = fetchBtn.html();
            fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');

            const cnic = $(`#${prefix}cnic`).val().trim();
            if (!cnic) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'CNIC Required', 'Please enter beneficiary CNIC first.');
                return;
            }

            const cnicDigits = cnic.replace(/\D/g, '');
            if (cnicDigits.length !== 13) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'Invalid CNIC', 'Please enter a valid CNIC (13 digits).');
                return;
            }

            fetchBeneficiaryDataForEdit(cnic, function() {
                fetchBtn.prop('disabled', false).html(originalHtml);
            });
        });

        // Representative fetch details button
        $(`#${prefix}fetchRepDetailsBtn`).on('click', function() {
            const fetchBtn = $(this);
            const originalHtml = fetchBtn.html();
            fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');

            const cnic = $(`#${prefix}rep_cnic`).val().trim();
            if (!cnic) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'CNIC Required', 'Please enter representative CNIC first.');
                return;
            }

            const cnicDigits = cnic.replace(/\D/g, '');
            if (cnicDigits.length !== 13) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'Invalid CNIC', 'Please enter a valid CNIC (13 digits).');
                return;
            }

            fetchRepresentativeDataForEdit(cnic, function() {
                fetchBtn.prop('disabled', false).html(originalHtml);
            });
        });

        // Fetch family members button
        $(`#${prefix}fetchFamilyMembersBtn`).on('click', function() {
            const fetchBtn = $(this);
            const originalHtml = fetchBtn.html();
            fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');

            const beneficiaryCnic = $(`#${prefix}cnic`).val().trim();
            if (!beneficiaryCnic) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'Beneficiary CNIC Required', 'Please enter beneficiary CNIC first.');
                return;
            }

            const cnicDigits = beneficiaryCnic.replace(/\D/g, '');
            if (cnicDigits.length !== 13) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'Invalid CNIC', 'Please enter a valid beneficiary CNIC (13 digits).');
                return;
            }

            fetchFamilyMembersForEdit(beneficiaryCnic, function() {
                fetchBtn.prop('disabled', false).html(originalHtml);
            });
        });

        // Family Member Node Click Handler
        $(document).off('click', `#${prefix}familyTreeGrid .family-tree-node`).on('click', `#${prefix}familyTreeGrid .family-tree-node`, function() {
            $(`#${prefix}familyTreeGrid .family-tree-node`).removeClass('selected');
            $(this).addClass('selected');

            const familyMember = $(this).data('family-member');
            if (!familyMember) {
                return;
            }

            $(`#${prefix}rep_cnic`).val(familyMember.cnic);
            $(`#${prefix}rep_full_name`).val(familyMember.full_name);
            $(`#${prefix}rep_father_husband_name`).val(familyMember.father_husband_name);
            $(`#${prefix}rep_mobile_number`).val(familyMember.mobile_number || '');
            $(`#${prefix}rep_date_of_birth`).val(familyMember.date_of_birth || '');

            const genderMap = { 'Male': 'male', 'Female': 'female', 'Other': 'other' };
            const genderValue = genderMap[familyMember.gender] || (familyMember.gender ? familyMember.gender.toLowerCase() : '');
            $(`#${prefix}rep_gender`).val(genderValue).trigger('change');

            $(`#${prefix}rep_relationship`).val(familyMember.relationship || '');

            if ($(`#${prefix}rep_cnic`).val()) {
                $(`#${prefix}rep_cnic`).val(formatCNIC($(`#${prefix}rep_cnic`).val()));
            }
            if ($(`#${prefix}rep_mobile_number`).val()) {
                $(`#${prefix}rep_mobile_number`).val(formatMobileNumber($(`#${prefix}rep_mobile_number`).val()));
            }

            showCustomToast('success', 'Family Member Selected', 'Representative details have been auto-filled from the selected family member.');
        });

        // Copy API data to form field
        $(document).off('click', '#beneficiaryForm .api-copy-btn').on('click', '#beneficiaryForm .api-copy-btn', function() {
            const targetId = $(this).data('target');
            const isSelect = $(this).data('is-select');
            const apiValue = $(this).closest('.api-data-container').data('original-value');

            if (apiValue) {
                if (isSelect) {
                    $(`#${targetId}`).val(apiValue.toLowerCase()).trigger('change');
                } else {
                    $(`#${targetId}`).val(apiValue);
                }
                showCustomToast('success', 'Copied!', 'Data copied to form field.');

                if (targetId === `${prefix}date_of_birth`) {
                    checkAgeAndRequireRepresentative(apiValue, prefix);
                }
            }
        });

        // Trigger checkAgeAndRequireRepresentative on manual DOB change
        $(`#${prefix}date_of_birth`).on('change', function() {
            checkAgeAndRequireRepresentative($(this).val(), prefix);
        });

        // Load scheme categories when scheme is selected
        $('#scheme_id').change(function() {
            const schemeId = $(this).val();
            const phaseId = $('#phase_id').val();
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
                            $('#amount').prop('readonly', true);
                            $('#amountHint').text('Amount will be auto-filled from selected category').show();
                            data.forEach(function(category) {
                                const selected = category.id == {{ $beneficiary->scheme_category_id ?? 0 }} ? 'selected' : '';
                                categorySelect.append(`<option value="${category.id}" data-amount="${category.amount}" ${selected}>${category.name} (Rs. ${category.amount})</option>`);
                            });
                        } else {
                            // No categories - this is a lump sum scheme
                            $('#schemeCategoryDiv').hide();
                            $('#amount').prop('readonly', false);
                            $('#amount').prop('required', true);
                            
                            // Fetch remaining amount for lump sum scheme
                            if (phaseId) {
                                fetchLumpSumRemainingAmountForEdit(phaseId, schemeId, {{ $beneficiary->id }});
                            } else {
                                $('#amountHint').text('Enter the amount for this beneficiary').show();
                            }
                        }
                    },
                    error: function() {
                        $('#schemeCategoryDiv').hide();
                        $('#amount').prop('readonly', false);
                        $('#amount').prop('required', true);
                        
                        // On error, treat as lump sum
                        if (phaseId) {
                            fetchLumpSumRemainingAmountForEdit(phaseId, schemeId, {{ $beneficiary->id }});
                        } else {
                            $('#amountHint').text('Enter the amount for this beneficiary').show();
                        }
                    }
                });
            } else {
                $('#schemeCategoryDiv').hide();
                $('#amountHint').hide();
            }
        });
        
        // Function to fetch remaining amount for lump sum schemes in edit page
        function fetchLumpSumRemainingAmountForEdit(phaseId, schemeId, beneficiaryId) {
            $.ajax({
                url: '{{ route("beneficiaries.scheme-remaining-amount") }}',
                type: 'GET',
                data: {
                    phase_id: phaseId,
                    scheme_id: schemeId,
                    beneficiary_id: beneficiaryId
                },
                success: function(response) {
                    if (response.success) {
                        const remainingAmount = parseFloat(response.remaining_amount) || 0;
                        const formattedRemaining = remainingAmount.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        
                        $('#amountHint').html(`Enter the amount for this beneficiary. <strong>Remaining Amount: Rs. ${formattedRemaining}</strong>`).show();
                        
                        // Set max attribute to prevent entering more than remaining
                        $('#amount').attr('max', remainingAmount);
                        
                        // Update hint dynamically as user types
                        $('#amount').off('input.remainingAmountEdit').on('input.remainingAmountEdit', function() {
                            const enteredAmount = parseFloat($(this).val()) || 0;
                            const newRemaining = remainingAmount - enteredAmount;
                            const formattedNewRemaining = newRemaining.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            
                            if (newRemaining < 0) {
                                $('#amountHint').html(`Enter the amount for this beneficiary. <strong class="text-danger">Remaining Amount: Rs. ${formattedNewRemaining}</strong> (Exceeds limit!)`).show();
                            } else {
                                $('#amountHint').html(`Enter the amount for this beneficiary. <strong>Remaining Amount: Rs. ${formattedNewRemaining}</strong>`).show();
                            }
                        });
                    } else {
                        $('#amountHint').text('Enter the amount for this beneficiary').show();
                    }
                },
                error: function() {
                    $('#amountHint').text('Enter the amount for this beneficiary').show();
                }
            });
        }
        
        // Trigger change on page load if scheme is already selected
        if ($('#scheme_id').val()) {
            $('#scheme_id').trigger('change');
        }
        
        // Auto-set amount when category is selected
        $('#scheme_category_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const amount = selectedOption.data('amount');
            if (amount) {
                $('#amount').val(amount);
                $('#amount').prop('readonly', true);
                $('#amountHint').text('Amount will be auto-filled from selected category').show();
            } else {
                $('#amount').val('');
                $('#amountHint').hide();
            }
        });
        
        // Show/hide representative section
        $('#requires_representative').change(function() {
            if ($(this).is(':checked')) {
                $(`#${prefix}representativeDiv`).show();
                $(`#${prefix}rep_cnic`).prop('required', true);
                $(`#${prefix}rep_full_name`).prop('required', true);
                $(`#${prefix}rep_father_husband_name`).prop('required', true);
                $(`#${prefix}rep_date_of_birth`).prop('required', true);
                $(`#${prefix}rep_gender`).prop('required', true);
                $(`#${prefix}rep_relationship`).prop('required', true);
            } else {
                $(`#${prefix}representativeDiv`).hide();
                $(`#${prefix}rep_cnic`).prop('required', false);
                $(`#${prefix}rep_full_name`).prop('required', false);
                $(`#${prefix}rep_father_husband_name`).prop('required', false);
                $(`#${prefix}rep_date_of_birth`).prop('required', false);
                $(`#${prefix}rep_gender`).prop('required', false);
                $(`#${prefix}rep_relationship`).prop('required', false);
            }
        });
        
        // Check age and auto-require representative if needed
        $(`#${prefix}date_of_birth`).on('change', function() {
            const dob = new Date($(this).val());
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            if (age < 18) {
                $('#requires_representative').prop('checked', true).trigger('change');
            }
        });
        
        // Trigger age check on page load
        if ($(`#${prefix}date_of_birth`).val()) {
            $(`#${prefix}date_of_birth`).trigger('change');
        }
        
        // Convert form submission to AJAX to use updateAjax with all validations
        $('#beneficiaryForm').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const formData = new FormData(this);
            const data = {};
            
            // Convert FormData to object
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('representative[')) {
                    const repKey = key.replace('representative[', '').replace(']', '');
                    if (!data.representative) data.representative = {};
                    data.representative[repKey] = value;
                } else if (key !== '_token' && key !== '_method') {
                    data[key] = value;
                }
            }
            
            // Calculate age to determine if representative is required
            const dob = $(`#${prefix}date_of_birth`).val();
            let requiresRepresentative = 0;
            if (dob) {
                const birthDate = new Date(dob);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                requiresRepresentative = age < 18 ? 1 : 0;
            }
            data.requires_representative = requiresRepresentative;
            
            // If age >= 18, remove representative data
            if (requiresRepresentative === 0) {
                delete data.representative;
            }
            
            // Show loading state
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="ti-reload"></i> Updating...');
            
            // Submit via AJAX
            $.ajax({
                url: '{{ route("beneficiaries.update-ajax", $beneficiary) }}',
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showCustomToast('success', 'Success', response.message || 'Beneficiary updated successfully.');
                        setTimeout(function() {
                            window.location.href = '{{ route("beneficiaries.index") }}';
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    const response = xhr.responseJSON;
                    
                    // Check if it's a duplicate CNIC error
                    if (response && response.error_type === 'duplicate_cnic' && response.duplicate_details) {
                        const details = response.duplicate_details;
                        
                        // Show detailed duplicate CNIC modal
                        Swal.fire({
                            icon: 'error',
                            title: 'Duplicate CNIC Entry',
                            html: `
                                <div style="text-align: left; padding: 10px 0;">
                                    <p style="margin-bottom: 15px; font-weight: 600; color: #dc3545;">
                                        ${response.message || 'This CNIC cannot be registered again in the same financial year.'}
                                    </p>
                                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                                        <p style="margin: 5px 0;"><strong>Reason:</strong></p>
                                        <p style="margin: 5px 0; color: #6c757d;">
                                            A beneficiary can only be registered in <strong>one scheme per financial year</strong>. 
                                            This CNIC has already been registered in another scheme.
                                        </p>
                                    </div>
                                    <div style="border-top: 1px solid #dee2e6; padding-top: 15px; margin-top: 15px;">
                                        <p style="margin: 8px 0; font-weight: 600; color: #495057;">Existing Registration Details:</p>
                                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d; width: 40%;">CNIC:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.cnic || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">Name:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.full_name || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">Scheme:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.scheme_name || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">Phase:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.phase_name || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">District:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.district_name || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">Committee:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.committee_name || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">Status:</td>
                                                <td style="padding: 5px 0;">
                                                    <span style="padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: 500; 
                                                        background-color: ${details.status === 'Approved' ? '#d4edda' : details.status === 'Submitted' ? '#fff3cd' : '#f8d7da'}; 
                                                        color: ${details.status === 'Approved' ? '#155724' : details.status === 'Submitted' ? '#856404' : '#721c24'};">
                                                        ${details.status || 'N/A'}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">Financial Year:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.financial_year || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #6c757d;">Registered Date:</td>
                                                <td style="padding: 5px 0; font-weight: 500;">${details.registered_date || 'N/A'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            `,
                            width: '600px',
                            confirmButtonText: 'I Understand',
                            confirmButtonColor: '#dc3545',
                            allowOutsideClick: false,
                            allowEscapeKey: true
                        });
                    } else {
                        // Handle other errors
                        let errorMessage = 'An error occurred.';
                        if (response && response.message) {
                            errorMessage = response.message;
                        } else if (response && response.errors) {
                            const errors = Object.values(response.errors).flat();
                            errorMessage = errors.join('<br>');
                        }
                        showCustomToast('error', 'Error', errorMessage);
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection

