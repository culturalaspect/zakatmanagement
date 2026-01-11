@extends('layouts.app')

@section('title', config('app.name') . ' - Edit Installment')
@section('page_title', 'Edit Installment')
@section('breadcrumb', 'Edit Installment')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Installment #{{ $installment->installment_number }}</h3>
                        <p class="text-muted mb-0">Financial Year: {{ $installment->fundAllocation->financialYear->name }} | Total Amount: Rs. {{ number_format($installment->fundAllocation->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('installments.update', $installment) }}" method="POST" id="installmentForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Installment Number <span class="text-danger">*</span></label>
                            <input type="number" name="installment_number" class="form-control" value="{{ old('installment_number', $installment->installment_number) }}" min="1" required>
                            @error('installment_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Installment Amount <span class="text-danger">*</span></label>
                            <input type="number" name="installment_amount" id="installment_amount" class="form-control" value="{{ old('installment_amount', $installment->installment_amount) }}" step="0.01" min="0" max="{{ $remainingAmount }}" required>
                            <small class="text-muted">
                                Allocated Fund: Rs. {{ number_format($installment->fundAllocation->total_amount, 2) }}
                                @if($totalExistingInstallments > 0)
                                    | Already Allocated: Rs. {{ number_format($totalExistingInstallments, 2) }}
                                @endif
                                | Remaining: Rs. {{ number_format($remainingAmount, 2) }}
                            </small>
                            <div id="installment_amount_error" class="text-danger small mt-1" style="display: none;"></div>
                            @error('installment_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Release Date <span class="text-danger">*</span></label>
                            <input type="date" name="release_date" class="form-control" value="{{ old('release_date', $installment->release_date->format('Y-m-d')) }}" required>
                            @error('release_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- District Quotas -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Disbursement Plan - District Quotas</h5>
                            <div class="alert alert-info mb-3" id="districtQuotaPreview">
                                <strong>District Quota Summary:</strong>
                                <div>Total Districts: <span id="totalDistrictsCount">0</span> <small class="text-muted">(At least 1 required)</small></div>
                                <div>Total Percentage: <span id="totalDistrictsPercentage">0</span>% | Remaining: <span id="remainingDistrictsPercentage">100</span>% <small class="text-muted">(Must equal 100%)</small></div>
                                <div>Total Amount: <span id="totalDistrictsAmount">Rs. 0.00</span> | Remaining: <span id="remainingDistrictsAmount">Rs. 0.00</span> <small class="text-muted">(Must equal installment amount)</small></div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="districtQuotasContainer">
                        <!-- District quotas will be added here dynamically -->
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-sm btn-secondary" id="addDistrictQuota">
                                <i class="ti-plus"></i> Add District Quota
                            </button>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Installment</button>
                            <a href="{{ route('installments.show', $installment) }}" class="btn btn-secondary">Cancel</a>
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
        const districts = @json($districts);
        const schemes = @json($schemes);
        const allocatedFund = parseFloat({{ $installment->fundAllocation->total_amount }}) || 0;
        const totalExistingInstallments = parseFloat({{ $totalExistingInstallments }}) || 0;
        const remainingAmount = parseFloat({{ $remainingAmount }}) || 0;
        const oldInput = @json($oldInput ?? []);
        const existingInstallment = @json($existingInstallmentData);
        
        // Initialize variables BEFORE calling repopulate functions
        let districtQuotaIndex = 0;
        let usedDistricts = []; // Track which districts are already used (as numbers)
        let usedSchemes = {}; // Track which schemes are used per district quota {quotaIndex: [schemeIds]}
        let schemeDistributionIndex = {};
        
        // Repopulate form with old input if validation failed, otherwise use existing installment data
        if (oldInput && Object.keys(oldInput).length > 0) {
            repopulateFormFromOldInput(oldInput);
        } else if (existingInstallment && existingInstallment.district_quotas) {
            repopulateFormFromExistingData(existingInstallment);
        }
        
        // Function to get available districts (excluding already used ones)
        function getAvailableDistricts(excludeQuotaIndex = null) {
            return districts.filter(d => {
                // Convert to number for consistent comparison
                const districtId = parseInt(d.id);
                // If this is the current quota being updated, allow its selected district
                if (excludeQuotaIndex !== null) {
                    const currentSelect = $(`.district-select[data-quota-index="${excludeQuotaIndex}"]`);
                    if (currentSelect.length && currentSelect.val() == districtId) {
                        return true; // Allow the currently selected district
                    }
                }
                return !usedDistricts.includes(districtId);
            });
        }
        
        // Function to get available schemes for a district quota (excluding already used ones)
        function getAvailableSchemes(quotaIndex, excludeDistributionIndex = null) {
            const usedSchemeIds = usedSchemes[quotaIndex] || [];
            return schemes.filter(s => {
                // Convert to number for consistent comparison
                const schemeId = parseInt(s.id);
                // If this is the current scheme being updated, allow it
                if (excludeDistributionIndex !== null) {
                    const currentSelect = $(`.scheme-select[data-quota-index="${quotaIndex}"][data-distribution-index="${excludeDistributionIndex}"]`);
                    if (currentSelect.length && currentSelect.val() == schemeId) {
                        return true; // Allow the currently selected scheme
                    }
                }
                return !usedSchemeIds.includes(schemeId);
            });
        }
        
        // Function to repopulate form from existing installment data
        function repopulateFormFromExistingData(data) {
            console.log('Repopulating from existing data:', data);
            
            if (!data || !data.district_quotas) {
                console.error('No data or district_quotas found');
                return;
            }
            
            // Repopulate basic fields
            if (data.installment_number) {
                $('input[name="installment_number"]').val(data.installment_number);
            }
            if (data.installment_amount) {
                $('input[name="installment_amount"]').val(data.installment_amount);
            }
            if (data.release_date) {
                $('input[name="release_date"]').val(data.release_date);
            }
            
            // Clear existing dynamic rows before repopulating
            $('#districtQuotasContainer').empty();
            usedDistricts = [];
            usedSchemes = {};
            districtQuotaIndex = 0;
            schemeDistributionIndex = {};
            
            if (data.district_quotas && Array.isArray(data.district_quotas) && data.district_quotas.length > 0) {
                console.log('Found ' + data.district_quotas.length + ' district quotas');
                data.district_quotas.forEach(function(quotaData) {
                    // Add district quota row
                    const currentQuotaIndex = districtQuotaIndex;
                    const availableDistricts = getAvailableDistricts(null);
                    const districtOptions = availableDistricts.map(d => 
                        `<option value="${d.id}" data-population="${d.population}" ${parseInt(d.id) === parseInt(quotaData.district_id) ? 'selected' : ''}>${d.name}</option>`
                    ).join('');

                    const quotaHtml = `
                        <div class="card mb-3 district-quota-row" data-quota-index="${currentQuotaIndex}">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">District Quota #${currentQuotaIndex + 1}</h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-district-quota">
                                        <i class="ti-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">District <span class="text-danger">*</span></label>
                                        <select name="district_quotas[${currentQuotaIndex}][district_id]" class="form-control district-select" data-quota-index="${currentQuotaIndex}" required data-previous-value="${quotaData.district_id}">
                                            <option value="">Select District</option>
                                            ${districts.map(d => `<option value="${d.id}" data-population="${d.population}" ${parseInt(d.id) === parseInt(quotaData.district_id) ? 'selected' : ''}>${d.name}</option>`).join('')}
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                        <input type="number" name="district_quotas[${currentQuotaIndex}][percentage]" class="form-control percentage-input" data-quota-index="${currentQuotaIndex}" placeholder="%" step="0.01" min="0" max="100" value="${quotaData.percentage || ''}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Total Beneficiaries <span class="text-danger">*</span></label>
                                        <input type="number" name="district_quotas[${currentQuotaIndex}][total_beneficiaries]" class="form-control beneficiaries-input" data-quota-index="${currentQuotaIndex}" step="0.1" min="0" value="${quotaData.total_beneficiaries || ''}" required readonly>
                                        <small class="text-muted">Auto-calculated</small>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Total Amount Share</label>
                                        <input type="text" class="form-control district-amount-display" data-quota-index="${currentQuotaIndex}" value="Rs. ${parseFloat(quotaData.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}" readonly>
                                        <small class="text-muted">Auto-calculated</small>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3 scheme-preview" data-quota-index="${currentQuotaIndex}" style="display: none;">
                                    <strong>Scheme Summary:</strong>
                                    <div>Total Schemes: <span class="totalSchemesCount" data-quota-index="${currentQuotaIndex}">0</span></div>
                                    <div>Total Percentage: <span class="totalSchemesPercentage" data-quota-index="${currentQuotaIndex}">0</span>% | Remaining: <span class="remainingSchemesPercentage" data-quota-index="${currentQuotaIndex}">100</span>%</div>
                                    <div>Total Beneficiaries: <span class="totalSchemesBeneficiaries" data-quota-index="${currentQuotaIndex}">0</span> | Remaining: <span class="remainingSchemesBeneficiaries" data-quota-index="${currentQuotaIndex}">0</span></div>
                                    <div>Total Amount: <span class="totalSchemesAmount" data-quota-index="${currentQuotaIndex}">Rs. 0.00</span> | Remaining: <span class="remainingSchemesAmount" data-quota-index="${currentQuotaIndex}">Rs. 0.00</span></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h6>Scheme Distributions</h6>
                                    </div>
                                </div>
                                
                                <div class="scheme-distributions-container" data-quota-index="${currentQuotaIndex}">
                                    <!-- Scheme distributions will be added here -->
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-sm btn-info add-scheme-distribution" data-quota-index="${currentQuotaIndex}">
                                            <i class="ti-plus"></i> Add Scheme
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#districtQuotasContainer').append(quotaHtml);
                    districtQuotaIndex++;

                    // Add selected district to used list
                    if (quotaData.district_id) {
                        usedDistricts.push(parseInt(quotaData.district_id));
                    }
                    
                    // Set district population for calculations
                    const selectedDistrict = districts.find(d => parseInt(d.id) === parseInt(quotaData.district_id));
                    if (selectedDistrict) {
                        $(`.district-quota-row[data-quota-index="${currentQuotaIndex}"]`).data('population', parseFloat(selectedDistrict.population) || 0);
                    }
                    
                    // Repopulate schemes for this district quota
                    if (quotaData.scheme_distributions && Array.isArray(quotaData.scheme_distributions)) {
                        usedSchemes[currentQuotaIndex] = [];
                        schemeDistributionIndex[currentQuotaIndex] = 0;
                        quotaData.scheme_distributions.forEach(function(schemeData) {
                            const currentDistributionIndex = schemeDistributionIndex[currentQuotaIndex];
                            const availableSchemes = getAvailableSchemes(currentQuotaIndex, null);
                            const schemeOptions = availableSchemes.map(s => 
                                `<option value="${s.id}" data-percentage="${s.percentage}" ${parseInt(s.id) === parseInt(schemeData.scheme_id) ? 'selected' : ''}>${s.name} (${s.percentage}%)</option>`
                            ).join('');

                            const schemeHtml = `
                                <div class="row mb-2 scheme-distribution-row" data-distribution-index="${currentDistributionIndex}">
                                    <div class="col-md-4">
                                        <label class="form-label small">Scheme</label>
                                        <select name="district_quotas[${currentQuotaIndex}][scheme_distributions][${currentDistributionIndex}][scheme_id]" class="form-control scheme-select" data-quota-index="${currentQuotaIndex}" data-distribution-index="${currentDistributionIndex}" required data-previous-value="${schemeData.scheme_id}">
                                            <option value="">Select Scheme</option>
                                            ${schemes.map(s => `<option value="${s.id}" data-percentage="${s.percentage}" ${parseInt(s.id) === parseInt(schemeData.scheme_id) ? 'selected' : ''}>${s.name} (${s.percentage}%)</option>`).join('')}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">Percentage</label>
                                        <input type="number" name="district_quotas[${currentQuotaIndex}][scheme_distributions][${currentDistributionIndex}][percentage]" class="form-control scheme-percentage-input" data-quota-index="${currentQuotaIndex}" data-distribution-index="${currentDistributionIndex}" step="0.01" min="0" max="100" value="${schemeData.percentage || ''}" required readonly>
                                        <small class="text-muted">Auto-filled</small>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">Beneficiaries</label>
                                        <input type="number" name="district_quotas[${currentQuotaIndex}][scheme_distributions][${currentDistributionIndex}][beneficiaries_count]" class="form-control scheme-beneficiaries-display" data-quota-index="${currentQuotaIndex}" data-distribution-index="${currentDistributionIndex}" step="0.1" min="0" value="${schemeData.beneficiaries_count || ''}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Amount Share</label>
                                        <input type="text" class="form-control scheme-amount-display" data-quota-index="${currentQuotaIndex}" data-distribution-index="${currentDistributionIndex}" value="Rs. ${parseFloat(schemeData.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label small">&nbsp;</label>
                                        <button type="button" class="btn btn-sm btn-danger remove-scheme-distribution w-100">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            $(`.scheme-distributions-container[data-quota-index="${currentQuotaIndex}"]`).append(schemeHtml);
                            schemeDistributionIndex[currentQuotaIndex]++;

                            // Add selected scheme to used list for this quota
                            if (schemeData.scheme_id) {
                                usedSchemes[currentQuotaIndex].push(parseInt(schemeData.scheme_id));
                            }
                        });
                    }
                });
            } else {
                console.log('No district quotas found in data');
            }
            
            // After repopulating, update all selects and previews
            setTimeout(function() {
                updateDistrictSelects();
                $('.district-quota-row').each(function() {
                    const quotaIndex = parseInt($(this).data('quota-index'));
                    updateSchemeSelects(quotaIndex);
                    recalculateSchemeDistributions(quotaIndex);
                    updateSchemePreview(quotaIndex);
                });
                updateDistrictQuotaPreview();
            }, 100);
        }
        
        $('#addDistrictQuota').click(function() {
            // Check if installment amount is entered
            const installmentAmount = parseFloat($('input[name="installment_amount"]').val()) || 0;
            if (installmentAmount <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter the Installment Amount before adding district quotas.',
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('input[name="installment_amount"]').focus();
                });
                return;
            }
            
            const availableDistricts = getAvailableDistricts();
            
            if (availableDistricts.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Districts Available',
                    text: 'All districts have been added. Please remove a district quota to add a different one.',
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            const quotaHtml = `
                <div class="card mb-3 district-quota-row" data-quota-index="${districtQuotaIndex}">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">District Quota #${districtQuotaIndex + 1}</h6>
                            <button type="button" class="btn btn-sm btn-danger remove-district-quota">
                                <i class="ti-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select name="district_quotas[${districtQuotaIndex}][district_id]" class="form-control district-select" data-quota-index="${districtQuotaIndex}" required>
                                    <option value="">Select District</option>
                                    ${availableDistricts.map(d => `<option value="${d.id}" data-population="${d.population}">${d.name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input type="number" name="district_quotas[${districtQuotaIndex}][percentage]" class="form-control percentage-input" data-quota-index="${districtQuotaIndex}" placeholder="%" step="0.01" min="0" max="100" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Total Beneficiaries <span class="text-danger">*</span></label>
                                <input type="number" name="district_quotas[${districtQuotaIndex}][total_beneficiaries]" class="form-control beneficiaries-input" data-quota-index="${districtQuotaIndex}" step="0.1" min="0" required readonly>
                                <small class="text-muted">Auto-calculated</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Total Amount Share</label>
                                <input type="text" class="form-control district-amount-display" data-quota-index="${districtQuotaIndex}" readonly>
                                <small class="text-muted">Auto-calculated</small>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3 scheme-preview" data-quota-index="${districtQuotaIndex}" style="display: none;">
                            <strong>Scheme Summary:</strong>
                            <div>Total Schemes: <span class="totalSchemesCount" data-quota-index="${districtQuotaIndex}">0</span></div>
                            <div>Total Percentage: <span class="totalSchemesPercentage" data-quota-index="${districtQuotaIndex}">0</span>% | Remaining: <span class="remainingSchemesPercentage" data-quota-index="${districtQuotaIndex}">100</span>%</div>
                            <div>Total Beneficiaries: <span class="totalSchemesBeneficiaries" data-quota-index="${districtQuotaIndex}">0</span> | Remaining: <span class="remainingSchemesBeneficiaries" data-quota-index="${districtQuotaIndex}">0</span></div>
                            <div>Total Amount: <span class="totalSchemesAmount" data-quota-index="${districtQuotaIndex}">Rs. 0.00</span> | Remaining: <span class="remainingSchemesAmount" data-quota-index="${districtQuotaIndex}">Rs. 0.00</span></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <h6>Scheme Distributions</h6>
                            </div>
                        </div>
                        
                        <div class="scheme-distributions-container" data-quota-index="${districtQuotaIndex}">
                            <!-- Scheme distributions will be added here -->
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-info add-scheme-distribution" data-quota-index="${districtQuotaIndex}">
                                    <i class="ti-plus"></i> Add Scheme
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#districtQuotasContainer').append(quotaHtml);
            usedSchemes[districtQuotaIndex] = []; // Initialize schemes array for this quota
            districtQuotaIndex++;
            
            // Initialize the data attribute for tracking previous value
            const newSelect = $(`.district-select[data-quota-index="${districtQuotaIndex - 1}"]`);
            newSelect.data('previous-value', '');
        });
        
        $(document).on('click', '.remove-district-quota', function() {
            const quotaRow = $(this).closest('.district-quota-row');
            const quotaIndex = parseInt(quotaRow.data('quota-index'));
            const selectedDistrictId = quotaRow.find('.district-select').val();
            
            // Remove district from used list (convert to number for consistency)
            if (selectedDistrictId) {
                const districtIdNum = parseInt(selectedDistrictId);
                usedDistricts = usedDistricts.filter(id => parseInt(id) !== districtIdNum);
            }
            
            // Remove schemes for this quota
            delete usedSchemes[quotaIndex];
            
            // Remove the row
            quotaRow.remove();
            
            // Update district quota numbers
            updateDistrictQuotaNumbers();
            
            // Update all district selects to include the removed district
            updateDistrictSelects();
            
            // Update district quota preview
            updateDistrictQuotaPreview();
        });
        
        // Track district selection and update other selects
        $(document).on('change', '.district-select', function() {
            const quotaIndex = parseInt($(this).data('quota-index'));
            const selectedDistrictId = $(this).val();
            const previousDistrictId = $(this).data('previous-value');
            
            // Remove previous district from used list if it was set (convert to number)
            if (previousDistrictId) {
                const prevIdNum = parseInt(previousDistrictId);
                usedDistricts = usedDistricts.filter(id => parseInt(id) !== prevIdNum);
            }
            
            // Add new district to used list (convert to number for consistency)
            if (selectedDistrictId) {
                const selectedIdNum = parseInt(selectedDistrictId);
                if (!usedDistricts.includes(selectedIdNum)) {
                    usedDistricts.push(selectedIdNum);
                }
            }
            
            // Store current value for next change
            $(this).data('previous-value', selectedDistrictId);
            
            // Update all other district selects
            updateDistrictSelects();
            
            // Continue with existing calculation logic
            const selectedOption = $(this).find('option:selected');
            const population = parseFloat(selectedOption.data('population')) || 0;
            $(this).closest('.district-quota-row').data('population', population);
            
            const percentage = parseFloat($(`.percentage-input[data-quota-index="${quotaIndex}"]`).val()) || 0;
            const installmentAmount = parseFloat($('input[name="installment_amount"]').val()) || 0;
            
            if (percentage > 0 && population > 0) {
                const totalBeneficiaries = (percentage / 100) * population;
                $(`.beneficiaries-input[data-quota-index="${quotaIndex}"]`).val(totalBeneficiaries.toFixed(1));
                
                if (installmentAmount > 0) {
                    const districtAmount = (installmentAmount * percentage) / 100;
                    $(`.district-amount-display[data-quota-index="${quotaIndex}"]`).val('Rs. ' + districtAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                }
                
                recalculateSchemeDistributions(quotaIndex);
            }
        });
        
        // Function to update all district selects to exclude used districts
        function updateDistrictSelects() {
            $('.district-select').each(function() {
                const currentQuotaIndex = parseInt($(this).data('quota-index'));
                const currentSelectedId = $(this).val();
                const availableDistricts = getAvailableDistricts(currentQuotaIndex);
                
                // Rebuild options
                $(this).html('<option value="">Select District</option>');
                availableDistricts.forEach(d => {
                    const selected = parseInt(d.id) == parseInt(currentSelectedId) ? 'selected' : '';
                    $(this).append(`<option value="${d.id}" data-population="${d.population}" ${selected}>${d.name}</option>`);
                });
            });
        }
        
        $(document).on('click', '.add-scheme-distribution', function() {
            const quotaIndex = parseInt($(this).data('quota-index'));
            
            // Check if district percentage is entered
            const districtPercentage = parseFloat($(`.percentage-input[data-quota-index="${quotaIndex}"]`).val()) || 0;
            if (districtPercentage <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter the District Percentage before adding schemes.',
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $(`.percentage-input[data-quota-index="${quotaIndex}"]`).focus();
                });
                return;
            }
            
            if (!schemeDistributionIndex[quotaIndex]) {
                schemeDistributionIndex[quotaIndex] = 0;
            }
            
            const availableSchemes = getAvailableSchemes(quotaIndex);
            
            if (availableSchemes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Schemes Available',
                    text: 'All schemes have been added for this district. Please remove a scheme to add a different one.',
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            const schemeHtml = `
                <div class="row mb-2 scheme-distribution-row" data-distribution-index="${schemeDistributionIndex[quotaIndex]}">
                    <div class="col-md-4">
                        <label class="form-label small">Scheme</label>
                        <select name="district_quotas[${quotaIndex}][scheme_distributions][${schemeDistributionIndex[quotaIndex]}][scheme_id]" class="form-control scheme-select" data-quota-index="${quotaIndex}" data-distribution-index="${schemeDistributionIndex[quotaIndex]}" required>
                            <option value="">Select Scheme</option>
                            ${availableSchemes.map(s => `<option value="${s.id}" data-percentage="${s.percentage}">${s.name} (${s.percentage}%)</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Percentage</label>
                        <input type="number" name="district_quotas[${quotaIndex}][scheme_distributions][${schemeDistributionIndex[quotaIndex]}][percentage]" class="form-control scheme-percentage-input" data-quota-index="${quotaIndex}" data-distribution-index="${schemeDistributionIndex[quotaIndex]}" step="0.01" min="0" max="100" required readonly>
                        <small class="text-muted">Auto-filled</small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Beneficiaries</label>
                        <input type="number" name="district_quotas[${quotaIndex}][scheme_distributions][${schemeDistributionIndex[quotaIndex]}][beneficiaries_count]" class="form-control scheme-beneficiaries-display" data-quota-index="${quotaIndex}" data-distribution-index="${schemeDistributionIndex[quotaIndex]}" step="0.1" min="0" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Amount Share</label>
                        <input type="text" class="form-control scheme-amount-display" data-quota-index="${quotaIndex}" data-distribution-index="${schemeDistributionIndex[quotaIndex]}" readonly>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small">&nbsp;</label>
                        <button type="button" class="btn btn-sm btn-danger remove-scheme-distribution w-100">
                            <i class="ti-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            // Fix: Find the container using the data attribute
            $(`.scheme-distributions-container[data-quota-index="${quotaIndex}"]`).append(schemeHtml);
            schemeDistributionIndex[quotaIndex]++;
            
            // Update scheme preview
            updateSchemePreview(quotaIndex);
        });
        
        $(document).on('click', '.remove-scheme-distribution', function() {
            const schemeRow = $(this).closest('.scheme-distribution-row');
            const quotaIndex = parseInt(schemeRow.find('.scheme-select').data('quota-index'));
            const selectedSchemeId = schemeRow.find('.scheme-select').val();
            
            // Remove scheme from used list (convert to number for consistency)
            if (selectedSchemeId && usedSchemes[quotaIndex]) {
                const schemeIdNum = parseInt(selectedSchemeId);
                usedSchemes[quotaIndex] = usedSchemes[quotaIndex].filter(id => parseInt(id) !== schemeIdNum);
            }
            
            // Remove the row
            schemeRow.remove();
            
            // Update scheme selects for this quota
            updateSchemeSelects(quotaIndex);
            
            // Update scheme preview
            updateSchemePreview(quotaIndex);
        });
        
        // Track scheme selection and update other scheme selects for the same quota
        $(document).on('change', '.scheme-select', function() {
            const quotaIndex = parseInt($(this).data('quota-index'));
            const distributionIndex = parseInt($(this).data('distribution-index'));
            const selectedSchemeId = $(this).val();
            const previousSchemeId = $(this).data('previous-value');
            
            // Remove previous scheme from used list if it was set (convert to number)
            if (previousSchemeId && usedSchemes[quotaIndex]) {
                const prevIdNum = parseInt(previousSchemeId);
                usedSchemes[quotaIndex] = usedSchemes[quotaIndex].filter(id => parseInt(id) !== prevIdNum);
            }
            
            // Add new scheme to used list (convert to number for consistency)
            if (selectedSchemeId) {
                if (!usedSchemes[quotaIndex]) {
                    usedSchemes[quotaIndex] = [];
                }
                const selectedIdNum = parseInt(selectedSchemeId);
                if (!usedSchemes[quotaIndex].includes(selectedIdNum)) {
                    usedSchemes[quotaIndex].push(selectedIdNum);
                }
                
                // Auto-fill percentage from selected scheme
                const selectedOption = $(this).find('option:selected');
                const schemePercentage = parseFloat(selectedOption.data('percentage')) || 0;
                $(this).closest('.scheme-distribution-row').find('.scheme-percentage-input').val(schemePercentage);
            } else {
                // Clear percentage if no scheme selected
                $(this).closest('.scheme-distribution-row').find('.scheme-percentage-input').val('');
            }
            
            // Store current value for next change
            $(this).data('previous-value', selectedSchemeId);
            
            // Update all scheme selects for this quota
            updateSchemeSelects(quotaIndex);
            
            // Recalculate beneficiaries and amount for this scheme
            recalculateSchemeDistributions(quotaIndex);
            
            // Update scheme preview
            updateSchemePreview(quotaIndex);
        });
        
        // Function to update all scheme selects for a quota to exclude used schemes
        function updateSchemeSelects(quotaIndex) {
            const quotaRow = $(`.district-quota-row[data-quota-index="${quotaIndex}"]`);
            
            quotaRow.find('.scheme-select').each(function() {
                const currentSelectedId = $(this).val();
                const distributionIndex = parseInt($(this).data('distribution-index'));
                const availableSchemes = getAvailableSchemes(quotaIndex, distributionIndex);
                
                // Rebuild options
                $(this).html('<option value="">Select Scheme</option>');
                availableSchemes.forEach(s => {
                    const selected = parseInt(s.id) == parseInt(currentSelectedId) ? 'selected' : '';
                    $(this).append(`<option value="${s.id}" data-percentage="${s.percentage}" ${selected}>${s.name} (${s.percentage}%)</option>`);
                });
                
                // If a scheme is selected, update its percentage
                if (currentSelectedId) {
                    const selectedScheme = schemes.find(s => parseInt(s.id) == parseInt(currentSelectedId));
                    if (selectedScheme) {
                        $(this).closest('.scheme-distribution-row').find('.scheme-percentage-input').val(selectedScheme.percentage);
                    }
                }
            });
        }
        
        function updateDistrictQuotaNumbers() {
            $('.district-quota-row').each(function(index) {
                $(this).find('.card-header h6').text(`District Quota #${index + 1}`);
            });
        }
        
        
        $(document).on('input', '.percentage-input', function() {
            const quotaIndex = $(this).data('quota-index');
            let percentage = parseFloat($(this).val()) || 0;
            const districtRow = $(this).closest('.district-quota-row');
            const population = districtRow.data('population') || 0;
            const installmentAmount = parseFloat($('input[name="installment_amount"]').val()) || 0;
            
            // Validate district percentage doesn't exceed remaining
            const totalDistrictsPercentage = calculateTotalDistrictsPercentage();
            if (totalDistrictsPercentage > 100) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: `Total district percentages (${totalDistrictsPercentage.toFixed(2)}%) cannot exceed 100%. Please reduce the percentage.`,
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                });
                // Reset to 0
                $(this).val('');
                percentage = 0;
            } else if (totalDistrictsPercentage < 100 && $('.district-quota-row').length > 0) {
                // Show warning if not equal to 100% but don't block
                // The preview will show this
            }
            
            if (percentage > 0 && population > 0) {
                const totalBeneficiaries = (percentage / 100) * population;
                $(`.beneficiaries-input[data-quota-index="${quotaIndex}"]`).val(totalBeneficiaries.toFixed(1));
                
                // Calculate and display district total amount
                if (installmentAmount > 0) {
                    const districtAmount = (installmentAmount * percentage) / 100;
                    $(`.district-amount-display[data-quota-index="${quotaIndex}"]`).val('Rs. ' + districtAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                }
                
                // Recalculate scheme distributions for this district
                recalculateSchemeDistributions(quotaIndex);
            } else {
                $(`.beneficiaries-input[data-quota-index="${quotaIndex}"]`).val('');
                $(`.district-amount-display[data-quota-index="${quotaIndex}"]`).val('');
            }
            
            // Update district quota preview
            updateDistrictQuotaPreview();
        });
        
        // Calculate district amount when installment amount changes
        $(document).on('input', 'input[name="installment_amount"]', function() {
            const installmentAmount = parseFloat($(this).val()) || 0;
            
            // Validate installment amount doesn't exceed remaining amount
            if (installmentAmount > remainingAmount) {
                $('#installment_amount_error').text(`Installment amount (Rs. ${installmentAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}) cannot exceed remaining amount (Rs. ${remainingAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}). Total allocated: Rs. ${allocatedFund.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}, Already allocated: Rs. ${totalExistingInstallments.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`).show();
            } else {
                $('#installment_amount_error').hide();
            }
            
            $('.percentage-input').each(function() {
                const quotaIndex = $(this).data('quota-index');
                const percentage = parseFloat($(this).val()) || 0;
                if (percentage > 0 && installmentAmount > 0) {
                    const districtAmount = (installmentAmount * percentage) / 100;
                    $(`.district-amount-display[data-quota-index="${quotaIndex}"]`).val('Rs. ' + districtAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    recalculateSchemeDistributions(quotaIndex);
                }
            });
            
            // Update district quota preview
            updateDistrictQuotaPreview();
        });
        
        // Calculate scheme beneficiaries and amount when scheme percentage changes
        $(document).on('input', '.scheme-percentage-input', function() {
            const quotaIndex = $(this).data('quota-index');
            let schemePercentage = parseFloat($(this).val()) || 0;
            
            // Validate scheme percentage doesn't exceed remaining for this district
            const totalSchemePercentage = calculateTotalSchemePercentage(quotaIndex);
            if (totalSchemePercentage > 100) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: `Total scheme percentages for this district (${totalSchemePercentage.toFixed(2)}%) cannot exceed 100%. Please reduce the percentage.`,
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                });
                // Reset to 0
                $(this).val('');
                schemePercentage = 0;
            } else if (totalSchemePercentage < 100) {
                // Show warning if not equal to 100% but don't block
                // The preview will show this
            }
            
            recalculateSchemeDistributions(quotaIndex);
            
            // Update scheme preview for this district
            updateSchemePreview(quotaIndex);
        });
        
        function recalculateSchemeDistributions(quotaIndex) {
            // Find the district quota row using data attribute
            const districtRow = $(`.district-quota-row[data-quota-index="${quotaIndex}"]`);
            
            const districtBeneficiaries = parseFloat($(`.beneficiaries-input[data-quota-index="${quotaIndex}"]`).val()) || 0;
            
            // Get district total amount share from the display field (this is the district's share, not the total installment)
            const districtAmountDisplay = $(`.district-amount-display[data-quota-index="${quotaIndex}"]`).val();
            // Extract numeric value from "Rs. 1,234.56" format
            const districtAmount = districtAmountDisplay ? parseFloat(districtAmountDisplay.replace('Rs. ', '').replace(/,/g, '')) || 0 : 0;
            
            let totalSchemeBeneficiaries = 0;
            let totalSchemeAmount = 0;
            
            // Recalculate each scheme distribution in this district
            districtRow.find('.scheme-distribution-row').each(function() {
                const schemePercentage = parseFloat($(this).find('.scheme-percentage-input').val()) || 0;
                
                if (schemePercentage > 0 && districtBeneficiaries > 0 && districtAmount > 0) {
                    // Calculate scheme beneficiaries based on district total beneficiaries
                    const schemeBeneficiaries = (schemePercentage / 100) * districtBeneficiaries;
                    $(this).find('.scheme-beneficiaries-display').val(schemeBeneficiaries.toFixed(1));
                    totalSchemeBeneficiaries += schemeBeneficiaries;
                    
                    // Calculate scheme amount based on district total amount share (not the total installment amount)
                    const schemeAmount = (districtAmount * schemePercentage) / 100;
                    $(this).find('.scheme-amount-display').val('Rs. ' + schemeAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    totalSchemeAmount += schemeAmount;
                } else {
                    $(this).find('.scheme-beneficiaries-display').val('');
                    $(this).find('.scheme-amount-display').val('');
                }
            });
            
            // Validate scheme totals don't exceed district share (with tolerance for floating point precision)
            const beneficiariesTolerance = Math.max(0.1, districtBeneficiaries * 0.0001); // 0.01% tolerance or minimum 0.1
            const amountTolerance = Math.max(0.01, districtAmount * 0.0001); // 0.01% tolerance or minimum 0.01
            
            if ((totalSchemeBeneficiaries - districtBeneficiaries) > beneficiariesTolerance || 
                (totalSchemeAmount - districtAmount) > amountTolerance) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: `Scheme totals exceed district share. Beneficiaries: ${totalSchemeBeneficiaries.toFixed(1)} > ${districtBeneficiaries.toFixed(1)}, Amount: Rs. ${totalSchemeAmount.toFixed(2)} > Rs. ${districtAmount.toFixed(2)}`,
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                });
            }
            
            // Update scheme preview
            updateSchemePreview(quotaIndex);
        }
        
        // Calculate total districts percentage
        function calculateTotalDistrictsPercentage() {
            let total = 0;
            $('.percentage-input').each(function() {
                const percentage = parseFloat($(this).val()) || 0;
                total += percentage;
            });
            return total;
        }
        
        // Calculate total scheme percentage for a district
        function calculateTotalSchemePercentage(quotaIndex) {
            let total = 0;
            $(`.scheme-percentage-input[data-quota-index="${quotaIndex}"]`).each(function() {
                const percentage = parseFloat($(this).val()) || 0;
                total += percentage;
            });
            return total;
        }
        
        // Update district quota preview
        function updateDistrictQuotaPreview() {
            const totalDistricts = $('.district-quota-row').length;
            const totalPercentage = calculateTotalDistrictsPercentage();
            const remainingPercentage = 100 - totalPercentage;
            const installmentAmount = parseFloat($('input[name="installment_amount"]').val()) || 0;
            const totalDistrictsAmount = (installmentAmount * totalPercentage) / 100;
            const remainingAmount = installmentAmount - totalDistrictsAmount;
            
            $('#totalDistrictsCount').text(totalDistricts);
            $('#totalDistrictsPercentage').text(totalPercentage.toFixed(2));
            $('#remainingDistrictsPercentage').text(remainingPercentage.toFixed(2));
            $('#totalDistrictsAmount').text('Rs. ' + totalDistrictsAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#remainingDistrictsAmount').text('Rs. ' + remainingAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            
            // Change color based on validation
            if (totalPercentage > 100 || totalDistrictsAmount > installmentAmount) {
                $('#districtQuotaPreview').removeClass('alert-info alert-warning').addClass('alert-danger');
            } else if (Math.abs(totalPercentage - 100) > 0.01) { // Not equal to 100% (with small tolerance for floating point)
                $('#districtQuotaPreview').removeClass('alert-info alert-danger').addClass('alert-warning');
            } else {
                $('#districtQuotaPreview').removeClass('alert-danger alert-warning').addClass('alert-info');
            }
        }
        
        // Update scheme preview for a district
        function updateSchemePreview(quotaIndex) {
            const districtRow = $(`.district-quota-row[data-quota-index="${quotaIndex}"]`);
            const schemeCount = districtRow.find('.scheme-distribution-row').length;
            const totalPercentage = calculateTotalSchemePercentage(quotaIndex);
            const remainingPercentage = 100 - totalPercentage;
            
            let totalBeneficiaries = 0;
            let totalAmount = 0;
            
            districtRow.find('.scheme-distribution-row').each(function() {
                const beneficiaries = parseFloat($(this).find('.scheme-beneficiaries-display').val()) || 0;
                const amountDisplay = $(this).find('.scheme-amount-display').val();
                const amount = amountDisplay ? parseFloat(amountDisplay.replace('Rs. ', '').replace(/,/g, '')) || 0 : 0;
                totalBeneficiaries += beneficiaries;
                totalAmount += amount;
            });
            
            const districtBeneficiaries = parseFloat($(`.beneficiaries-input[data-quota-index="${quotaIndex}"]`).val()) || 0;
            const districtAmountDisplay = $(`.district-amount-display[data-quota-index="${quotaIndex}"]`).val();
            const districtAmount = districtAmountDisplay ? parseFloat(districtAmountDisplay.replace('Rs. ', '').replace(/,/g, '')) || 0 : 0;
            
            $(`.totalSchemesCount[data-quota-index="${quotaIndex}"]`).text(schemeCount);
            $(`.totalSchemesPercentage[data-quota-index="${quotaIndex}"]`).text(totalPercentage.toFixed(2));
            $(`.remainingSchemesPercentage[data-quota-index="${quotaIndex}"]`).text(remainingPercentage.toFixed(2));
            $(`.totalSchemesBeneficiaries[data-quota-index="${quotaIndex}"]`).text(totalBeneficiaries.toFixed(1));
            $(`.totalSchemesAmount[data-quota-index="${quotaIndex}"]`).text('Rs. ' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $(`.remainingSchemesBeneficiaries[data-quota-index="${quotaIndex}"]`).text((districtBeneficiaries - totalBeneficiaries).toFixed(1));
            $(`.remainingSchemesAmount[data-quota-index="${quotaIndex}"]`).text('Rs. ' + (districtAmount - totalAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            
            // Show/hide preview based on whether schemes exist
            if (schemeCount > 0) {
                $(`.scheme-preview[data-quota-index="${quotaIndex}"]`).show();
                
                // Change color based on validation
                const preview = $(`.scheme-preview[data-quota-index="${quotaIndex}"]`);
                if (totalPercentage > 100 || totalBeneficiaries > districtBeneficiaries || totalAmount > districtAmount) {
                    preview.removeClass('alert-warning alert-info').addClass('alert-danger');
                } else if (Math.abs(totalPercentage - 100) > 0.01) { // Not equal to 100% (with small tolerance)
                    preview.removeClass('alert-danger alert-info').addClass('alert-warning');
                } else {
                    preview.removeClass('alert-danger alert-warning').addClass('alert-info');
                }
            } else {
                $(`.scheme-preview[data-quota-index="${quotaIndex}"]`).hide();
            }
        }
        
        // Validate form before submission
        $('#installmentForm').on('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            let errorMessages = [];
            
            // Check if installment amount exceeds remaining amount
            const installmentAmount = parseFloat($('input[name="installment_amount"]').val()) || 0;
            if (installmentAmount > remainingAmount) {
                isValid = false;
                errorMessages.push(`Installment amount (Rs. ${installmentAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}) cannot exceed remaining amount (Rs. ${remainingAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}). Total allocated: Rs. ${allocatedFund.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}, Already allocated: Rs. ${totalExistingInstallments.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}.`);
            }
            
            // Check if at least one district is added
            const districtCount = $('.district-quota-row').length;
            if (districtCount === 0) {
                isValid = false;
                errorMessages.push('At least one district quota must be added.');
            }
            
            // Check if district percentages equal 100%
            const totalDistrictsPercentage = calculateTotalDistrictsPercentage();
            if (districtCount > 0 && Math.abs(totalDistrictsPercentage - 100) > 0.01) {
                isValid = false;
                errorMessages.push(`District percentages must equal exactly 100% (currently ${totalDistrictsPercentage.toFixed(2)}%).`);
            }
            
            // Check if district amounts equal installment amount
            const totalDistrictsAmount = (installmentAmount * totalDistrictsPercentage) / 100;
            if (districtCount > 0 && Math.abs(totalDistrictsAmount - installmentAmount) > 0.01) {
                isValid = false;
                errorMessages.push(`District total amount (Rs. ${totalDistrictsAmount.toFixed(2)}) must equal installment amount (Rs. ${installmentAmount.toFixed(2)}).`);
            }
            
            // Check each district has at least one scheme
            $('.district-quota-row').each(function() {
                const quotaIndex = $(this).data('quota-index');
                const schemeCount = $(this).find('.scheme-distribution-row').length;
                const districtName = $(this).find('.district-select option:selected').text() || `District Quota #${quotaIndex + 1}`;
                
                if (schemeCount === 0) {
                    isValid = false;
                    errorMessages.push(`${districtName} must have at least one scheme added.`);
                }
            });
            
            // Check each district's scheme percentages equal 100%
            $('.district-quota-row').each(function() {
                const quotaIndex = $(this).data('quota-index');
                const schemeCount = $(this).find('.scheme-distribution-row').length;
                const totalSchemePercentage = calculateTotalSchemePercentage(quotaIndex);
                const districtName = $(this).find('.district-select option:selected').text() || `District Quota #${quotaIndex + 1}`;
                
                if (schemeCount > 0 && Math.abs(totalSchemePercentage - 100) > 0.01) {
                    isValid = false;
                    errorMessages.push(`${districtName} scheme percentages must equal exactly 100% (currently ${totalSchemePercentage.toFixed(2)}%).`);
                }
            });
            
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: '<ul style="text-align: left;"><li>' + errorMessages.join('</li><li>') + '</li></ul>',
                    confirmButtonColor: '#567AED',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            // If all validations pass, submit the form
            this.submit();
        });
        
        // Function to repopulate form from old input after validation failure
        function repopulateFormFromOldInput(oldInput) {
            // Repopulate basic fields
            if (oldInput.installment_number) {
                $('input[name="installment_number"]').val(oldInput.installment_number);
            }
            if (oldInput.installment_amount) {
                $('input[name="installment_amount"]').val(oldInput.installment_amount);
            }
            if (oldInput.release_date) {
                $('input[name="release_date"]').val(oldInput.release_date);
            }
            
            // Repopulate district quotas
            if (oldInput.district_quotas && Array.isArray(oldInput.district_quotas)) {
                oldInput.district_quotas.forEach(function(quotaData, index) {
                    // Add district quota
                    $('#addDistrictQuota').click();
                    
                    // Wait for the row to be added
                    setTimeout(function() {
                        const quotaRow = $('.district-quota-row').eq(index);
                        const quotaIndex = quotaRow.data('quota-index');
                        
                        // Set district
                        if (quotaData.district_id) {
                            quotaRow.find('.district-select').val(quotaData.district_id).trigger('change');
                            
                            // Wait for district to load, then set percentage
                            setTimeout(function() {
                                if (quotaData.percentage) {
                                    quotaRow.find('.percentage-input').val(quotaData.percentage).trigger('input');
                                }
                                
                                // Repopulate schemes
                                if (quotaData.scheme_distributions && Array.isArray(quotaData.scheme_distributions)) {
                                    quotaData.scheme_distributions.forEach(function(schemeData, schemeIndex) {
                                        setTimeout(function() {
                                            quotaRow.find('.add-scheme-distribution').click();
                                            
                                            setTimeout(function() {
                                                const schemeRow = quotaRow.find('.scheme-distribution-row').eq(schemeIndex);
                                                
                                                if (schemeData.scheme_id) {
                                                    schemeRow.find('.scheme-select').val(schemeData.scheme_id).trigger('change');
                                                }
                                            }, 100);
                                        }, schemeIndex * 200);
                                    });
                                }
                            }, 200);
                        }
                    }, index * 300);
                });
            }
        }
    });
</script>
@endpush
@endsection
