<?php $__env->startSection('title', config('app.name') . ' - Add LZC Member'); ?>
<?php $__env->startSection('page_title', 'Add LZC Member'); ?>
<?php $__env->startSection('breadcrumb', 'Add LZC Member'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New LZC Member</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <?php if($selectedCommittee): ?>
                <div class="alert alert-info mb-4" role="alert">
                    <h5 class="alert-heading mb-2">
                        <i class="ti-info-alt"></i> Adding Member to Committee
                    </h5>
                    <p class="mb-0">
                        <strong>Committee:</strong> <?php echo e($selectedCommittee->name); ?><br>
                        <strong>Code:</strong> <?php echo e($selectedCommittee->code ?? 'N/A'); ?><br>
                        <strong>District:</strong> <?php echo e($selectedCommittee->district->name ?? 'N/A'); ?>

                    </p>
                </div>
                <?php endif; ?>
                <form action="<?php echo e(route('lzc-members.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($selectedCommittee): ?>
                        <input type="hidden" name="local_zakat_committee_id" value="<?php echo e($selectedCommittee->id); ?>">
                        <input type="hidden" name="return_to_committee" value="1">
                    <?php endif; ?>
                    
                    <?php if(!$selectedCommittee): ?>
                    <!-- Committee Selection Section -->
                    <div class="card mb-4" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="ti-group"></i> Committee Selection
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Filter by District</label>
                                    <select id="filterDistrict" class="form-control">
                                        <option value="">All Districts</option>
                                        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($district->id); ?>"><?php echo e($district->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Local Zakat Committee <span class="text-danger">*</span></label>
                                    <select name="local_zakat_committee_id" id="local_zakat_committee_id" class="form-control" required>
                                        <option value="">Select Committee</option>
                                        <?php $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($committee->id); ?>" data-district-id="<?php echo e($committee->district_id); ?>" <?php echo e(old('local_zakat_committee_id') == $committee->id ? 'selected' : ''); ?>>
                                            <?php echo e($committee->name); ?> [<?php echo e($committee->code ?? 'N/A'); ?>] - <?php echo e($committee->district->name ?? ''); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['local_zakat_committee_id'];
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
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Member Information Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="ti-user"></i> Member Information
                            </h5>
                            <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CNIC <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="cnic" id="cnic" class="form-control" value="<?php echo e(old('cnic')); ?>" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" required>
                                <button type="button" class="btn btn-primary" id="fetchMemberDetailsBtn" title="Fetch details from Wheat Distribution System">
                                    <i class="ti-search"></i> Fetch Details
                                </button>
                            </div>
                            <small class="text-muted">Format: XXXXX-XXXXXXX-X (15 digits)</small>
                            <?php $__errorArgs = ['cnic'];
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
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo e(old('full_name')); ?>" required>
                                <div id="apiFullName" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="full_name" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <?php $__errorArgs = ['full_name'];
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
                            <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" name="father_husband_name" id="father_husband_name" class="form-control" value="<?php echo e(old('father_husband_name')); ?>" required>
                                <div id="apiFatherHusbandName" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="father_husband_name" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <?php $__errorArgs = ['father_husband_name'];
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
                            <label class="form-label">Mobile Number</label>
                            <div class="position-relative">
                                <input type="text" name="mobile_number" id="mobile_number" class="form-control" value="<?php echo e(old('mobile_number')); ?>" placeholder="03XX-XXXXXXX" maxlength="12" pattern="03[0-9]{2}-[0-9]{7}">
                                <div id="apiMobileNumber" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="mobile_number" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Format: 03XX-XXXXXXX (11 digits)</small>
                            <?php $__errorArgs = ['mobile_number'];
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
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?php echo e(old('date_of_birth')); ?>" required>
                                <div id="apiDateOfBirth" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="date_of_birth" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <?php $__errorArgs = ['date_of_birth'];
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
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>Female</option>
                                    <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                                <div id="apiGender" class="api-data-container" style="display: none;">
                                    <div class="api-data-value"></div>
                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="gender" data-is-select="true" title="Copy to form field">
                                        <i class="ti-check"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <?php $__errorArgs = ['gender'];
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
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" name="designation" id="designation" class="form-control" value="<?php echo e(old('designation')); ?>" placeholder="e.g., Chairman, Secretary, Member" required>
                            <?php $__errorArgs = ['designation'];
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
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="<?php echo e(old('start_date')); ?>" required>
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
                            <input type="date" name="end_date" class="form-control" value="<?php echo e(old('end_date')); ?>">
                            <small class="text-muted">Leave empty if tenure is ongoing</small>
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
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="<?php echo e(route('lzc-members.index')); ?>" class="btn btn-secondary">Cancel</a>
                            <?php if($selectedCommitteeId): ?>
                            <a href="<?php echo e(route('local-zakat-committees.show', $selectedCommitteeId)); ?>" class="btn btn-secondary">Back to Committee</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    
    .custom-toast.progress {
        position: relative;
        overflow: hidden;
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        <?php if(!$selectedCommittee): ?>
        // Initialize Select2 for committee dropdown
        $('#local_zakat_committee_id').select2({
            placeholder: 'Search and select a committee...',
            width: '100%',
            allowClear: true
        });
        
        // Function to rebuild committee options based on district filter
        function rebuildCommitteeOptions() {
            const districtId = $('#filterDistrict').val();
            const currentSelected = $('#local_zakat_committee_id').val() || [];
            
            $('#local_zakat_committee_id').empty();
            $('#local_zakat_committee_id').append('<option value="">Select Committee</option>');
            
            const committees = <?php echo json_encode($committees, 15, 512) ?>;
            committees.forEach(function(committee) {
                let show = true;
                
                if (districtId && committee.district_id != districtId) {
                    show = false;
                }
                
                if (show) {
                    const code = committee.code || 'N/A';
                    const $option = $('<option></option>')
                        .attr('value', committee.id)
                        .text(`${committee.name} [${code}] - ${committee.district ? committee.district.name : ''}`)
                        .data('district-id', committee.district_id);
                    
                    if (currentSelected.includes(committee.id.toString())) {
                        $option.prop('selected', true);
                    }
                    
                    $('#local_zakat_committee_id').append($option);
                }
            });
            
            $('#local_zakat_committee_id').select2('destroy').select2({
                placeholder: 'Search and select a committee...',
                width: '100%',
                allowClear: true
            });
        }
        
        // Filter committees when district changes
        $('#filterDistrict').on('change', function() {
            rebuildCommitteeOptions();
        });
        
        // Initial rebuild
        rebuildCommitteeOptions();
        <?php endif; ?>
        // Function to format CNIC: XXXXX-XXXXXXX-X (13 digits total)
        function formatCNIC(value) {
            // Remove all non-digits
            value = value.replace(/\D/g, '');
            
            // Limit to 13 digits (5 + 7 + 1)
            if (value.length > 13) {
                value = value.substring(0, 13);
            }
            
            // Format: XXXXX-XXXXXXX-X
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
        
        // Format CNIC on input
        $('#cnic').on('input', function(e) {
            let formatted = formatCNIC($(this).val());
            $(this).val(formatted);
        });
        
        // Format existing value on page load
        let existingValue = $('#cnic').val();
        if (existingValue) {
            $('#cnic').val(formatCNIC(existingValue));
        }
        
        // Prevent non-numeric input
        $('#cnic').on('keypress', function(e) {
            // Allow backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        
        // Format on paste
        $('#cnic').on('paste', function(e) {
            setTimeout(function() {
                let formatted = formatCNIC($('#cnic').val());
                $('#cnic').val(formatted);
            }, 10);
        });
        
        // Function to format Mobile Number: 03XX-XXXXXXX (11 digits total)
        function formatMobileNumber(value) {
            // Remove all non-digits
            value = value.replace(/\D/g, '');
            
            // Limit to 11 digits
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Format: 03XX-XXXXXXX
            let formatted = '';
            if (value.length > 0) {
                // Must start with 03
                if (value.length >= 2 && value.substring(0, 2) === '03') {
                    formatted = value.substring(0, 4);
                    if (value.length > 4) {
                        formatted += '-' + value.substring(4, 11);
                    }
                } else {
                    // If doesn't start with 03, format what we have
                    if (value.length <= 4) {
                        formatted = value;
                    } else {
                        formatted = value.substring(0, 4) + '-' + value.substring(4, 11);
                    }
                }
            }
            
            return formatted;
        }
        
        // Format Mobile Number on input
        $('#mobile_number').on('input', function(e) {
            let formatted = formatMobileNumber($(this).val());
            $(this).val(formatted);
        });
        
        // Format existing value on page load
        let existingMobileValue = $('#mobile_number').val();
        if (existingMobileValue) {
            $('#mobile_number').val(formatMobileNumber(existingMobileValue));
        }
        
        // Prevent non-numeric input for mobile
        $('#mobile_number').on('keypress', function(e) {
            // Allow backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        
        // Format on paste for mobile
        $('#mobile_number').on('paste', function(e) {
            setTimeout(function() {
                let formatted = formatMobileNumber($('#mobile_number').val());
                $('#mobile_number').val(formatted);
            }, 10);
        });
        
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
        
        // Fetch Member Details Button Click
        $('#fetchMemberDetailsBtn').on('click', function() {
            const fetchBtn = $(this);
            const originalHtml = fetchBtn.html();
            fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
            
            $('.api-data-container').hide();
            $('.api-data-value').text('');
            
            const cnic = $('#cnic').val().trim();
            
            if (!cnic) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'CNIC Required', 'Please enter a CNIC first.');
                return;
            }
            
            const cnicDigits = cnic.replace(/\D/g, '');
            if (cnicDigits.length !== 13) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'Invalid CNIC', 'Please enter a valid CNIC (13 digits).');
                return;
            }
            
            fetchMemberData(cnic, function() {
                fetchBtn.prop('disabled', false).html(originalHtml);
            });
        });
        
        // Copy API Data Button Click Handler
        $(document).on('click', '.api-copy-btn', function() {
            const copyBtn = $(this);
            const targetId = copyBtn.data('target');
            const isSelect = copyBtn.data('is-select') === true;
            const originalValue = copyBtn.closest('.api-data-container').data('original-value');
            
            if (!targetId || !originalValue) return;
            
            const targetElement = $(`#${targetId}`);
            if (targetElement.length === 0) return;
            
            if (isSelect) {
                const genderMap = {
                    'Male': 'male',
                    'Female': 'female',
                    'Other': 'other'
                };
                const mappedValue = genderMap[originalValue] || originalValue.toLowerCase();
                targetElement.val(mappedValue).trigger('change');
            } else if (targetElement.attr('type') === 'date') {
                targetElement.val(originalValue);
            } else {
                targetElement.val(originalValue);
            }
            
            const originalText = copyBtn.html();
            copyBtn.html('<i class="ti-check"></i> Copied!').addClass('btn-success').removeClass('btn-success');
            setTimeout(function() {
                copyBtn.html(originalText);
            }, 2000);
        });
        
        // Fetch Member Data from API
        function fetchMemberData(cnic, callback) {
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            function displayApiData(data) {
                function showApiData(containerId, value, displayValue = null) {
                    if (value && value !== 'N/A' && value !== null && value !== '') {
                        const container = $(`#${containerId}`);
                        const valueDiv = container.find('.api-data-value');
                        const displayText = displayValue !== null ? displayValue : value;
                        valueDiv.text(displayText);
                        container.data('original-value', value);
                        container.show();
                    }
                }
                
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
                
                showApiData('apiFullName', data.full_name);
                showApiData('apiFatherHusbandName', data.father_husband_name);
                showApiData('apiMobileNumber', data.mobile_number);
                showApiData('apiDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
                showApiData('apiGender', data.gender);
            }
            
            function fetchMemberDataFromAPI(token) {
                $.ajax({
                    url: `${apiBaseUrl}/external/zakat/member/lookup`,
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify({ cnic: cnic }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (callback) callback();
                        if (response.success && response.data) {
                            displayApiData(response.data);
                            showCustomToast('success', 'Member Found', 'Verified member data fetched. Review and copy to form fields.');
                        } else {
                            showCustomToast('info', 'Member Not Found', response.message || 'Member with this CNIC was not found in the verified database. Please enter details manually.');
                        }
                    },
                    error: function(xhr) {
                        if (callback) callback();
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
                    }
                });
            }
            
            if (apiToken) {
                fetchMemberDataFromAPI(apiToken);
            } else {
                if (!apiUsername || !apiPassword) {
                    if (callback) callback();
                    showCustomToast('warning', 'API Configuration Missing', 'Please configure WHEAT_API_USERNAME and WHEAT_API_PASSWORD in your .env file.');
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
                            fetchMemberDataFromAPI(loginResponse.data.access_token);
                        } else {
                            if (callback) callback();
                            showCustomToast('error', 'Authentication Failed', 'Failed to authenticate with Wheat Distribution API. Please verify your API credentials.');
                        }
                    },
                    error: function(loginXhr) {
                        if (callback) callback();
                        let errorMessage = 'Failed to authenticate with Wheat Distribution API.';
                        if (loginXhr.status === 401) {
                            errorMessage = 'Invalid username or password. Please check your API credentials.';
                        } else if (loginXhr.status === 0) {
                            errorMessage = 'Unable to connect to the API server. Please check if the Wheat Distribution application is running.';
                        }
                        showCustomToast('error', 'Authentication Error', errorMessage);
                    }
                });
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/lzc-members/create.blade.php ENDPATH**/ ?>