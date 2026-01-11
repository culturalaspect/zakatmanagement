<?php $__env->startSection('title', config('app.name') . ' - Edit Phase'); ?>
<?php $__env->startSection('page_title', 'Edit Phase'); ?>
<?php $__env->startSection('breadcrumb', 'Edit Phase'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Phase</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="<?php echo e(route('phases.update', $phase)); ?>" method="POST" id="phaseForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <!-- Installment and District Selection -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Installment <span class="text-danger">*</span></label>
                            <select name="installment_id" id="installment_id" class="form-control" required>
                                <option value="">Select Installment</option>
                                <?php $__currentLoopData = $installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($installment->id); ?>" <?php echo e(old('installment_id', $phase->installment_id) == $installment->id ? 'selected' : ''); ?>

                                        data-financial-year="<?php echo e($installment->fundAllocation->financialYear->name ?? ''); ?>">
                                        Installment <?php echo e($installment->installment_number); ?> - <?php echo e($installment->fundAllocation->financialYear->name ?? 'N/A'); ?> 
                                        (Rs. <?php echo e(number_format($installment->installment_amount, 2)); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['installment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">District <span class="text-danger">*</span></label>
                            <select name="district_id" id="district_id" class="form-control" required>
                                <option value="">Select Installment first</option>
                            </select>
                            <?php $__errorArgs = ['district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <!-- Scheme Selection -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Scheme <span class="text-danger">*</span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control" required>
                                <option value="">Select District first</option>
                            </select>
                            <?php $__errorArgs = ['scheme_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- District Quota Information (loaded via AJAX) -->
                    <div id="quotaInfo" class="row mb-3" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2">District Quota Information</h6>
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
                            <input type="number" name="phase_number" id="phase_number" class="form-control" value="<?php echo e(old('phase_number', $phase->phase_number)); ?>" min="1" required>
                            <small class="text-muted">Next suggested: <span id="next_phase_number" class="text-primary">-</span></small>
                            <?php $__errorArgs = ['phase_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phase Name <span class="text-muted">(Auto-generated, editable)</span></label>
                            <input type="text" name="name" id="phase_name" class="form-control" value="<?php echo e(old('name', $phase->name)); ?>" placeholder="Will be auto-generated">
                            <small class="text-muted">Auto-generated based on Financial Year, Installment, District, Scheme, and Phase Number. You can edit if needed.</small>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Max Beneficiaries <span class="text-danger">*</span></label>
                            <input type="number" name="max_beneficiaries" id="max_beneficiaries" class="form-control" value="<?php echo e(old('max_beneficiaries', $phase->max_beneficiaries)); ?>" min="1" required>
                            <?php $__errorArgs = ['max_beneficiaries'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Remaining: <span id="remaining_beneficiaries_hint" class="text-success">-</span></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="<?php echo e(old('start_date', $phase->start_date ? $phase->start_date->format('Y-m-d') : '')); ?>" required>
                            <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?php echo e(old('end_date', $phase->end_date ? $phase->end_date->format('Y-m-d') : '')); ?>">
                            <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="open" <?php echo e(old('status', $phase->status) == 'open' ? 'selected' : ''); ?>>Open</option>
                                <option value="closed" <?php echo e(old('status', $phase->status) == 'closed' ? 'selected' : ''); ?>>Closed</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Status will automatically be set to "Closed" if end date is in the past. To reopen, extend the end date and change status to "Open".</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?php echo e(route('phases.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        let quotaData = null;
        const currentPhaseId = <?php echo e($phase->id); ?>;
        const currentMaxBeneficiaries = <?php echo e($phase->max_beneficiaries); ?>;
        const currentPhaseSchemeId = <?php echo e($phase->scheme_id ?? 0); ?>;

        // Load districts for selected installment
        function loadDistrictsForInstallment(selectDistrictId = null) {
            const installmentId = $('#installment_id').val();
            
            if (installmentId) {
                $.ajax({
                    url: '<?php echo e(route("phases.districts-for-installment")); ?>',
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
                                const selected = (selectDistrictId && district.id == selectDistrictId) ? 'selected' : '';
                                districtSelect.append(`<option value="${district.id}" ${selected}>${district.name}</option>`);
                            });
                        } else {
                            districtSelect.append('<option value="">No districts available</option>');
                        }
                        
                        // If district was pre-selected, trigger loadQuotaInfo with current scheme
                        const districtToSelect = selectDistrictId || $('#district_id').val();
                        if (districtToSelect) {
                            $('#district_id').val(districtToSelect);
                            // Pass the current phase's scheme ID to preserve selection
                            loadQuotaInfo(currentPhaseSchemeId);
                        } else {
                            // Reset scheme selection
                            $('#scheme_id').empty().append('<option value="">Select District first</option>');
                            $('#quotaInfo').hide();
                            quotaData = null;
                        }
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

        // Load quota information and schemes when both installment and district are selected
        function loadQuotaInfo(selectSchemeId = null) {
            const installmentId = $('#installment_id').val();
            const districtId = $('#district_id').val();

            if (installmentId && districtId) {
                $.ajax({
                    url: '<?php echo e(route("phases.district-quota")); ?>',
                    method: 'GET',
                    data: {
                        installment_id: installmentId,
                        district_id: districtId
                    },
                    success: function(response) {
                        quotaData = response;
                        displayQuotaInfo(response);
                        
                        // Store currently selected scheme before repopulating
                        // Priority: selectSchemeId parameter > current phase scheme ID > existing selection
                        let currentSchemeId = selectSchemeId;
                        if (!currentSchemeId && typeof currentPhaseSchemeId !== 'undefined' && currentPhaseSchemeId) {
                            currentSchemeId = currentPhaseSchemeId;
                        }
                        if (!currentSchemeId) {
                            currentSchemeId = $('#scheme_id').val();
                        }
                        
                        // Populate schemes dropdown
                        const schemeSelect = $('#scheme_id');
                        schemeSelect.empty();
                        schemeSelect.append('<option value="">Select Scheme</option>');
                        
                        if (response.schemes && response.schemes.length > 0) {
                            response.schemes.forEach(function(scheme) {
                                // Calculate amount per beneficiary
                                const amountPerBeneficiary = scheme.beneficiaries_count > 0 ? (scheme.amount / scheme.beneficiaries_count) : 0;
                                const selected = (currentSchemeId && scheme.id == currentSchemeId) ? 'selected' : '';
                                schemeSelect.append(`<option value="${scheme.id}" data-name="${scheme.name}" data-amount="${scheme.amount}" data-beneficiaries="${scheme.beneficiaries_count}" data-amount-per-beneficiary="${amountPerBeneficiary}" ${selected}>${scheme.name} (${scheme.percentage}%)</option>`);
                            });
                        }
                        
                        // Select scheme if provided or preserve current selection
                        if (currentSchemeId) {
                            $('#scheme_id').val(currentSchemeId);
                        }
                        
                        // Show next phase number hint
                        if (response.next_phase_number !== null && response.next_phase_number !== undefined) {
                            $('#next_phase_number').text(response.next_phase_number);
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
            $('#total_beneficiaries').text(numberFormat(data.total_beneficiaries));
            $('#total_amount').text(numberFormat(data.total_amount, 2));
            $('#used_beneficiaries').text(numberFormat(data.used_beneficiaries));
            $('#used_amount').text(numberFormat(data.used_amount, 2));
            
            // For edit, we need to add back the current phase's values to show remaining
            const currentMaxBeneficiaries = parseInt($('#max_beneficiaries').val()) || 0;
            
            // Calculate current max amount based on scheme
            let currentMaxAmount = 0;
            const schemeSelect = $('#scheme_id option:selected');
            if (schemeSelect.val() && currentMaxBeneficiaries > 0) {
                let amountPerBeneficiary = parseFloat(schemeSelect.data('amount-per-beneficiary')) || 0;
                if (amountPerBeneficiary === 0 || isNaN(amountPerBeneficiary)) {
                    const schemeAmount = parseFloat(schemeSelect.data('amount')) || 0;
                    const schemeBeneficiaries = parseInt(schemeSelect.data('beneficiaries')) || 0;
                    if (schemeBeneficiaries > 0 && schemeAmount > 0) {
                        amountPerBeneficiary = schemeAmount / schemeBeneficiaries;
                    }
                }
                if (amountPerBeneficiary > 0) {
                    currentMaxAmount = amountPerBeneficiary * currentMaxBeneficiaries;
                }
            }
            
            const remainingBeneficiaries = data.remaining_beneficiaries + currentMaxBeneficiaries;
            const remainingAmount = data.remaining_amount + currentMaxAmount;
            
            $('#remaining_beneficiaries').text(numberFormat(remainingBeneficiaries));
            $('#remaining_amount').text(numberFormat(remainingAmount, 2));
            $('#remaining_beneficiaries_hint').text(numberFormat(remainingBeneficiaries));
            $('#remaining_amount_hint').text(numberFormat(remainingAmount, 2));
            $('#quotaInfo').show();
        }

        function numberFormat(num, decimals = 0) {
            if (decimals > 0) {
                return parseFloat(num).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            return parseFloat(num).toLocaleString();
        }

        // Store original phase name to detect user edits
        let userEditedPhaseName = false;
        let lastGeneratedName = '<?php echo e(old('name', $phase->name)); ?>';

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

        // Function to update remaining hints
        function updateRemainingHints() {
            if (quotaData) {
                const maxBeneficiaries = parseInt($('#max_beneficiaries').val()) || 0;
                
                // Calculate max amount based on scheme and beneficiaries
                let maxAmount = 0;
                const schemeSelect = $('#scheme_id option:selected');
                if (schemeSelect.val() && maxBeneficiaries > 0) {
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
                
                // For edit, we need to account for the current phase's values
                const remainingBeneficiaries = quotaData.remaining_beneficiaries + currentMaxBeneficiaries - maxBeneficiaries;
                
                // Calculate current phase's max amount for comparison
                let currentMaxAmount = 0;
                if (schemeSelect.val() && currentMaxBeneficiaries > 0) {
                    let amountPerBeneficiary = parseFloat(schemeSelect.data('amount-per-beneficiary')) || 0;
                    if (amountPerBeneficiary === 0 || isNaN(amountPerBeneficiary)) {
                        const schemeAmount = parseFloat(schemeSelect.data('amount')) || 0;
                        const schemeBeneficiaries = parseInt(schemeSelect.data('beneficiaries')) || 0;
                        if (schemeBeneficiaries > 0 && schemeAmount > 0) {
                            amountPerBeneficiary = schemeAmount / schemeBeneficiaries;
                        }
                    }
                    if (amountPerBeneficiary > 0) {
                        currentMaxAmount = amountPerBeneficiary * currentMaxBeneficiaries;
                    }
                }
                
                const remainingAmount = quotaData.remaining_amount + currentMaxAmount - maxAmount;

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
            
            // Reload quota info with scheme-specific data
            if ($('#installment_id').val() && $('#district_id').val()) {
                loadQuotaInfo($(this).val());
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

        // Automatically set status to "Closed" if end_date is in the past
        $('#end_date').on('change', function() {
            const endDate = $(this).val();
            if (endDate) {
                const today = new Date().toISOString().split('T')[0];
                if (endDate < today) {
                    $('#status').val('closed');
                } else {
                    // If date is extended to future, allow user to change status to "Open"
                    // Don't auto-change to open, let user decide
                }
            }
        });

        // Check end_date on page load
        $(document).ready(function() {
            const endDate = $('#end_date').val();
            if (endDate) {
                const today = new Date().toISOString().split('T')[0];
                if (endDate < today && $('#status').val() == 'open') {
                    $('#status').val('closed');
                }
            }
        });

        // Load districts and quota info on page load
        if ($('#installment_id').val()) {
            const currentDistrictId = <?php echo e($phase->district_id); ?>;
            
            // Load districts with current district pre-selected
            // The loadDistrictsForInstallment will automatically call loadQuotaInfo with currentPhaseSchemeId
            loadDistrictsForInstallment(currentDistrictId);
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/phases/edit.blade.php ENDPATH**/ ?>