

<?php $__env->startSection('title', config('app.name') . ' - Verify LZC Member'); ?>
<?php $__env->startSection('page_title', 'Verify LZC Member'); ?>
<?php $__env->startSection('breadcrumb', 'Verify LZC Member'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $genderDisplay = $lZCMember->gender == 'male' ? 'Male' : ($lZCMember->gender == 'female' ? 'Female' : 'Other');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Verify LZC Member</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <!-- Member Details Display -->
                <div class="card mb-4" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Member Details</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="fetchVerifyApiDataBtn" title="Fetch data from Wheat Distribution System">
                                <i class="ti-search"></i> Fetch API Data
                            </button>
                        </div>
                        <p class="text-muted mb-3">Compare member data with API data from Wheat Distribution System. API data will appear below each field when fetched.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>CNIC:</strong></label>
                                <div class="position-relative">
                                    <input type="text" id="verifyCnic" class="form-control" value="<?php echo e($lZCMember->cnic); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                                    <div id="apiVerifyCnic" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Full Name:</strong></label>
                                <div class="position-relative">
                                    <input type="text" id="verifyFullName" class="form-control" value="<?php echo e($lZCMember->full_name); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                                    <div id="apiVerifyFullName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Father/Husband Name:</strong></label>
                                <div class="position-relative">
                                    <input type="text" id="verifyFatherHusbandName" class="form-control" value="<?php echo e($lZCMember->father_husband_name); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                                    <div id="apiVerifyFatherHusbandName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Mobile Number:</strong></label>
                                <div class="position-relative">
                                    <input type="text" id="verifyMobile" class="form-control" value="<?php echo e($lZCMember->mobile_number ?? 'N/A'); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                                    <div id="apiVerifyMobile" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Date of Birth:</strong></label>
                                <div class="position-relative">
                                    <input type="text" id="verifyDateOfBirth" class="form-control" value="<?php echo e($lZCMember->date_of_birth ? \Carbon\Carbon::parse($lZCMember->date_of_birth)->format('d M Y') : 'N/A'); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                                    <div id="apiVerifyDateOfBirth" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Gender:</strong></label>
                                <div class="position-relative">
                                    <input type="text" id="verifyGender" class="form-control" value="<?php echo e($genderDisplay); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                                    <div id="apiVerifyGender" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Start Date:</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($lZCMember->start_date ? \Carbon\Carbon::parse($lZCMember->start_date)->format('d M Y') : 'N/A'); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>End Date:</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($lZCMember->end_date ? \Carbon\Carbon::parse($lZCMember->end_date)->format('d M Y') : 'Ongoing'); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Committee:</strong></label>
                                <input type="text" class="form-control" value="<?php echo e($lZCMember->localZakatCommittee->name ?? 'N/A'); ?>" readonly style="background-color: #fff; border: 1px solid #ced4da;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Verification Status:</strong></label>
                                <div>
                                    <?php if($lZCMember->verification_status == 'verified'): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php elseif($lZCMember->verification_status == 'rejected'): ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="<?php echo e(route('lzc-members.verify.submit', $lZCMember)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Action <span class="text-danger">*</span></label>
                            <select name="action" id="verifyAction" class="form-control" required>
                                <option value="">Select Action</option>
                                <option value="verify" <?php echo e(old('action') == 'verify' ? 'selected' : ''); ?>>Verify & Activate</option>
                                <option value="reject" <?php echo e(old('action') == 'reject' ? 'selected' : ''); ?>>Reject</option>
                            </select>
                            <?php $__errorArgs = ['action'];
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
                        <div class="col-md-12 mb-3" id="verificationRemarksDiv" style="display: none;">
                            <label class="form-label">Verification Remarks <span class="text-danger">*</span></label>
                            <textarea name="verification_remarks" id="verificationRemarks" class="form-control" rows="3" placeholder="Enter remarks for verification"><?php echo e(old('verification_remarks')); ?></textarea>
                            <small class="text-muted">Please provide remarks for verification</small>
                            <?php $__errorArgs = ['verification_remarks'];
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
                        <div class="col-md-12 mb-3" id="rejectionReasonDiv" style="display: none;">
                            <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" id="rejectionReason" class="form-control" rows="3" placeholder="Enter reason for rejection"><?php echo e(old('rejection_reason')); ?></textarea>
                            <small class="text-muted">Please provide a reason for rejection</small>
                            <?php $__errorArgs = ['rejection_reason'];
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
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="<?php echo e(route('lzc-members.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
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
<script>
    $(document).ready(function() {
        $('#verifyAction').on('change', function() {
            const action = $(this).val();
            if (action === 'verify') {
                $('#verificationRemarksDiv').show();
                $('#verificationRemarks').prop('required', true);
                $('#rejectionReasonDiv').hide();
                $('#rejectionReason').prop('required', false);
            } else if (action === 'reject') {
                $('#rejectionReasonDiv').show();
                $('#rejectionReason').prop('required', true);
                $('#verificationRemarksDiv').hide();
                $('#verificationRemarks').prop('required', false);
            } else {
                $('#verificationRemarksDiv').hide();
                $('#verificationRemarks').prop('required', false);
                $('#rejectionReasonDiv').hide();
                $('#rejectionReason').prop('required', false);
            }
        });
        
        if ($('#verifyAction').val()) {
            $('#verifyAction').trigger('change');
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
        
        // Fetch API Data Button Click
        $('#fetchVerifyApiDataBtn').on('click', function() {
            const fetchBtn = $(this);
            const originalHtml = fetchBtn.html();
            fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
            
            $('.api-data-container').hide();
            $('.api-data-value').text('');
            
            const cnic = '<?php echo e($lZCMember->cnic); ?>';
            
            fetchVerifyMemberApiData(cnic, function() {
                fetchBtn.prop('disabled', false).html(originalHtml);
            });
        });
        
        // Fetch API Data for Verify Member Modal
        function fetchVerifyMemberApiData(cnic, callback) {
            if (!cnic || cnic.trim() === '') {
                if (callback) callback();
                return;
            }
            
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            function displayVerifyApiData(data) {
                function showVerifyApiData(containerId, value, displayValue = null) {
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
                
                showVerifyApiData('apiVerifyCnic', data.cnic);
                showVerifyApiData('apiVerifyFullName', data.full_name);
                showVerifyApiData('apiVerifyFatherHusbandName', data.father_husband_name);
                showVerifyApiData('apiVerifyMobile', data.mobile_number);
                showVerifyApiData('apiVerifyDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
                showVerifyApiData('apiVerifyGender', data.gender);
            }
            
            function fetchMemberData(token) {
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
                            displayVerifyApiData(response.data);
                            showCustomToast('success', 'API Data Loaded', 'Member data from Wheat Distribution System is now displayed for comparison.');
                        } else {
                            showCustomToast('info', 'API Data Not Available', response.message || 'Member data not found in Wheat Distribution System. You can still verify based on form data.');
                        }
                    },
                    error: function(xhr) {
                        if (callback) callback();
                        if (xhr.status === 404) {
                            const response = xhr.responseJSON;
                            if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                                showCustomToast('info', 'Member Not Found', response.message || 'Member not found in Wheat Distribution System. You can still verify based on form data.');
                            } else {
                                showCustomToast('warning', 'API Error', 'Unable to fetch API data for comparison. You can still verify based on form data.');
                            }
                        } else if (xhr.status === 401) {
                            showCustomToast('error', 'Authentication Failed', 'Authentication failed. Please check API credentials. You can still verify based on form data.');
                        } else {
                            showCustomToast('error', 'API Error', 'Unable to fetch API data. You can still verify based on form data.');
                        }
                    }
                });
            }
            
            if (apiToken) {
                fetchMemberData(apiToken);
            } else if (apiUsername && apiPassword) {
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
                            fetchMemberData(loginResponse.data.access_token);
                        } else {
                            if (callback) callback();
                            showCustomToast('error', 'Authentication Failed', 'Failed to authenticate with Wheat Distribution API. You can still verify based on form data.');
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
                        showCustomToast('error', 'Authentication Error', errorMessage + ' You can still verify based on form data.');
                    }
                });
            } else {
                if (callback) callback();
                showCustomToast('warning', 'API Configuration Missing', 'Please configure WHEAT_API_USERNAME and WHEAT_API_PASSWORD in your .env file. You can still verify based on form data.');
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/lzc-members/verify.blade.php ENDPATH**/ ?>