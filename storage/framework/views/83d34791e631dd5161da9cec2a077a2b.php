<?php $__env->startSection('title', config('app.name') . ' - View Local Zakat Committee'); ?>
<?php $__env->startSection('page_title', 'View Local Zakat Committee'); ?>
<?php $__env->startSection('breadcrumb', 'View Local Zakat Committee'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Local Zakat Committee Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('local-zakat-committees.edit', $localZakatCommittee)); ?>" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Committee Code:</strong>
                        <p><strong class="text-primary"><?php echo e($localZakatCommittee->code ?? 'N/A'); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Committee Name:</strong>
                        <p><?php echo e($localZakatCommittee->name); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p><?php echo e($localZakatCommittee->district->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <strong>Area Coverage:</strong>
                        <p><?php echo e($localZakatCommittee->area_coverage ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Formation Date:</strong>
                        <p><?php echo e($localZakatCommittee->formation_date ? \Carbon\Carbon::parse($localZakatCommittee->formation_date)->format('d M Y') : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Tenure (Years):</strong>
                        <p><?php echo e($localZakatCommittee->tenure_years ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Tenure End Date:</strong>
                        <p><?php echo e($localZakatCommittee->tenure_end_date ? \Carbon\Carbon::parse($localZakatCommittee->tenure_end_date)->format('d M Y') : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            <?php if($localZakatCommittee->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Total Members:</strong>
                        <p><span id="totalMembersCount"><?php echo e($localZakatCommittee->members->count() ?? 0); ?></span></p>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Serving Mohallas (<span id="mohallasCount"><?php echo e($localZakatCommittee->mohallas->count()); ?></span>)</h5>
                    <button type="button" class="btn btn-sm btn-success" id="addMohallaBtn">
                        <i class="ti-plus"></i> Add Mohalla
                    </button>
                </div>
                <div id="mohallasTableContainer">
                    <?php if($localZakatCommittee->mohallas->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm" id="mohallasTable">
                            <thead>
                                <tr>
                                    <th>Mohalla</th>
                                    <th>Village</th>
                                    <th>Union Council</th>
                                    <th>Tehsil</th>
                                    <th>District</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="mohallasTableBody">
                                <?php $__currentLoopData = $localZakatCommittee->mohallas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mohalla): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-mohalla-id="<?php echo e($mohalla->id); ?>">
                                    <td><strong><?php echo e($mohalla->name); ?></strong></td>
                                    <td><?php echo e($mohalla->village->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($mohalla->village->unionCouncil->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($mohalla->village->unionCouncil->tehsil->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($mohalla->village->unionCouncil->tehsil->district->name ?? 'N/A'); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger deleteMohallaBtn" data-mohalla-id="<?php echo e($mohalla->id); ?>" data-mohalla-name="<?php echo e($mohalla->name); ?>">
                                            <i class="ti-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <p class="mb-0">No mohallas have been added to this committee yet.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Committee Members</h5>
                    <button type="button" class="btn btn-sm btn-success" id="addMemberBtn">
                        <i class="ti-plus"></i> Add Member
                    </button>
                </div>
                <div id="membersTableContainer">
                    <?php if($localZakatCommittee->members->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table" id="membersTable">
                            <thead>
                                <tr>
                                    <th>CNIC</th>
                                    <th>Full Name</th>
                                    <th>Mobile Number</th>
                                    <th>Gender</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Verification Status</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="membersTableBody">
                                <?php $__currentLoopData = $localZakatCommittee->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-member-id="<?php echo e($member->id); ?>">
                                    <td><strong><?php echo e($member->cnic); ?></strong></td>
                                    <td><?php echo e($member->full_name); ?></td>
                                    <td><?php echo e($member->mobile_number ?? 'N/A'); ?></td>
                                    <td>
                                        <?php
                                            $genderValue = $member->getAttribute('gender') ?? '';
                                            $gender = is_string($genderValue) ? strtolower(trim($genderValue)) : '';
                                        ?>
                                        <?php if($gender === 'male'): ?>
                                            <span class="badge bg-primary">Male</span>
                                        <?php elseif($gender === 'female'): ?>
                                            <span class="badge bg-info">Female</span>
                                        <?php elseif($gender === 'other'): ?>
                                            <span class="badge bg-secondary">Other</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning" title="Raw value: '<?php echo e($genderValue); ?>' | Processed: '<?php echo e($gender); ?>'">Unknown</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($member->start_date ? \Carbon\Carbon::parse($member->start_date)->format('d M Y') : 'N/A'); ?></td>
                                    <td><?php echo e($member->end_date ? \Carbon\Carbon::parse($member->end_date)->format('d M Y') : 'Ongoing'); ?></td>
                                    <td>
                                        <?php if($member->verification_status == 'verified'): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php elseif($member->verification_status == 'rejected'): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($member->is_active): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action_btns d-flex">
                                            <button type="button" class="action_btn mr_10 viewMemberBtn" data-member-id="<?php echo e($member->id); ?>" title="View">
                                                <i class="ti-eye"></i>
                                            </button>
                                            <?php if($member->verification_status == 'pending'): ?>
                                            <button type="button" class="action_btn mr_10 verifyMemberBtn" data-member-id="<?php echo e($member->id); ?>" title="Verify">
                                                <i class="ti-check"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php if($member->verification_status != 'verified'): ?>
                                            <button type="button" class="action_btn mr_10 editMemberBtn" data-member-id="<?php echo e($member->id); ?>" data-verification-status="<?php echo e($member->verification_status); ?>" title="Edit">
                                                <i class="ti-pencil"></i>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" class="action_btn mr_10" disabled title="Verified members cannot be edited" style="opacity: 0.5; cursor: not-allowed;">
                                                <i class="ti-pencil"></i>
                                            </button>
                                            <?php endif; ?>
                                            <button type="button" class="action_btn deleteMemberBtn" data-member-id="<?php echo e($member->id); ?>" data-member-name="<?php echo e($member->full_name); ?>" title="Delete">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <p class="mb-0">No members have been added to this committee yet.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="<?php echo e(route('local-zakat-committees.index')); ?>" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Left align form fields in SweetAlert modals */
    .swal2-html-container {
        text-align: left !important;
    }
    
    .swal2-html-container form,
    .swal2-html-container .row,
    .swal2-html-container .mb-3,
    .swal2-html-container .form-label,
    .swal2-html-container .form-control,
    .swal2-html-container select,
    .swal2-html-container textarea,
    .swal2-html-container input {
        text-align: left !important;
    }
    
    .swal2-html-container .form-label {
        display: block;
        text-align: left !important;
        margin-bottom: 0.5rem;
    }
    
    .swal2-html-container .text-left {
        text-align: left !important;
    }
    
    /* Input group styling for CNIC field with Fetch button */
    .swal2-html-container .input-group {
        display: flex;
        width: 100%;
    }
    
    .swal2-html-container .input-group .form-control {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .swal2-html-container .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        white-space: nowrap;
        padding: 0.375rem 0.75rem;
    }
    
    .swal2-html-container .input-group .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* API Data Container Styling */
    .api-data-container {
        margin-top: 5px;
        padding: 8px 12px;
        background-color: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 0.9em;
    }
    
    .api-data-value {
        flex: 1;
        color: #0066cc;
        font-weight: 500;
    }
    
    .api-data-container::before {
        content: "📋 API Data: ";
        font-weight: 600;
        color: #004499;
        margin-right: 5px;
    }
    
    .api-copy-btn {
        white-space: nowrap;
        padding: 4px 12px;
        font-size: 0.85em;
    }
    
    .api-copy-btn:hover {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .api-copy-btn.copied {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .position-relative {
        position: relative;
    }
    
    /* Custom Toast Notification */
    .custom-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 300px;
        max-width: 400px;
        background-color: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s ease-out;
    }
    
    .custom-toast.success {
        background-color: #28a745;
    }
    
    .custom-toast.error {
        background-color: #dc3545;
    }
    
    .custom-toast.warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .custom-toast.info {
        background-color: #17a2b8;
    }
    
    .custom-toast-icon {
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .custom-toast-content {
        flex: 1;
    }
    
    .custom-toast-title {
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 14px;
    }
    
    .custom-toast-message {
        font-size: 13px;
        opacity: 0.95;
    }
    
    .custom-toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    
    .custom-toast-close:hover {
        opacity: 1;
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
        const committeeId = <?php echo e($localZakatCommittee->id); ?>;
        let availableMohallas = <?php echo json_encode($availableMohallas ?? [], 15, 512) ?>;
        
        // Initialize DataTable for members if table exists
        if ($('#membersTable').length) {
            $('#membersTable').DataTable({
                scrollX: true,
                order: [[1, 'asc']],
                autoWidth: false
            });
        }

        // Fetch API Data for Edit Member Modal
        function fetchEditMemberApiData(cnic, callback) {
            if (!cnic || cnic.trim() === '') {
                if (callback) callback();
                return;
            }
            
            // API Configuration
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            // Function to display API data in edit modal
            function displayEditApiData(data) {
                // Function to show API data for a field
                function showEditApiData(containerId, value, displayValue = null) {
                    if (value && value !== 'N/A' && value !== null && value !== '') {
                        const container = $(`#${containerId}`);
                        const valueDiv = container.find('.api-data-value');
                        
                        const displayText = displayValue !== null ? displayValue : value;
                        valueDiv.text(displayText);
                        container.data('original-value', value);
                        container.show();
                    }
                }
                
                // Format date for display
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
                
                // Display API data next to each field
                showEditApiData('editApiCnic', data.cnic);
                showEditApiData('editApiFullName', data.full_name);
                showEditApiData('editApiFatherHusbandName', data.father_husband_name);
                showEditApiData('editApiMobileNumber', data.mobile_number);
                showEditApiData('editApiDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
                showEditApiData('editApiGender', data.gender);
            }
            
            // Function to fetch member data
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
                            displayEditApiData(response.data);
                            showCustomToast('success', 'API Data Loaded', 'Member data from Wheat Distribution System is now displayed. Click Copy to update fields.');
                        } else {
                            showCustomToast('info', 'API Data Not Available', response.message || 'Member data not found in Wheat Distribution System.');
                        }
                    },
                    error: function(xhr) {
                        if (callback) callback();
                        if (xhr.status === 404) {
                            const response = xhr.responseJSON;
                            if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                                showCustomToast('info', 'Member Not Found', response.message || 'Member not found in Wheat Distribution System.');
                            } else {
                                showCustomToast('warning', 'API Error', 'Unable to fetch API data.');
                            }
                        } else if (xhr.status === 401) {
                            showCustomToast('error', 'Authentication Failed', 'Authentication failed. Please check API credentials.');
                        } else {
                            showCustomToast('error', 'API Error', 'Unable to fetch API data.');
                        }
                    }
                });
            }
            
            // If token is available, use it directly
            if (apiToken) {
                fetchMemberData(apiToken);
            } else if (apiUsername && apiPassword) {
                // Try to login first to get token
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
                            showCustomToast('error', 'Authentication Failed', 'Failed to authenticate with Wheat Distribution API.');
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
            } else {
                if (callback) callback();
                showCustomToast('warning', 'API Configuration Missing', 'Please configure WHEAT_API_USERNAME and WHEAT_API_PASSWORD in your .env file.');
            }
        }
        
        // Fetch API Data for Verify Member Modal
        function fetchVerifyMemberApiData(cnic, callback) {
            if (!cnic || cnic.trim() === '') {
                if (callback) callback();
                return;
            }
            
            // API Configuration
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            // Function to display API data in verify modal
            function displayVerifyApiData(data) {
                // Function to show API data for a field
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
                
                // Format date for display
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
                
                // Display API data next to each field
                showVerifyApiData('verifyApiCnic', data.cnic);
                showVerifyApiData('verifyApiFullName', data.full_name);
                showVerifyApiData('verifyApiFatherHusbandName', data.father_husband_name);
                showVerifyApiData('verifyApiMobile', data.mobile_number);
                showVerifyApiData('verifyApiDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
                showVerifyApiData('verifyApiGender', data.gender);
            }
            
            // Function to fetch member data
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
                            showCustomToast('info', 'API Data Not Available', response.message || 'Member data not found in Wheat Distribution System.');
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
            
            // If token is available, use it directly
            if (apiToken) {
                fetchMemberData(apiToken);
            } else if (apiUsername && apiPassword) {
                // Try to login first to get token
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
        
        // Custom Toast Notification Function
        function showCustomToast(type, title, message, duration = 3000) {
            // Remove any existing toasts
            $('.custom-toast').remove();
            
            // Create toast element
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
            
            // Add to body
            $('body').append(toast);
            
            // Auto remove after duration
            if (duration > 0) {
                setTimeout(function() {
                    toast.addClass('hiding');
                    setTimeout(function() {
                        toast.remove();
                    }, 300);
                }, duration);
            }
            
            // Pause on hover
            toast.on('mouseenter', function() {
                toast.find('.custom-toast-progress-bar').css('animation-play-state', 'paused');
            }).on('mouseleave', function() {
                toast.find('.custom-toast-progress-bar').css('animation-play-state', 'running');
            });
        }
        
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

        // Add Mohalla Button Click
        $('#addMohallaBtn').on('click', function() {
            if (availableMohallas.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Mohallas Available',
                    text: 'All mohallas in this district are already attached to this committee.',
                    confirmButtonColor: '#567AED'
                });
                return;
            }

            const districts = <?php echo json_encode($districts ?? [], 15, 512) ?>;
            const tehsils = <?php echo json_encode($tehsils ?? [], 15, 512) ?>;
            const unionCouncils = <?php echo json_encode($unionCouncils ?? [], 15, 512) ?>;
            const villages = <?php echo json_encode($villages ?? [], 15, 512) ?>;
            
            // Build mohalla options with data attributes for filtering
            let mohallaOptions = availableMohallas.map(m => {
                const village = m.village;
                const uc = village.union_council;
                const tehsil = uc.tehsil;
                const district = tehsil.district;
                return `<option value="${m.id}" 
                        data-district-id="${district.id}"
                        data-tehsil-id="${tehsil.id}"
                        data-union-council-id="${uc.id}"
                        data-village-id="${village.id}">
                        ${m.name} - ${village.name} (Village), ${uc.name} (UC), ${tehsil.name} (Tehsil), ${district.name} (District)
                    </option>`;
            }).join('');

            // Build filter dropdowns
            let districtOptions = districts.map(d => `<option value="${d.id}">${d.name}</option>`).join('');
            let tehsilOptions = tehsils.map(t => `<option value="${t.id}" data-district-id="${t.district_id}">${t.name}</option>`).join('');
            let unionCouncilOptions = unionCouncils.map(uc => `<option value="${uc.id}" data-tehsil-id="${uc.tehsil_id}">${uc.name}</option>`).join('');
            let villageOptions = villages.map(v => `<option value="${v.id}" data-union-council-id="${v.union_council_id}">${v.name}</option>`).join('');

            Swal.fire({
                title: 'Add Mohalla',
                html: `
                    <form id="addMohallaForm" style="text-align: left;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Filter by District</label>
                                <select id="filterDistrict" class="form-control">
                                    <option value="">All Districts</option>
                                    ${districtOptions}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Filter by Tehsil</label>
                                <select id="filterTehsil" class="form-control">
                                    <option value="">All Tehsils</option>
                                    ${tehsilOptions}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Filter by Union Council</label>
                                <select id="filterUnionCouncil" class="form-control">
                                    <option value="">All Union Councils</option>
                                    ${unionCouncilOptions}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Filter by Village</label>
                                <select id="filterVillage" class="form-control">
                                    <option value="">All Villages</option>
                                    ${villageOptions}
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Mohalla <span class="text-danger">*</span></label>
                            <select id="mohallaSelect" class="form-control" required>
                                <option value="">Select Mohalla</option>
                                ${mohallaOptions}
                            </select>
                        </div>
                    </form>
                `,
                width: '900px',
                showCancelButton: true,
                confirmButtonText: 'Add',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#567AED',
                cancelButtonColor: '#000000',
                didOpen: () => {
                    // Initialize Select2
                    $('#mohallaSelect').select2({
                        dropdownParent: Swal.getContainer(),
                        width: '100%',
                        placeholder: 'Select mohalla...'
                    });
                    
                    // Function to rebuild mohalla options based on filters
                    function rebuildMohallaOptions() {
                        const districtId = $('#filterDistrict').val();
                        const tehsilId = $('#filterTehsil').val();
                        const unionCouncilId = $('#filterUnionCouncil').val();
                        const villageId = $('#filterVillage').val();
                        
                        const currentSelected = $('#mohallaSelect').val() || [];
                        
                        $('#mohallaSelect').empty();
                        $('#mohallaSelect').append('<option value="">Select Mohalla</option>');
                        
                        availableMohallas.forEach(function(mohalla) {
                            let show = true;
                            
                            if (districtId && mohalla.village.union_council.tehsil.district.id != districtId) {
                                show = false;
                            }
                            if (tehsilId && mohalla.village.union_council.tehsil.id != tehsilId) {
                                show = false;
                            }
                            if (unionCouncilId && mohalla.village.union_council.id != unionCouncilId) {
                                show = false;
                            }
                            if (villageId && mohalla.village.id != villageId) {
                                show = false;
                            }
                            
                            if (show) {
                                const village = mohalla.village;
                                const uc = village.union_council;
                                const tehsil = uc.tehsil;
                                const district = tehsil.district;
                                const $option = $('<option></option>')
                                    .attr('value', mohalla.id)
                                    .text(`${mohalla.name} - ${village.name} (Village), ${uc.name} (UC), ${tehsil.name} (Tehsil), ${district.name} (District)`)
                                    .data('district-id', district.id)
                                    .data('tehsil-id', tehsil.id)
                                    .data('union-council-id', uc.id)
                                    .data('village-id', village.id);
                                
                                if (currentSelected.includes(mohalla.id.toString())) {
                                    $option.prop('selected', true);
                                }
                                
                                $('#mohallaSelect').append($option);
                            }
                        });
                        
                        $('#mohallaSelect').select2('destroy').select2({
                            dropdownParent: Swal.getContainer(),
                            width: '100%',
                            placeholder: 'Select mohalla...'
                        });
                    }
                    
                    // Filter hierarchical dropdowns
                    function filterHierarchicalDropdowns() {
                        const selectedDistrictId = $('#filterDistrict').val();
                        const selectedTehsilId = $('#filterTehsil').val();
                        const selectedUnionCouncilId = $('#filterUnionCouncil').val();
                        
                        // Filter Tehsils
                        $('#filterTehsil option').each(function() {
                            if ($(this).val() === '') return true;
                            if (selectedDistrictId === '' || $(this).data('district-id') == selectedDistrictId) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                        if (selectedDistrictId && $('#filterTehsil').find('option:selected').css('display') === 'none') {
                            $('#filterTehsil').val('').trigger('change');
                        }
                        
                        // Filter Union Councils
                        $('#filterUnionCouncil option').each(function() {
                            if ($(this).val() === '') return true;
                            if (selectedTehsilId === '' || $(this).data('tehsil-id') == selectedTehsilId) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                        if (selectedTehsilId && $('#filterUnionCouncil').find('option:selected').css('display') === 'none') {
                            $('#filterUnionCouncil').val('').trigger('change');
                        }
                        
                        // Filter Villages
                        $('#filterVillage option').each(function() {
                            if ($(this).val() === '') return true;
                            if (selectedUnionCouncilId === '' || $(this).data('union-council-id') == selectedUnionCouncilId) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                        if (selectedUnionCouncilId && $('#filterVillage').find('option:selected').css('display') === 'none') {
                            $('#filterVillage').val('').trigger('change');
                        }
                    }
                    
                    // Event handlers for filters
                    $('#filterDistrict').on('change', function() {
                        $('#filterTehsil').val('');
                        $('#filterUnionCouncil').val('');
                        $('#filterVillage').val('');
                        filterHierarchicalDropdowns();
                        rebuildMohallaOptions();
                    });
                    
                    $('#filterTehsil').on('change', function() {
                        $('#filterUnionCouncil').val('');
                        $('#filterVillage').val('');
                        filterHierarchicalDropdowns();
                        rebuildMohallaOptions();
                    });
                    
                    $('#filterUnionCouncil').on('change', function() {
                        $('#filterVillage').val('');
                        filterHierarchicalDropdowns();
                        rebuildMohallaOptions();
                    });
                    
                    $('#filterVillage').on('change', function() {
                        rebuildMohallaOptions();
                    });
                    
                    filterHierarchicalDropdowns();
                    rebuildMohallaOptions();
                },
                preConfirm: () => {
                    const mohallaId = $('#mohallaSelect').val();
                    if (!mohallaId) {
                        Swal.showValidationMessage('Please select a mohalla');
                        return false;
                    }
                    return { mohalla_id: mohallaId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo e(route("local-zakat-committees.add-mohalla", $localZakatCommittee)); ?>',
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            mohalla_id: result.value.mohalla_id
                        },
                        success: function(response) {
                            if (response.success) {
                                // Add row to table
                                const mohalla = response.mohalla;
                                const row = `
                                    <tr data-mohalla-id="${mohalla.id}">
                                        <td><strong>${mohalla.name}</strong></td>
                                        <td>${mohalla.village.name || 'N/A'}</td>
                                        <td>${mohalla.village.union_council.name || 'N/A'}</td>
                                        <td>${mohalla.village.union_council.tehsil.name || 'N/A'}</td>
                                        <td>${mohalla.village.union_council.tehsil.district.name || 'N/A'}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger deleteMohallaBtn" data-mohalla-id="${mohalla.id}" data-mohalla-name="${mohalla.name}">
                                                <i class="ti-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                
                                if ($('#mohallasTableBody').length) {
                                    $('#mohallasTableBody').append(row);
                                } else {
                                    $('#mohallasTableContainer').html(`
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="mohallasTable">
                                                <thead>
                                                    <tr>
                                                        <th>Mohalla</th>
                                                        <th>Village</th>
                                                        <th>Union Council</th>
                                                        <th>Tehsil</th>
                                                        <th>District</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="mohallasTableBody">
                                                    ${row}
                                                </tbody>
                                            </table>
                                        </div>
                                    `);
                                }
                                
                                // Update count
                                const count = $('#mohallasTableBody tr').length;
                                $('#mohallasCount').text(count);
                                
                                // Remove from available list
                                availableMohallas = availableMohallas.filter(m => m.id != mohalla.id);
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    confirmButtonColor: '#567AED'
                                });
                            }
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'An error occurred';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message,
                                confirmButtonColor: '#567AED'
                            });
                        }
                    });
                }
            });
        });

        // Delete Mohalla Button Click
        $(document).on('click', '.deleteMohallaBtn', function() {
            const mohallaId = $(this).data('mohalla-id');
            const mohallaName = $(this).data('mohalla-name');
            
            Swal.fire({
                title: 'Delete Mohalla',
                text: `Are you sure you want to remove "${mohallaName}" from this committee?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#567AED',
                cancelButtonColor: '#000000'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo e(route("local-zakat-committees.remove-mohalla", $localZakatCommittee)); ?>',
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            mohalla_id: mohallaId
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove row
                                $(`tr[data-mohalla-id="${mohallaId}"]`).remove();
                                
                                // Update count
                                const count = $('#mohallasTableBody tr').length;
                                $('#mohallasCount').text(count);
                                
                                if (count === 0) {
                                    $('#mohallasTableContainer').html('<div class="alert alert-info"><p class="mb-0">No mohallas have been added to this committee yet.</p></div>');
                                }
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    confirmButtonColor: '#567AED'
                                });
                            }
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'An error occurred';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message,
                                confirmButtonColor: '#567AED'
                            });
                        }
                    });
                }
            });
        });

        // Add Member Button Click
        $('#addMemberBtn').on('click', function() {
            Swal.fire({
                title: 'Add New Member',
                html: `
                    <form id="addMemberForm" style="text-align: left;">
                        <input type="hidden" name="local_zakat_committee_id" value="${committeeId}">
                        <div class="row" style="text-align: left;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CNIC <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="cnic" id="memberCnic" class="form-control" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" required>
                                    <button type="button" class="btn btn-primary" id="fetchMemberDetailsBtn" title="Fetch details from Wheat Distribution System">
                                        <i class="ti-search"></i> Fetch Details
                                    </button>
                                </div>
                                <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="full_name" id="fullNameInput" class="form-control" required>
                                    <div id="apiFullName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="fullNameInput" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="father_husband_name" id="fatherHusbandNameInput" class="form-control" required>
                                    <div id="apiFatherHusbandName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="fatherHusbandNameInput" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <div class="position-relative">
                                    <input type="text" name="mobile_number" id="memberMobile" class="form-control" placeholder="03XX-XXXXXXX" maxlength="12">
                                    <div id="apiMobileNumber" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="memberMobile" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Format: 03XX-XXXXXXX</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="date" name="date_of_birth" id="dateOfBirthInput" class="form-control" required>
                                    <div id="apiDateOfBirth" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="dateOfBirthInput" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select name="gender" id="genderSelect" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div id="apiGender" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="genderSelect" data-is-select="true" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control">
                                <small class="text-muted">Leave empty if tenure is ongoing</small>
                            </div>
                        </div>
                    </form>
                `,
                width: '800px',
                showCancelButton: true,
                confirmButtonText: 'Add Member',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#567AED',
                cancelButtonColor: '#000000',
                didOpen: () => {
                    // CNIC formatting
                    $('#memberCnic').on('input', function() {
                        $(this).val(formatCNIC($(this).val()));
                    });
                    
                    // Mobile formatting
                    $('#memberMobile').on('input', function() {
                        $(this).val(formatMobileNumber($(this).val()));
                    });
                    
                    // Copy API Data to Form Fields
                    $(document).on('click', '.api-copy-btn', function() {
                        const targetId = $(this).data('target');
                        const isSelect = $(this).data('is-select') || false;
                        const apiContainer = $(this).closest('.api-data-container');
                        
                        // Get original value from data attribute (for dates) or text content
                        let apiValue = apiContainer.data('original-value');
                        if (!apiValue) {
                            apiValue = apiContainer.find('.api-data-value').text().trim();
                        }
                        
                        if (!apiValue || apiValue === 'N/A' || apiValue === '') {
                            return;
                        }
                        
                        if (isSelect) {
                            // For select fields, find matching option
                            const targetSelect = $(`#${targetId}`);
                            const genderValue = apiValue.toLowerCase();
                            targetSelect.val(genderValue);
                            
                            if (!targetSelect.val()) {
                                // If exact match not found, try to find similar
                                targetSelect.find('option').each(function() {
                                    const optionText = $(this).text().toLowerCase();
                                    if (optionText.includes(genderValue) || genderValue.includes(optionText)) {
                                        targetSelect.val($(this).val());
                                        return false;
                                    }
                                });
                            }
                        } else {
                            // For input fields
                            const targetInput = $(`#${targetId}`);
                            
                            // Handle date fields - use original value which is in YYYY-MM-DD format
                            if (targetInput.attr('type') === 'date') {
                                // The original value should already be in YYYY-MM-DD format
                                if (apiValue.match(/^\d{4}-\d{2}-\d{2}$/)) {
                                    targetInput.val(apiValue);
                                } else {
                                    // Try to parse if not in correct format
                                    try {
                                        const parsedDate = new Date(apiValue);
                                        if (!isNaN(parsedDate.getTime())) {
                                            const year = parsedDate.getFullYear();
                                            const month = String(parsedDate.getMonth() + 1).padStart(2, '0');
                                            const day = String(parsedDate.getDate()).padStart(2, '0');
                                            targetInput.val(`${year}-${month}-${day}`);
                                        }
                                    } catch (e) {
                                        console.error('Date parsing error:', e);
                                    }
                                }
                            } else {
                                targetInput.val(apiValue);
                                
                                // Trigger input event for formatting (CNIC, Mobile)
                                if (targetId === 'memberCnic') {
                                    targetInput.trigger('input');
                                } else if (targetId === 'memberMobile') {
                                    targetInput.trigger('input');
                                }
                            }
                        }
                        
                        // Visual feedback
                        const copyBtn = $(this);
                        const originalHtml = copyBtn.html();
                        copyBtn.addClass('copied').html('<i class="ti-check"></i> Copied!');
                        
                        setTimeout(function() {
                            copyBtn.removeClass('copied').html(originalHtml);
                        }, 2000);
                    });
                    
                    // Fetch Member Details Button Click
                    $('#fetchMemberDetailsBtn').on('click', function() {
                        const cnic = $('#memberCnic').val().trim();
                        
                        if (!cnic) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'CNIC Required',
                                text: 'Please enter a CNIC first.',
                                confirmButtonColor: '#567AED'
                            });
                            return;
                        }
                        
                        // Validate CNIC format (basic check - should have at least 13 digits)
                        const cnicDigits = cnic.replace(/\D/g, '');
                        if (cnicDigits.length < 13) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Invalid CNIC',
                                text: 'Please enter a valid CNIC (13 digits).',
                                confirmButtonColor: '#567AED'
                            });
                            return;
                        }
                        
                        // Show loading
                        const fetchBtn = $(this);
                        const originalHtml = fetchBtn.html();
                        fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                        
                        // Clear any previously displayed API data
                        $('.api-data-container').hide();
                        $('.api-data-value').text('');
                        
                        // API Configuration
                        const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
                        const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
                        
                        // First, try to get token if not available (login)
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
                                    fetchBtn.prop('disabled', false).html(originalHtml);
                                    
                                    if (response.success && response.data) {
                                        // Member found - display data next to each field
                                        const data = response.data;
                                        
                                        // Function to show API data for a field
                                        function showApiData(containerId, value, displayValue = null) {
                                            if (value && value !== 'N/A' && value !== null && value !== '') {
                                                const container = $(`#${containerId}`);
                                                const valueDiv = container.find('.api-data-value');
                                                
                                                // Use displayValue if provided, otherwise use the original value
                                                const displayText = displayValue !== null ? displayValue : value;
                                                valueDiv.text(displayText);
                                                
                                                // Store original value in data attribute for copying
                                                container.data('original-value', value);
                                                
                                                container.show();
                                            }
                                        }
                                        
                                        // Format date for display
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
                                        
                                        // Display API data next to each field
                                        showApiData('apiFullName', data.full_name);
                                        showApiData('apiFatherHusbandName', data.father_husband_name);
                                        showApiData('apiMobileNumber', data.mobile_number);
                                        showApiData('apiDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
                                        showApiData('apiGender', data.gender);
                                        
                                        // Show custom success toast notification (top right) - doesn't interfere with modal
                                        showCustomToast('success', 'Member Found', 'Verified member data fetched. Review and copy to form fields.');
                                    } else {
                                        // Member not found or not verified - show error toast
                                        showCustomToast('info', 'Member Not Found', response.message || 'Member with this CNIC was not found in the verified database. Please enter details manually.');
                                    }
                                },
                                error: function(xhr) {
                                    fetchBtn.prop('disabled', false).html(originalHtml);
                                    
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
                                    } else if (xhr.status === 403) {
                                        errorTitle = 'Access Denied';
                                        errorMessage = 'You do not have permission to access this resource.';
                                    } else if (xhr.status === 500) {
                                        errorTitle = 'Server Error';
                                        errorMessage = 'An internal server error occurred. Please try again later.';
                                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                        if (xhr.responseJSON.error_code) {
                                            errorTitle = xhr.responseJSON.error_code.replace(/_/g, ' ');
                                        }
                                    } else if (xhr.status === 0) {
                                        errorTitle = 'Connection Error';
                                        errorMessage = 'Unable to connect to the API server. Please check if the Wheat Distribution application is running and the API URL is correct.';
                                    }
                                    
                                    // Show error toast
                                    const toastMessage = xhr.status ? `${errorMessage} (Status: ${xhr.status})` : errorMessage;
                                    showCustomToast(errorIcon === 'info' ? 'info' : 'error', errorTitle, toastMessage);
                                }
                            });
                        }
                        
                        // If token is available, use it directly
                        if (apiToken) {
                            fetchMemberData(apiToken);
                        } else {
                            // Try to login first to get token
                            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
                            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
                            
                            if (!apiUsername || !apiPassword) {
                                fetchBtn.prop('disabled', false).html(originalHtml);
                                showCustomToast('warning', 'API Configuration Missing', 'Please configure WHEAT_API_USERNAME and WHEAT_API_PASSWORD in your .env file.');
                                return;
                            }
                            
                            // Login to get token
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
                                        // Store token temporarily and use it
                                        fetchMemberData(loginResponse.data.access_token);
                                    } else {
                                        fetchBtn.prop('disabled', false).html(originalHtml);
                                        showCustomToast('error', 'Authentication Failed', 'Failed to authenticate with Wheat Distribution API. Please verify your API credentials in the .env file.');
                                    }
                                },
                                error: function(loginXhr) {
                                    fetchBtn.prop('disabled', false).html(originalHtml);
                                    
                                    let errorMessage = 'Failed to authenticate with Wheat Distribution API.';
                                    if (loginXhr.status === 401) {
                                        errorMessage = 'Invalid username or password. Please check your API credentials.';
                                    } else if (loginXhr.status === 0) {
                                        errorMessage = 'Unable to connect to the API server. Please check if the Wheat Distribution application is running.';
                                    }
                                    
                                    const toastMessage = loginXhr.status ? `${errorMessage} (Status: ${loginXhr.status})` : errorMessage;
                                    showCustomToast('error', 'Authentication Error', toastMessage);
                                }
                            });
                        }
                    });
                },
                preConfirm: () => {
                    const form = $('#addMemberForm');
                    if (!form[0].checkValidity()) {
                        form[0].reportValidity();
                        return false;
                    }
                    return Object.fromEntries(new FormData(form[0]));
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo e(route("lzc-members.store-ajax")); ?>',
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            ...result.value
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    confirmButtonColor: '#567AED'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'An error occurred';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message,
                                confirmButtonColor: '#567AED'
                            });
                        }
                    });
                }
            });
        });

        // View Member Button Click
        $(document).on('click', '.viewMemberBtn', function() {
            const memberId = $(this).data('member-id');
            
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: `/lzc-members/${memberId}/details`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const m = response.member;
                        const genderBadge = m.gender === 'male' ? '<span class="badge bg-primary">Male</span>' : 
                                          m.gender === 'female' ? '<span class="badge bg-info">Female</span>' : 
                                          '<span class="badge bg-secondary">Other</span>';
                        const statusBadge = m.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                        const verificationBadge = m.verification_status === 'verified' ? '<span class="badge bg-success">Verified</span>' :
                                                 m.verification_status === 'rejected' ? '<span class="badge bg-danger">Rejected</span>' :
                                                 '<span class="badge bg-warning">Pending</span>';
                        
                        Swal.fire({
                            title: 'Member Details',
                            html: `
                                <div class="text-left">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>CNIC:</strong></div>
                                        <div class="col-8">${m.cnic}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Full Name:</strong></div>
                                        <div class="col-8">${m.full_name}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Father/Husband Name:</strong></div>
                                        <div class="col-8">${m.father_husband_name}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Mobile Number:</strong></div>
                                        <div class="col-8">${m.mobile_number || 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Date of Birth:</strong></div>
                                        <div class="col-8">${m.date_of_birth || 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Gender:</strong></div>
                                        <div class="col-8">${genderBadge}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Start Date:</strong></div>
                                        <div class="col-8">${m.start_date || 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>End Date:</strong></div>
                                        <div class="col-8">${m.end_date || 'Ongoing'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Verification Status:</strong></div>
                                        <div class="col-8">${verificationBadge}</div>
                                    </div>
                                    ${m.rejection_reason ? `
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Rejection Reason:</strong></div>
                                        <div class="col-8">${m.rejection_reason}</div>
                                    </div>
                                    ` : ''}
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Status:</strong></div>
                                        <div class="col-8">${statusBadge}</div>
                                    </div>
                                </div>
                            `,
                            width: '700px',
                            confirmButtonColor: '#567AED'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load member details.',
                        confirmButtonColor: '#567AED'
                    });
                }
            });
        });

        // Verify Member Button Click
        $(document).on('click', '.verifyMemberBtn', function() {
            const memberId = $(this).data('member-id');
            
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: `/lzc-members/${memberId}/details`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const m = response.member;
                        const genderBadge = m.gender === 'male' ? '<span class="badge bg-primary">Male</span>' : 
                                          m.gender === 'female' ? '<span class="badge bg-info">Female</span>' : 
                                          '<span class="badge bg-secondary">Other</span>';
                        
                        // Format date for input field
                        function formatDateForInput(dateString) {
                            if (!dateString) return '';
                            if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
                                return dateString;
                            }
                            if (dateString.includes(' ')) {
                                return dateString.split(' ')[0];
                            }
                            try {
                                const date = new Date(dateString);
                                if (!isNaN(date.getTime())) {
                                    const year = date.getFullYear();
                                    const month = String(date.getMonth() + 1).padStart(2, '0');
                                    const day = String(date.getDate()).padStart(2, '0');
                                    return `${year}-${month}-${day}`;
                                }
                            } catch (e) {
                                console.error('Date parsing error:', e);
                            }
                            return '';
                        }
                        
                        const dobValue = formatDateForInput(m.date_of_birth);
                        const startDateValue = formatDateForInput(m.start_date);
                        const endDateValue = formatDateForInput(m.end_date);
                        
                        Swal.fire({
                            title: 'Verify Member',
                            html: `
                                <div class="text-left mb-3" style="text-align: left !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <p class="text-muted mb-0">Compare the form data with API data from Wheat Distribution System below each field.</p>
                                        <button type="button" class="btn btn-primary btn-sm" id="fetchVerifyApiDataBtn" title="Fetch data from Wheat Distribution System">
                                            <i class="ti-search"></i> Fetch API Data
                                        </button>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>CNIC:</strong></div>
                                        <div class="col-8" style="text-align: left;">
                                            <div class="position-relative">
                                                <input type="text" id="verifyCnic" class="form-control" value="${m.cnic}" readonly style="background-color: #f8f9fa;">
                                                <div id="verifyApiCnic" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>Full Name:</strong></div>
                                        <div class="col-8" style="text-align: left;">
                                            <div class="position-relative">
                                                <input type="text" id="verifyFullName" class="form-control" value="${m.full_name}" readonly style="background-color: #f8f9fa;">
                                                <div id="verifyApiFullName" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>Father/Husband Name:</strong></div>
                                        <div class="col-8" style="text-align: left;">
                                            <div class="position-relative">
                                                <input type="text" id="verifyFatherHusbandName" class="form-control" value="${m.father_husband_name}" readonly style="background-color: #f8f9fa;">
                                                <div id="verifyApiFatherHusbandName" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>Mobile:</strong></div>
                                        <div class="col-8" style="text-align: left;">
                                            <div class="position-relative">
                                                <input type="text" id="verifyMobile" class="form-control" value="${m.mobile_number || ''}" readonly style="background-color: #f8f9fa;">
                                                <div id="verifyApiMobile" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>Date of Birth:</strong></div>
                                        <div class="col-8" style="text-align: left;">
                                            <div class="position-relative">
                                                <input type="date" id="verifyDateOfBirth" class="form-control" value="${dobValue}" readonly style="background-color: #f8f9fa;">
                                                <div id="verifyApiDateOfBirth" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>Gender:</strong></div>
                                        <div class="col-8" style="text-align: left;">
                                            <div class="position-relative">
                                                <input type="text" id="verifyGender" class="form-control" value="${m.gender || ''}" readonly style="background-color: #f8f9fa;">
                                                <div id="verifyApiGender" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>Start Date:</strong></div>
                                        <div class="col-8" style="text-align: left;">${m.start_date || 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2" style="text-align: left;">
                                        <div class="col-4" style="text-align: left;"><strong>End Date:</strong></div>
                                        <div class="col-8" style="text-align: left;">${m.end_date || 'Ongoing'}</div>
                                    </div>
                                </div>
                                <div class="mb-3" style="text-align: left !important;">
                                    <label class="form-label" style="text-align: left !important; display: block;">Action <span class="text-danger">*</span></label>
                                    <select id="verifyAction" class="form-control" required style="text-align: left !important;">
                                        <option value="">Select Action</option>
                                        <option value="verify">Verify & Activate</option>
                                        <option value="reject">Reject</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="verificationRemarksDiv" style="display: none; text-align: left !important;">
                                    <label class="form-label" style="text-align: left !important; display: block;">Verification Remarks <span class="text-danger">*</span></label>
                                    <textarea id="verificationRemarks" class="form-control" rows="3" placeholder="Enter remarks for verification" style="text-align: left !important;"></textarea>
                                </div>
                                <div class="mb-3" id="rejectionReasonDiv" style="display: none; text-align: left !important;">
                                    <label class="form-label" style="text-align: left !important; display: block;">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea id="rejectionReason" class="form-control" rows="3" placeholder="Enter reason for rejection" style="text-align: left !important;"></textarea>
                                </div>
                            `,
                            width: '800px',
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#567AED',
                            cancelButtonColor: '#000000',
                            didOpen: () => {
                                // Fetch API Data Button Click
                                $('#fetchVerifyApiDataBtn').on('click', function() {
                                    const fetchBtn = $(this);
                                    const originalHtml = fetchBtn.html();
                                    fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                                    
                                    // Clear any previously displayed API data
                                    $('.api-data-container').hide();
                                    $('.api-data-value').text('');
                                    
                                    // Fetch API data
                                    fetchVerifyMemberApiData(m.cnic, function() {
                                        fetchBtn.prop('disabled', false).html(originalHtml);
                                    });
                                });
                                
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
                            },
                            preConfirm: () => {
                                const action = $('#verifyAction').val();
                                if (!action) {
                                    Swal.showValidationMessage('Please select an action');
                                    return false;
                                }
                                if (action === 'verify' && !$('#verificationRemarks').val().trim()) {
                                    Swal.showValidationMessage('Please enter verification remarks');
                                    return false;
                                }
                                if (action === 'reject' && !$('#rejectionReason').val().trim()) {
                                    Swal.showValidationMessage('Please enter a rejection reason');
                                    return false;
                                }
                                return {
                                    action: action,
                                    verification_remarks: action === 'verify' ? $('#verificationRemarks').val() : null,
                                    rejection_reason: action === 'reject' ? $('#rejectionReason').val() : null
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `/lzc-members/${memberId}/verify`,
                                    method: 'POST',
                                    data: {
                                        _token: '<?php echo e(csrf_token()); ?>',
                                        action: result.value.action,
                                        verification_remarks: result.value.verification_remarks,
                                        rejection_reason: result.value.rejection_reason
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: response.message,
                                                confirmButtonColor: '#567AED'
                                            }).then(() => {
                                                location.reload();
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        const message = xhr.responseJSON?.message || 'An error occurred';
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: message,
                                            confirmButtonColor: '#567AED'
                                        });
                                    }
                                });
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load member details.',
                        confirmButtonColor: '#567AED'
                    });
                }
            });
        });

        // Edit Member Button Click
        $(document).on('click', '.editMemberBtn', function() {
            const memberId = $(this).data('member-id');
            const verificationStatus = $(this).data('verification-status');
            
            // Check if member is verified - prevent editing
            if (verificationStatus === 'verified') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Edit Verified Member',
                    html: `
                        <p>This member has been verified and cannot be edited.</p>
                        <p class="text-muted">Only pending or rejected members can be edited.</p>
                    `,
                    confirmButtonColor: '#567AED'
                });
                return;
            }
            
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: `/lzc-members/${memberId}/details`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const m = response.member;
                        
                        // Double check verification status from API response
                        if (m.verification_status === 'verified') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Cannot Edit Verified Member',
                                html: `
                                    <p>This member has been verified and cannot be edited.</p>
                                    <p class="text-muted">Only pending or rejected members can be edited.</p>
                                `,
                                confirmButtonColor: '#567AED'
                            });
                            return;
                        }
                        
                        // Format dates properly for date input fields
                        function formatDateForInput(dateString) {
                            if (!dateString) return '';
                            // If it's already in YYYY-MM-DD format, return as is
                            if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
                                return dateString;
                            }
                            // If it's in datetime format (YYYY-MM-DD HH:MM:SS), extract date part
                            if (dateString.includes(' ')) {
                                return dateString.split(' ')[0];
                            }
                            // If it's in other format, try to parse it
                            try {
                                const date = new Date(dateString);
                                if (!isNaN(date.getTime())) {
                                    const year = date.getFullYear();
                                    const month = String(date.getMonth() + 1).padStart(2, '0');
                                    const day = String(date.getDate()).padStart(2, '0');
                                    return `${year}-${month}-${day}`;
                                }
                            } catch (e) {
                                console.error('Date parsing error:', e);
                            }
                            return '';
                        }
                        
                        const endDateValue = formatDateForInput(m.end_date);
                        const dobValue = formatDateForInput(m.date_of_birth);
                        const startDateValue = formatDateForInput(m.start_date);
                        
                        // Check if member is verified - if so, show read-only view
                        const isVerified = m.verification_status === 'verified';
                        const readonlyAttr = isVerified ? 'readonly style="background-color: #f8f9fa;"' : '';
                        const disabledAttr = isVerified ? 'disabled' : '';
                        const readonlyClass = isVerified ? 'readonly-field' : '';
                        
                        Swal.fire({
                            title: isVerified ? 'View Member (Verified - Cannot Edit)' : 'Edit Member',
                            html: `
                                ${isVerified ? '<div class="alert alert-warning mb-3"><i class="ti-info-alt"></i> This member has been verified and cannot be edited.</div>' : ''}
                                <form id="editMemberForm" style="text-align: left;">
                                    <input type="hidden" name="local_zakat_committee_id" value="${m.local_zakat_committee_id}">
                                    ${!isVerified ? `
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <p class="text-muted mb-0">Fetch API data to compare and update fields.</p>
                                        <button type="button" class="btn btn-primary btn-sm" id="fetchEditApiDataBtn" title="Fetch data from Wheat Distribution System">
                                            <i class="ti-search"></i> Fetch API Data
                                        </button>
                                    </div>
                                    ` : ''}
                                    <div class="row" style="text-align: left;">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">CNIC <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input type="text" name="cnic" id="editMemberCnic" class="form-control ${readonlyClass}" value="${m.cnic}" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" required ${readonlyAttr}>
                                                <div id="editApiCnic" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="editMemberCnic" title="Copy to form field">
                                                        <i class="ti-check"></i> Copy
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input type="text" name="full_name" id="editFullName" class="form-control ${readonlyClass}" value="${m.full_name}" required ${readonlyAttr}>
                                                ${!isVerified ? `
                                                <div id="editApiFullName" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="editFullName" title="Copy to form field">
                                                        <i class="ti-check"></i> Copy
                                                    </button>
                                                </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input type="text" name="father_husband_name" id="editFatherHusbandName" class="form-control ${readonlyClass}" value="${m.father_husband_name}" required ${readonlyAttr}>
                                                ${!isVerified ? `
                                                <div id="editApiFatherHusbandName" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="editFatherHusbandName" title="Copy to form field">
                                                        <i class="ti-check"></i> Copy
                                                    </button>
                                                </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mobile Number</label>
                                            <div class="position-relative">
                                                <input type="text" name="mobile_number" id="editMemberMobile" class="form-control ${readonlyClass}" value="${m.mobile_number || ''}" placeholder="03XX-XXXXXXX" maxlength="12" ${readonlyAttr}>
                                                ${!isVerified ? `
                                                <div id="editApiMobileNumber" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="editMemberMobile" title="Copy to form field">
                                                        <i class="ti-check"></i> Copy
                                                    </button>
                                                </div>
                                                ` : ''}
                                            </div>
                                            <small class="text-muted">Format: 03XX-XXXXXXX</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input type="date" name="date_of_birth" id="editDateOfBirth" class="form-control ${readonlyClass}" value="${dobValue}" required ${readonlyAttr}>
                                                ${!isVerified ? `
                                                <div id="editApiDateOfBirth" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="editDateOfBirth" title="Copy to form field">
                                                        <i class="ti-check"></i> Copy
                                                    </button>
                                                </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <select name="gender" id="editGenderSelect" class="form-control ${readonlyClass}" required ${disabledAttr}>
                                                    <option value="">Select Gender</option>
                                                    <option value="male" ${m.gender === 'male' ? 'selected' : ''}>Male</option>
                                                    <option value="female" ${m.gender === 'female' ? 'selected' : ''}>Female</option>
                                                    <option value="other" ${m.gender === 'other' ? 'selected' : ''}>Other</option>
                                                </select>
                                                ${!isVerified ? `
                                                <div id="editApiGender" class="api-data-container" style="display: none;">
                                                    <div class="api-data-value"></div>
                                                    <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="editGenderSelect" data-is-select="true" title="Copy to form field">
                                                        <i class="ti-check"></i> Copy
                                                    </button>
                                                </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" name="start_date" class="form-control ${readonlyClass}" value="${startDateValue}" required ${readonlyAttr}>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">End Date</label>
                                            <input type="date" name="end_date" class="form-control ${readonlyClass}" value="${endDateValue}" ${readonlyAttr}>
                                            <small class="text-muted">Leave empty if tenure is ongoing</small>
                                        </div>
                                    </div>
                                </form>
                            `,
                            width: '800px',
                            showCancelButton: true,
                            confirmButtonText: isVerified ? 'Close' : 'Update Member',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#567AED',
                            cancelButtonColor: '#000000',
                            didOpen: () => {
                                // If verified, disable all interactions
                                if (isVerified) {
                                    // Hide confirm button or make it just close
                                    return;
                                }
                                
                                // CNIC formatting
                                $('#editMemberCnic').on('input', function() {
                                    $(this).val(formatCNIC($(this).val()));
                                });
                                
                                // Mobile formatting
                                $('#editMemberMobile').on('input', function() {
                                    $(this).val(formatMobileNumber($(this).val()));
                                });
                                
                                // Fetch API Data Button Click
                                $('#fetchEditApiDataBtn').on('click', function() {
                                    const fetchBtn = $(this);
                                    const originalHtml = fetchBtn.html();
                                    fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                                    
                                    // Clear any previously displayed API data
                                    $('.api-data-container').hide();
                                    $('.api-data-value').text('');
                                    
                                    // Get CNIC from form
                                    const cnic = $('#editMemberCnic').val().trim();
                                    
                                    if (!cnic) {
                                        fetchBtn.prop('disabled', false).html(originalHtml);
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'CNIC Required',
                                            text: 'Please enter a CNIC first.',
                                            confirmButtonColor: '#567AED'
                                        });
                                        return;
                                    }
                                    
                                    // Fetch API data
                                    fetchEditMemberApiData(cnic, function() {
                                        fetchBtn.prop('disabled', false).html(originalHtml);
                                    });
                                });
                            },
                            preConfirm: () => {
                                // If verified, just close the modal
                                if (isVerified) {
                                    return false;
                                }
                                
                                const form = $('#editMemberForm');
                                if (!form[0].checkValidity()) {
                                    form[0].reportValidity();
                                    return false;
                                }
                                const formData = Object.fromEntries(new FormData(form[0]));
                                return formData;
                            }
                        }).then((result) => {
                            // If verified member, just close without saving
                            if (isVerified) {
                                return;
                            }
                            
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `/lzc-members/${memberId}/update-ajax`,
                                    method: 'POST',
                                    data: {
                                        _token: '<?php echo e(csrf_token()); ?>',
                                        _method: 'PUT',
                                        ...result.value
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: response.message,
                                                confirmButtonColor: '#567AED'
                                            }).then(() => {
                                                location.reload();
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        const errors = xhr.responseJSON?.errors;
                                        let errorMessage = 'An error occurred. Please check your input.';
                                        if (errors) {
                                            errorMessage = Object.values(errors).flat().join('<br>');
                                        } else if (xhr.responseJSON?.message) {
                                            errorMessage = xhr.responseJSON.message;
                                        }
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Validation Error',
                                            html: errorMessage,
                                            confirmButtonColor: '#567AED'
                                        });
                                    }
                                });
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load member details.',
                        confirmButtonColor: '#567AED'
                    });
                }
            });
        });

        // Delete Member Button Click
        $(document).on('click', '.deleteMemberBtn', function() {
            const memberId = $(this).data('member-id');
            const memberName = $(this).data('member-name');
            
            Swal.fire({
                title: 'Delete Member',
                text: `Are you sure you want to delete "${memberName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#567AED',
                cancelButtonColor: '#000000'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/lzc-members/${memberId}/delete-ajax`,
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message || 'Member deleted successfully.',
                                    confirmButtonColor: '#567AED'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Failed to delete member.',
                                    confirmButtonColor: '#567AED'
                                });
                            }
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'An error occurred while deleting the member.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message,
                                confirmButtonColor: '#567AED'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/local-zakat-committees/show.blade.php ENDPATH**/ ?>