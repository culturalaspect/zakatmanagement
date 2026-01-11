@extends('layouts.app')

@section('title', config('app.name') . ' - Add Phase')
@section('page_title', 'Add Phase')
@section('breadcrumb', 'Add Phase')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Phase</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('phases.store') }}" method="POST" id="phaseForm">
                    @csrf
                    
                    <!-- Installment and District Selection -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Installment <span class="text-danger">*</span></label>
                            <select name="installment_id" id="installment_id" class="form-control" required>
                                <option value="">Select Installment</option>
                                @foreach($installments as $installment)
                                    <option value="{{ $installment->id }}" {{ old('installment_id') == $installment->id ? 'selected' : '' }}
                                        data-financial-year="{{ $installment->fundAllocation->financialYear->name ?? 'N/A' }}">
                                        Installment {{ $installment->installment_number }} - {{ $installment->fundAllocation->financialYear->name ?? 'N/A' }} 
                                        (Rs. {{ number_format($installment->installment_amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('installment_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">District <span class="text-danger">*</span></label>
                            <select name="district_id" id="district_id" class="form-control" required>
                                <option value="">Select Installment first</option>
                            </select>
                            @error('district_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Scheme Selection -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Scheme <span class="text-danger">*</span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control" required>
                                <option value="">Select District first</option>
                            </select>
                            @error('scheme_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- District Quota Information (loaded via AJAX) -->
                    <div id="quotaInfo" class="row mb-3" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2" id="quotaInfoTitle">District Quota Information</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Total Beneficiaries:</strong> <span id="total_beneficiaries">0</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Total Amount:</strong> Rs. <span id="total_amount">0.00</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Used Beneficiaries:</strong> <span id="used_beneficiaries">0</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Used Amount:</strong> Rs. <span id="used_amount">0.00</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong class="text-success">Remaining Beneficiaries:</strong> <span id="remaining_beneficiaries" class="text-success">0</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong class="text-success">Remaining Amount:</strong> Rs. <span id="remaining_amount" class="text-success">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phase Details -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phase Number <span class="text-danger">*</span></label>
                            <input type="number" name="phase_number" id="phase_number" class="form-control" value="{{ old('phase_number') }}" min="1" required>
                            <small class="text-muted">Next suggested: <span id="next_phase_number" class="text-primary">-</span></small>
                            @error('phase_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phase Name <span class="text-muted">(Auto-generated, editable)</span></label>
                            <input type="text" name="name" id="phase_name" class="form-control" value="{{ old('name') }}" placeholder="Will be auto-generated">
                            <small class="text-muted">Auto-generated based on Financial Year, Installment, District, and Phase Number. You can edit if needed.</small>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Max Beneficiaries <span class="text-danger">*</span></label>
                            <input type="number" name="max_beneficiaries" id="max_beneficiaries" class="form-control" value="{{ old('max_beneficiaries') }}" min="1" required>
                            @error('max_beneficiaries')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Remaining: <span id="remaining_beneficiaries_hint" class="text-success">-</span></small>
                        </div>
                        <div class="col-md-6 mb-3" id="max_amount_div" style="display: none;">
                            <label class="form-label">Max Amount <span class="text-danger">*</span></label>
                            <input type="number" name="max_amount" id="max_amount" class="form-control" value="{{ old('max_amount') }}" min="0" step="0.01">
                            @error('max_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Remaining: <span id="remaining_amount_hint" class="text-success">-</span></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Status will automatically be set to "Closed" if end date is in the past.</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('phases.index') }}" class="btn btn-secondary">Cancel</a>
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
         let quotaData = null;

        // Load quota information when both installment and district are selected
        function loadQuotaInfo() {
            const installmentId = $('#installment_id').val();
            const districtId = $('#district_id').val();
            const schemeId = $('#scheme_id').val();

            if (installmentId && districtId) {
                const requestData = {
                    installment_id: installmentId,
                    district_id: districtId
                };
                
                // Include scheme_id if selected for scheme-specific calculations
                if (schemeId) {
                    requestData.scheme_id = schemeId;
                }
                
                $.ajax({
                    url: '{{ route("phases.district-quota") }}',
                    method: 'GET',
                    data: requestData,
                    success: function(response) {
                        quotaData = response;
                        displayQuotaInfo(response);
                        
                        // Store currently selected scheme before repopulating
                        const currentSchemeId = $('#scheme_id').val();
                        
                        // Populate schemes dropdown
                        const schemeSelect = $('#scheme_id');
                        schemeSelect.empty();
                        schemeSelect.append('<option value="">Select Scheme</option>');
                        
                        if (response.schemes && response.schemes.length > 0) {
                            response.schemes.forEach(function(scheme) {
                                // Calculate amount per beneficiary
                                const amountPerBeneficiary = scheme.beneficiaries_count > 0 ? (scheme.amount / scheme.beneficiaries_count) : 0;
                                const selected = (currentSchemeId && scheme.id == currentSchemeId) ? 'selected' : '';
                                // If scheme has no categories, it's a lump sum scheme
                                const hasCategories = scheme.has_categories !== undefined ? scheme.has_categories : (scheme.categories && scheme.categories.length > 0);
                                const isLumpSum = !hasCategories;
                                schemeSelect.append(`<option value="${scheme.id}" data-name="${scheme.name}" data-amount="${scheme.amount}" data-beneficiaries="${scheme.beneficiaries_count}" data-amount-per-beneficiary="${amountPerBeneficiary}" data-is-lump-sum="${isLumpSum}" data-has-categories="${hasCategories}" ${selected}>${scheme.name} (${scheme.percentage}%)</option>`);
                            });
                            
                            // Check if selected scheme is lump sum and show/hide max_amount field
                            if (currentSchemeId) {
                                const selectedOption = schemeSelect.find(`option[value="${currentSchemeId}"]`);
                                if (selectedOption.length) {
                                    const isLumpSum = selectedOption.data('is-lump-sum') === true || selectedOption.data('is-lump-sum') === 'true';
                                    if (isLumpSum) {
                                        $('#max_amount_div').show();
                                        $('#max_amount').prop('required', true);
                                    } else {
                                        $('#max_amount_div').hide();
                                        $('#max_amount').prop('required', false);
                                        $('#max_amount').val('');
                                    }
                                }
                            }
                        }
                        
                        // Auto-update phase number when scheme is selected/changed
                        if (response.next_phase_number !== null && response.next_phase_number !== undefined) {
                            // Always update phase number when scheme changes to ensure correct numbering
                            $('#phase_number').val(response.next_phase_number);
                            $('#next_phase_number').text(response.next_phase_number);
                            // Regenerate phase name when phase number is updated
                            generatePhaseName();
                        } else {
                            $('#next_phase_number').text('Select scheme first');
                        }
                        
                        // Generate phase name after schemes are populated
                        setTimeout(function() {
                            generatePhaseName();
                        }, 50);
                        
                         // Update remaining hints if beneficiaries are already entered
                         if ($('#max_beneficiaries').val()) {
                             setTimeout(function() {
                                 updateRemainingHints();
                             }, 100);
                         }
                    },
                    error: function(xhr) {
                        $('#quotaInfo').hide();
                        quotaData = null;
                        $('#next_phase_number').text('-');
                        
                        let errorMessage = 'Failed to load quota information.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                            if (xhr.responseJSON.debug) {
                                errorMessage += '\n\nDebug Info:\n';
                                errorMessage += 'Installment ID: ' + xhr.responseJSON.debug.installment_id + '\n';
                                errorMessage += 'District ID: ' + xhr.responseJSON.debug.district_id + '\n';
                                errorMessage += 'Total Quotas for Installment: ' + xhr.responseJSON.debug.total_quotas_for_installment + '\n';
                                if (xhr.responseJSON.debug.available_district_ids && xhr.responseJSON.debug.available_district_ids.length > 0) {
                                    errorMessage += 'Available District IDs: ' + xhr.responseJSON.debug.available_district_ids.join(', ');
                                }
                            }
                        }
                        
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Quota Found',
                            text: errorMessage,
                            width: '600px'
                        });
                    }
                });
            } else {
                $('#quotaInfo').hide();
                quotaData = null;
                $('#next_phase_number').text('-');
            }
        }

        function displayQuotaInfo(data) {
            // Update title based on whether scheme-specific or district-wide
            if (data.scheme_specific && data.scheme_total_beneficiaries !== undefined) {
                const schemeName = $('#scheme_id option:selected').text().split(' (')[0] || 'Selected Scheme';
                $('#quotaInfoTitle').text('Scheme Quota Information: ' + schemeName);
                
                // Show scheme-specific information
                $('#total_beneficiaries').text(numberFormat(data.scheme_total_beneficiaries));
                $('#total_amount').text(numberFormat(data.scheme_total_amount, 2));
            } else {
                $('#quotaInfoTitle').text('District Quota Information');
                
                // Show district totals
                $('#total_beneficiaries').text(numberFormat(data.total_beneficiaries));
                $('#total_amount').text(numberFormat(data.total_amount, 2));
            }
            
            $('#used_beneficiaries').text(numberFormat(data.used_beneficiaries));
            $('#used_amount').text(numberFormat(data.used_amount, 2));
            $('#remaining_beneficiaries').text(numberFormat(data.remaining_beneficiaries));
            $('#remaining_amount').text(numberFormat(data.remaining_amount, 2));
            $('#remaining_beneficiaries_hint').text(numberFormat(data.remaining_beneficiaries));
            $('#remaining_amount_hint').text(numberFormat(data.remaining_amount, 2));
            $('#quotaInfo').show();
        }

        function numberFormat(num, decimals = 0) {
            if (decimals > 0) {
                return parseFloat(num).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            return parseFloat(num).toLocaleString();
        }

        // Load districts for selected installment
        function loadDistrictsForInstallment() {
            const installmentId = $('#installment_id').val();
            
            if (installmentId) {
                $.ajax({
                    url: '{{ route("phases.districts-for-installment") }}',
                    method: 'GET',
                    data: {
                        installment_id: installmentId
                    },
                    success: function(response) {
                        const districtSelect = $('#district_id');
                        districtSelect.empty();
                        districtSelect.append('<option value="">Select District</option>');
                        
                        if (response.districts && response.districts.length > 0) {
                            response.districts.forEach(function(district) {
                                districtSelect.append(`<option value="${district.id}">${district.name}</option>`);
                            });
                        } else {
                            districtSelect.append('<option value="">No districts available</option>');
                        }
                        
                        // Reset scheme selection
                        $('#scheme_id').empty().append('<option value="">Select District first</option>');
                        $('#quotaInfo').hide();
                        quotaData = null;
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load districts for this installment.'
                        });
                    }
                });
            } else {
                $('#district_id').empty().append('<option value="">Select Installment first</option>');
                $('#scheme_id').empty().append('<option value="">Select District first</option>');
                $('#quotaInfo').hide();
                quotaData = null;
            }
        }

        // Store original phase name to detect user edits
        let userEditedPhaseName = false;
        let lastGeneratedName = '';

        // Auto-generate phase name
        function generatePhaseName() {
            const installmentSelect = $('#installment_id option:selected');
            const districtSelect = $('#district_id option:selected');
            const schemeSelect = $('#scheme_id option:selected');
            const phaseNumber = $('#phase_number').val();
            
            if (installmentSelect.val() && districtSelect.val() && phaseNumber) {
                // Get financial year from data attribute
                const financialYear = installmentSelect.data('financial-year') || '';
                
                // Extract installment number from text (format: "Installment X - Financial Year (Rs. ...)")
                const installmentText = installmentSelect.text();
                const installmentMatch = installmentText.match(/Installment\s+(\d+)/);
                const installmentNumber = installmentMatch ? installmentMatch[1] : '';
                
                const districtName = districtSelect.text().trim();
                const schemeName = schemeSelect.data('name') || schemeSelect.text().split(' (')[0] || '';
                
                // Generate name in format: [Financial Year] Installment # [Number] [District] [Scheme] Phase [Phase Number]
                const parts = [];
                if (financialYear) parts.push(financialYear);
                if (installmentNumber) parts.push(`Installment # ${installmentNumber}`);
                if (districtName) parts.push(districtName);
                if (schemeName && schemeSelect.val()) parts.push(schemeName);
                if (phaseNumber) parts.push(`Phase ${phaseNumber}`);
                
                const generatedName = parts.join(' ').trim();
                lastGeneratedName = generatedName;
                
                // Always update if user hasn't manually edited, or if the generated name matches what they have
                if (!userEditedPhaseName || $('#phase_name').val() === lastGeneratedName) {
                    $('#phase_name').val(generatedName);
                    userEditedPhaseName = false;
                }
            }
        }

        // Track if user manually edits the phase name
        $('#phase_name').on('input', function() {
            // If user changes the name from the auto-generated one, mark as edited
            if ($(this).val() !== lastGeneratedName) {
                userEditedPhaseName = true;
            }
        });

        // Event listeners
        $('#installment_id').on('change', function() {
            loadDistrictsForInstallment();
            userEditedPhaseName = false;
            quotaData = null;
        });

        $('#district_id').on('change', function() {
            loadQuotaInfo();
            userEditedPhaseName = false; // Reset edit flag when changing district
        });

        $('#scheme_id').on('change', function() {
            userEditedPhaseName = false; // Reset edit flag when changing scheme
            
            // Check if selected scheme is lump sum
            const selectedOption = $(this).find('option:selected');
            const isLumpSum = selectedOption.data('is-lump-sum') === true || selectedOption.data('is-lump-sum') === 'true';
            
            if (isLumpSum) {
                $('#max_amount_div').show();
                $('#max_amount').prop('required', true);
            } else {
                $('#max_amount_div').hide();
                $('#max_amount').prop('required', false);
                $('#max_amount').val('');
            }
            
            // Reload quota info with scheme-specific data to get updated phase number
            if ($('#installment_id').val() && $('#district_id').val()) {
                loadQuotaInfo();
            } else {
                // Generate phase name immediately when scheme changes
                setTimeout(function() {
                    generatePhaseName();
                }, 50);
                updateRemainingHints();
            }
        });

        $('#phase_number').on('input', function() {
            // Always update phase name when phase number changes
            generatePhaseName();
        });

        // Update remaining hints when max beneficiaries changes
        $('#max_beneficiaries').on('input', function() {
            updateRemainingHints();
        });

        // Update remaining hints when max amount changes (for lump sum schemes)
        $('#max_amount').on('input', function() {
            updateRemainingHints();
        });

        // Automatically set status to "Closed" if end_date is in the past
        $('#end_date').on('change', function() {
            const endDate = $(this).val();
            if (endDate) {
                const today = new Date().toISOString().split('T')[0];
                if (endDate < today) {
                    $('#status').val('closed');
                }
            }
        });

        // Check end_date on page load
        $(document).ready(function() {
            const endDate = $('#end_date').val();
            if (endDate) {
                const today = new Date().toISOString().split('T')[0];
                if (endDate < today) {
                    $('#status').val('closed');
                }
            }
        });

        // Function to update remaining hints
        function updateRemainingHints() {
            if (quotaData) {
                const maxBeneficiaries = parseInt($('#max_beneficiaries').val()) || 0;
                
                // Calculate max amount based on scheme type
                let maxAmount = 0;
                const schemeSelect = $('#scheme_id option:selected');
                const isLumpSum = schemeSelect.data('is-lump-sum') === true || schemeSelect.data('is-lump-sum') === 'true';
                
                if (isLumpSum) {
                    // For lump sum schemes, use the max_amount input value
                    maxAmount = parseFloat($('#max_amount').val()) || 0;
                } else if (schemeSelect.val() && maxBeneficiaries > 0) {
                    // For non-lump sum schemes, calculate based on beneficiaries
                    let amountPerBeneficiary = parseFloat(schemeSelect.data('amount-per-beneficiary')) || 0;
                    if (amountPerBeneficiary === 0 || isNaN(amountPerBeneficiary)) {
                        const schemeAmount = parseFloat(schemeSelect.data('amount')) || 0;
                        const schemeBeneficiaries = parseInt(schemeSelect.data('beneficiaries')) || 0;
                        if (schemeBeneficiaries > 0 && schemeAmount > 0) {
                            amountPerBeneficiary = schemeAmount / schemeBeneficiaries;
                        }
                    }
                    if (amountPerBeneficiary > 0) {
                        maxAmount = amountPerBeneficiary * maxBeneficiaries;
                    }
                }
                
                const remainingBeneficiaries = quotaData.remaining_beneficiaries - maxBeneficiaries;
                const remainingAmount = quotaData.remaining_amount - maxAmount;

                if (remainingBeneficiaries < 0) {
                    $('#remaining_beneficiaries_hint').removeClass('text-success').addClass('text-danger').text(numberFormat(remainingBeneficiaries));
                } else {
                    $('#remaining_beneficiaries_hint').removeClass('text-danger').addClass('text-success').text(numberFormat(remainingBeneficiaries));
                }

                if (remainingAmount < 0) {
                    $('#remaining_amount_hint').removeClass('text-success').addClass('text-danger').text('Rs. ' + numberFormat(remainingAmount, 2));
                } else {
                    $('#remaining_amount_hint').removeClass('text-danger').addClass('text-success').text('Rs. ' + numberFormat(remainingAmount, 2));
                }
            }
        }

        // Load quota info on page load if both fields are pre-filled
        if ($('#installment_id').val() && $('#district_id').val()) {
            loadQuotaInfo();
            generatePhaseName();
        }
        
        // Check scheme on page load if scheme is pre-selected
        if ($('#scheme_id').val()) {
            const selectedOption = $('#scheme_id option:selected');
            const isLumpSum = selectedOption.data('is-lump-sum') === true || selectedOption.data('is-lump-sum') === 'true';
            
            if (isLumpSum) {
                $('#max_amount_div').show();
                $('#max_amount').prop('required', true);
            } else {
                $('#max_amount_div').hide();
                $('#max_amount').prop('required', false);
            }
        }
    });
</script>
@endpush
@endsection
