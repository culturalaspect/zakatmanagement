<?php $__env->startSection('title', config('app.name') . ' - Beneficiary Details'); ?>
<?php $__env->startSection('page_title', 'Beneficiary Details'); ?>
<?php $__env->startSection('breadcrumb', 'Beneficiary Details'); ?>

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

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Beneficiary Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('beneficiaries.index')); ?>" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                        <?php if(in_array($beneficiary->status, ['pending', 'rejected'])): ?>
                        <a href="<?php echo e(route('beneficiaries.edit', $beneficiary)); ?>" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                        <?php endif; ?>
                        <?php if(auth()->user()->isAdministratorHQ() || auth()->user()->isSuperAdmin()): ?>
                            <?php if($beneficiary->status == 'submitted'): ?>
                            <button type="button" class="btn btn-success" id="approveBeneficiaryBtn" data-beneficiary-id="<?php echo e($beneficiary->id); ?>">
                                <i class="ti-check"></i> Approve
                            </button>
                            <button type="button" class="btn btn-danger" id="rejectBeneficiaryBtn" data-beneficiary-id="<?php echo e($beneficiary->id); ?>">
                                <i class="ti-close"></i> Reject
                            </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Beneficiary Information</h5>
                            <?php if(auth()->user()->isAdministratorHQ() || auth()->user()->isSuperAdmin()): ?>
                            <button type="button" class="btn btn-sm btn-primary" id="fetchBeneficiaryApiBtn" title="Fetch details from Wheat Distribution System">
                                <i class="ti-search"></i> Fetch API Details
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>CNIC:</strong>
                        <p class="mb-0" id="show_cnic"><?php echo e($beneficiary->cnic ?? 'N/A'); ?></p>
                        <div id="show_apiCnic" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0" id="show_full_name"><?php echo e($beneficiary->full_name ?? 'N/A'); ?></p>
                        <div id="show_apiFullName" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father/Husband Name:</strong>
                        <p class="mb-0" id="show_father_husband_name"><?php echo e($beneficiary->father_husband_name ?? 'N/A'); ?></p>
                        <div id="show_apiFatherHusbandName" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mobile Number:</strong>
                        <p class="mb-0" id="show_mobile_number"><?php echo e($beneficiary->mobile_number ?? 'N/A'); ?></p>
                        <div id="show_apiMobileNumber" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p class="mb-0" id="show_date_of_birth"><?php echo e($beneficiary->date_of_birth ? \Carbon\Carbon::parse($beneficiary->date_of_birth)->format('d M Y') : 'N/A'); ?></p>
                        <div id="show_apiDateOfBirth" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0" id="show_gender">
                            <?php if($beneficiary->gender === 'male'): ?>
                                <span class="badge bg-primary">Male</span>
                            <?php elseif($beneficiary->gender === 'female'): ?>
                                <span class="badge bg-info">Female</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($beneficiary->gender ?? 'N/A')); ?></span>
                            <?php endif; ?>
                        </p>
                        <div id="show_apiGender" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p class="mb-0">
                            <?php if($beneficiary->status == 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif($beneficiary->status == 'submitted'): ?>
                                <span class="badge bg-info">Submitted</span>
                            <?php elseif($beneficiary->status == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif($beneficiary->status == 'rejected'): ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php elseif($beneficiary->status == 'paid'): ?>
                                <span class="badge bg-primary">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($beneficiary->status ?? 'N/A')); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Amount:</strong>
                        <p class="mb-0"><strong class="text-success">Rs. <?php echo e(number_format($beneficiary->amount ?? 0, 2)); ?></strong></p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Phase & Scheme Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phase:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->phase->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->phase->district->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Scheme:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->scheme->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Scheme Category:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->schemeCategory->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Local Zakat Committee:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->localZakatCommittee->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative Required:</strong>
                        <p class="mb-0">
                            <?php if($beneficiary->requires_representative): ?>
                                <span class="badge bg-warning">Yes (Age < 18)</span>
                            <?php else: ?>
                                <span class="badge bg-success">No (Age ≥ 18)</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php if($beneficiary->representative): ?>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Representative Information</h5>
                            <?php if(auth()->user()->isAdministratorHQ() || auth()->user()->isSuperAdmin()): ?>
                            <?php
                                $repCnic = $beneficiary->representative->cnic ?? '';
                                $repCnicDigits = str_replace(['-', ' '], '', $repCnic);
                                $showRepFetchBtn = strlen($repCnicDigits) === 13;
                            ?>
                            <?php if($showRepFetchBtn): ?>
                            <button type="button" class="btn btn-sm btn-primary" id="fetchRepresentativeApiBtn" title="Fetch representative details from Wheat Distribution System">
                                <i class="ti-search"></i> Fetch API Details
                            </button>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>CNIC:</strong>
                        <p class="mb-0" id="show_rep_cnic"><?php echo e($beneficiary->representative->cnic ?? 'N/A'); ?></p>
                        <div id="show_apiRepCnic" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0" id="show_rep_full_name"><?php echo e($beneficiary->representative->full_name ?? 'N/A'); ?></p>
                        <div id="show_apiRepFullName" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father/Husband Name:</strong>
                        <p class="mb-0" id="show_rep_father_husband_name"><?php echo e($beneficiary->representative->father_husband_name ?? 'N/A'); ?></p>
                        <div id="show_apiRepFatherHusbandName" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mobile Number:</strong>
                        <p class="mb-0" id="show_rep_mobile_number"><?php echo e($beneficiary->representative->mobile_number ?? 'N/A'); ?></p>
                        <div id="show_apiRepMobileNumber" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p class="mb-0" id="show_rep_date_of_birth"><?php echo e($beneficiary->representative->date_of_birth ? \Carbon\Carbon::parse($beneficiary->representative->date_of_birth)->format('d M Y') : 'N/A'); ?></p>
                        <div id="show_apiRepDateOfBirth" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0" id="show_rep_gender">
                            <?php if($beneficiary->representative->gender === 'male'): ?>
                                <span class="badge bg-primary">Male</span>
                            <?php elseif($beneficiary->representative->gender === 'female'): ?>
                                <span class="badge bg-info">Female</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($beneficiary->representative->gender ?? 'N/A')); ?></span>
                            <?php endif; ?>
                        </p>
                        <div id="show_apiRepGender" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Relationship:</strong>
                        <p class="mb-0" id="show_rep_relationship"><?php echo e($beneficiary->representative->relationship ?? 'N/A'); ?></p>
                        <div id="show_apiRepRelationship" class="api-data-container" style="display: none; margin-top: 5px;">
                            <div class="api-data-value"></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($beneficiary->district_remarks || $beneficiary->admin_remarks || $beneficiary->rejection_remarks): ?>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Remarks</h5>
                    </div>
                    <?php if($beneficiary->district_remarks): ?>
                    <div class="col-md-12 mb-3">
                        <strong>District Remarks:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->district_remarks); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($beneficiary->admin_remarks): ?>
                    <div class="col-md-12 mb-3">
                        <strong>Admin Remarks:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->admin_remarks); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($beneficiary->rejection_remarks): ?>
                    <div class="col-md-12 mb-3">
                        <strong>Rejection Remarks:</strong>
                        <p class="mb-0 text-danger"><?php echo e($beneficiary->rejection_remarks); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <hr class="my-4">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Timeline</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Created At:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->created_at->format('d M Y H:i:s')); ?></p>
                    </div>
                    <?php if($beneficiary->submitted_at): ?>
                    <div class="col-md-6 mb-3">
                        <strong>Submitted At:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->submitted_at->format('d M Y H:i:s')); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($beneficiary->submittedBy): ?>
                    <div class="col-md-6 mb-3">
                        <strong>Submitted By:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->submittedBy->name ?? 'N/A'); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($beneficiary->approved_at): ?>
                    <div class="col-md-6 mb-3">
                        <strong>Approved At:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->approved_at->format('d M Y H:i:s')); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($beneficiary->approvedBy): ?>
                    <div class="col-md-6 mb-3">
                        <strong>Approved By:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->approvedBy->name ?? 'N/A'); ?></p>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6 mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->updated_at->format('d M Y H:i:s')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        const beneficiaryId = <?php echo e($beneficiary->id); ?>;
        const beneficiaryCnic = '<?php echo e($beneficiary->cnic); ?>';
        const representativeCnic = '<?php echo e($beneficiary->representative->cnic ?? ''); ?>';

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

        // Generic API Fetcher
        function fetchApiData(url, cnic, successCallback, errorCallback, finalCallback) {
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';

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
        function fetchBeneficiaryApiData(cnic, callback) {
            fetchApiData(
                '/external/zakat/member/lookup',
                cnic,
                function(response) {
                    if (response.success && response.data) {
                        displayBeneficiaryApiData(response.data);
                        showCustomToast('success', 'API Data Fetched', 'Verified member data fetched. Compare with beneficiary details above.');
                    } else {
                        showCustomToast('info', 'Member Not Found', response.message || 'Member with this CNIC was not found in the verified database.');
                    }
                },
                function(xhr) {
                    let errorTitle = 'API Error';
                    let errorMessage = 'Failed to fetch member details.';
                    let errorIcon = 'error';

                    if (xhr.status === 401) {
                        errorTitle = 'Authentication Failed';
                        errorMessage = 'Authentication failed. Please check API credentials.';
                    } else if (xhr.status === 404) {
                        const response = xhr.responseJSON;
                        if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                            errorTitle = 'Member Not Found';
                            errorMessage = response.message || 'Member with this CNIC was not found in the verified database.';
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

        // Display Beneficiary API Data
        function displayBeneficiaryApiData(data) {
            function showApiData(containerId, value, displayValue = null) {
                if (value && value !== 'N/A' && value !== null && value !== '') {
                    const container = $(`#show_${containerId}`);
                    const valueDiv = container.find('.api-data-value');
                    const displayText = displayValue !== null ? displayValue : value;
                    valueDiv.text(`API: ${displayText}`);
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
            
            showApiData('apiCnic', data.cnic);
            showApiData('apiFullName', data.full_name);
            showApiData('apiFatherHusbandName', data.father_husband_name);
            showApiData('apiMobileNumber', data.mobile_number);
            showApiData('apiDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
            showApiData('apiGender', data.gender);
        }

        // Fetch Representative Data
        function fetchRepresentativeApiData(cnic, callback) {
            fetchApiData(
                '/external/zakat/member/lookup',
                cnic,
                function(response) {
                    if (response.success && response.data) {
                        displayRepresentativeApiData(response.data);
                        showCustomToast('success', 'API Data Fetched', 'Verified representative data fetched. Compare with representative details above.');
                    } else {
                        showCustomToast('info', 'Member Not Found', response.message || 'Representative with this CNIC was not found in the verified database.');
                    }
                },
                function(xhr) {
                    let errorTitle = 'API Error';
                    let errorMessage = 'Failed to fetch representative details.';
                    let errorIcon = 'error';

                    if (xhr.status === 401) {
                        errorTitle = 'Authentication Failed';
                        errorMessage = 'Authentication failed. Please check API credentials.';
                    } else if (xhr.status === 404) {
                        const response = xhr.responseJSON;
                        if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                            errorTitle = 'Member Not Found';
                            errorMessage = response.message || 'Representative with this CNIC was not found in the verified database.';
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

        // Display Representative API Data
        function displayRepresentativeApiData(data) {
            function showApiData(containerId, value, displayValue = null) {
                if (value && value !== 'N/A' && value !== null && value !== '') {
                    const container = $(`#show_${containerId}`);
                    const valueDiv = container.find('.api-data-value');
                    const displayText = displayValue !== null ? displayValue : value;
                    valueDiv.text(`API: ${displayText}`);
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
            
            showApiData('apiRepCnic', data.cnic);
            showApiData('apiRepFullName', data.full_name);
            showApiData('apiRepFatherHusbandName', data.father_husband_name);
            showApiData('apiRepMobileNumber', data.mobile_number);
            showApiData('apiRepDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
            showApiData('apiRepGender', data.gender);
        }

        // Fetch Beneficiary API Button
        $('#fetchBeneficiaryApiBtn').on('click', function() {
            const fetchBtn = $(this);
            const originalHtml = fetchBtn.html();
            fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
            
            const cnic = beneficiaryCnic.replace(/\D/g, '');
            if (cnic.length !== 13) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'Invalid CNIC', 'Beneficiary CNIC is not valid (13 digits required).');
                return;
            }
            
            fetchBeneficiaryApiData(beneficiaryCnic, function() {
                fetchBtn.prop('disabled', false).html(originalHtml);
            });
        });

        // Fetch Representative API Button
        $('#fetchRepresentativeApiBtn').on('click', function() {
            const fetchBtn = $(this);
            const originalHtml = fetchBtn.html();
            fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
            
            const cnic = representativeCnic.replace(/\D/g, '');
            if (cnic.length !== 13) {
                fetchBtn.prop('disabled', false).html(originalHtml);
                showCustomToast('warning', 'Invalid CNIC', 'Representative CNIC is not valid (13 digits required).');
                return;
            }
            
            fetchRepresentativeApiData(representativeCnic, function() {
                fetchBtn.prop('disabled', false).html(originalHtml);
            });
        });

        // Approve Beneficiary Button
        $('#approveBeneficiaryBtn').on('click', function() {
            showApproveBeneficiaryModal();
        });

        function showApproveBeneficiaryModal() {
            Swal.fire({
                title: 'Approve Beneficiary',
                html: `
                    <form id="approveBeneficiaryForm" style="text-align: left;">
                        <div class="mb-3">
                            <label class="form-label"><strong>Admin Remarks</strong> (Optional)</label>
                            <textarea name="admin_remarks" id="approve_admin_remarks" class="form-control" rows="3" placeholder="Enter any remarks or observations..."><?php echo e($beneficiary->admin_remarks ?? ''); ?></textarea>
                            <small class="text-muted">Add any remarks or observations about this approval.</small>
                        </div>
                    </form>
                `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#000000',
                customClass: {
                    cancelButton: 'btn btn-dark',
                    confirmButton: 'btn btn-success'
                },
                preConfirm: () => {
                    const remarks = $('#approve_admin_remarks').val();
                    return $.ajax({
                        url: '<?php echo e(route("admin-hq.approve", $beneficiary)); ?>',
                        type: 'POST',
                        data: {
                            admin_remarks: remarks
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Approved!',
                                text: response.message || 'Beneficiary has been approved successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '<?php echo e(route("beneficiaries.show", $beneficiary)); ?>';
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to approve beneficiary.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.showValidationMessage(errorMessage);
                        }
                    });
                }
            });
        }

        // Reject Beneficiary Button
        $('#rejectBeneficiaryBtn').on('click', function() {
            showRejectBeneficiaryModal();
        });

        function showRejectBeneficiaryModal() {
            Swal.fire({
                title: 'Reject Beneficiary',
                html: `
                    <form id="rejectBeneficiaryForm" style="text-align: left;">
                        <div class="mb-3">
                            <label class="form-label"><strong>Rejection Remarks</strong> <span class="text-danger">*</span></label>
                            <textarea name="admin_remarks" id="reject_admin_remarks" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                            <small class="text-muted">Please provide a reason for rejecting this beneficiary.</small>
                        </div>
                    </form>
                `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#000000',
                customClass: {
                    cancelButton: 'btn btn-dark',
                    confirmButton: 'btn btn-danger'
                },
                preConfirm: () => {
                    const remarks = $('#reject_admin_remarks').val();
                    if (!remarks || remarks.trim() === '') {
                        Swal.showValidationMessage('Rejection remarks are required.');
                        return false;
                    }
                    return $.ajax({
                        url: '<?php echo e(route("admin-hq.reject", $beneficiary)); ?>',
                        type: 'POST',
                        data: {
                            admin_remarks: remarks
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected!',
                                text: response.message || 'Beneficiary has been rejected successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '<?php echo e(route("beneficiaries.show", $beneficiary)); ?>';
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to reject beneficiary.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.showValidationMessage(errorMessage);
                        }
                    });
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/beneficiaries/show.blade.php ENDPATH**/ ?>