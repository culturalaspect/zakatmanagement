<?php $__env->startSection('title', config('app.name') . ' - Beneficiary Applications'); ?>
<?php $__env->startSection('page_title', 'Beneficiary Applications'); ?>
<?php $__env->startSection('breadcrumb', 'Beneficiary Applications'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* DataTables Buttons Styling */
    .dt-buttons {
        margin-bottom: 15px;
    }
    
    .dt-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .dt-buttons .btn i {
        margin-right: 5px;
    }
    
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
    
    /* Make table rows hoverable */
    #beneficiariesTable tbody tr {
        cursor: default;
    }
    
    #beneficiariesTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Custom Searchable Select Styles */
    .custom-select-option:hover {
        background-color: #f8f9fa;
    }
    
    .custom-select-option.selected {
        background-color: #e7f3ff;
        font-weight: 500;
    }
    
    .custom-select-display:hover {
        border-color: #86b7fe;
    }
    
    .custom-select-display.active {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
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
    
    /* Family Tree Grid Styles - Tree Structure */
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
        gap: 15px;
    }
    
    .family-tree-children {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        padding-top: 10px;
        position: relative;
    }
    
    .family-tree-children::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 10px;
        background-color: #6c757d;
    }
    
    .family-tree-node {
        position: relative;
        width: 140px;
        background: #fff;
        border: 2px solid #ced4da;
        border-radius: 6px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    
    .family-tree-node:hover {
        border-color: #567AED;
        box-shadow: 0 2px 6px rgba(86, 122, 237, 0.3);
        transform: translateY(-1px);
    }
    
    .family-tree-node.selected {
        border-color: #28a745;
        background-color: #d4edda;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
    }
    
    .family-tree-node.selected::after {
        content: '✓';
        position: absolute;
        top: -8px;
        right: -8px;
        background: #28a745;
        color: #fff;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }
    
    .family-tree-node.root-node {
        border-color: #ffc107;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        font-weight: 600;
    }
    
    .family-tree-node.root-node::before {
        content: "👑";
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #ffc107;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }
    
    .family-tree-node.child-node {
        border-color: #17a2b8;
        background: linear-gradient(135deg, #e7f3ff 0%, #d1ecf1 100%);
    }
    
    .family-tree-node-header {
        margin-bottom: 6px;
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
    
    .family-tree-connection {
        position: absolute;
        width: 2px;
        background-color: #ced4da;
        left: 50%;
        top: -15px;
        height: 15px;
        transform: translateX(-50%);
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

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Manage Beneficiaries - <?php echo e($phase->name); ?></h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('beneficiary-applications.index')); ?>" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back to Applications
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <!-- Phase Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Phase Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phase Name:</strong>
                        <p><strong class="text-primary"><?php echo e($phase->name); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phase Number:</strong>
                        <p><?php echo e($phase->phase_number); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            <?php if($phase->status == 'open'): ?>
                                <span class="badge bg-success">Open</span>
                            <?php elseif($phase->status == 'closed'): ?>
                                <span class="badge bg-warning">Closed</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($phase->status)); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p><?php echo e($phase->district->name ?? 'N/A'); ?></p>
                    </div>
                    <?php if($phase->scheme): ?>
                    <div class="col-md-6 mb-3">
                        <strong>Scheme:</strong>
                        <p><?php echo e($phase->scheme->name ?? 'N/A'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Installment Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Installment Information</h5>
                    </div>
                    <?php if($phase->installment): ?>
                    <div class="col-md-6 mb-3">
                        <strong>Installment Number:</strong>
                        <p>Installment <?php echo e($phase->installment->installment_number); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Financial Year:</strong>
                        <p><?php echo e($phase->installment->fundAllocation->financialYear->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Installment Amount:</strong>
                        <p><strong class="text-success">Rs. <?php echo e(number_format($phase->installment->installment_amount, 2)); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Installment Date:</strong>
                        <p><?php echo e($phase->installment->installment_date ? \Carbon\Carbon::parse($phase->installment->installment_date)->format('d M Y') : 'N/A'); ?></p>
                    </div>
                    <?php else: ?>
                    <div class="col-md-12">
                        <p class="text-muted">No installment information available</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Phase Limits -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Phase Limits</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Max Beneficiaries:</strong>
                        <p><strong class="text-primary"><?php echo e(number_format($phase->max_beneficiaries)); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Max Amount:</strong>
                        <p><strong class="text-success">Rs. <?php echo e(number_format($phase->max_amount, 2)); ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Start Date:</strong>
                        <p><?php echo e($phase->start_date ? \Carbon\Carbon::parse($phase->start_date)->format('d M Y') : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>End Date:</strong>
                        <p><?php echo e($phase->end_date ? \Carbon\Carbon::parse($phase->end_date)->format('d M Y') : 'N/A'); ?></p>
                    </div>
                </div>

                <!-- Beneficiaries Section -->
                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Beneficiaries (<span id="beneficiariesCount"><?php echo e($beneficiaries->count() ?? 0); ?></span>)</h5>
                    <?php if($phase->status == 'open' && $currentBeneficiariesCount < $phase->max_beneficiaries && $currentAmount < $phase->max_amount): ?>
                    <button type="button" class="btn btn-sm btn-success" id="addBeneficiaryBtn">
                        <i class="ti-plus"></i> Add Beneficiary
                    </button>
                    <?php endif; ?>
                </div>
                <div id="beneficiariesTableContainer">
                    <?php if(isset($beneficiaries) && $beneficiaries->count() > 0): ?>
                    <!-- Filters Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="ti-filter"></i> Filters
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Scheme</label>
                                            <select id="filterScheme" class="form-control form-control-sm">
                                                <option value="">All Schemes</option>
                                                <?php $__currentLoopData = $uniqueSchemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($scheme); ?>"><?php echo e($scheme); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Category</label>
                                            <select id="filterCategory" class="form-control form-control-sm">
                                                <option value="">All Categories</option>
                                                <?php $__currentLoopData = $uniqueCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Local Zakat Committee</label>
                                            <select id="filterCommittee" class="form-control form-control-sm">
                                                <option value="">All Committees</option>
                                                <?php $__currentLoopData = $uniqueCommittees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($committee); ?>"><?php echo e($committee); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Status</label>
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Statuses</option>
                                                <?php $__currentLoopData = $uniqueStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e(ucfirst($status)); ?>"><?php echo e(ucfirst($status)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Search</label>
                                            <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="Search by CNIC, Name...">
                                        </div>
                                        <div class="col-md-6 mb-3 d-flex align-items-end">
                                            <button type="button" id="clearFilters" class="btn btn-sm btn-secondary">
                                                <i class="ti-close"></i> Clear Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="beneficiariesTable">
                            <thead>
                                <tr>
                                    <th>CNIC</th>
                                    <th>Full Name</th>
                                    <th>Scheme</th>
                                    <th>Category</th>
                                    <th>Local Zakat Committee</th>
                                    <th>Institution</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="beneficiariesTableBody">
                                <?php $__currentLoopData = $beneficiaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficiary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-beneficiary-id="<?php echo e($beneficiary->id); ?>">
                                    <td><strong><?php echo e($beneficiary->cnic ?? 'N/A'); ?></strong></td>
                                    <td><?php echo e($beneficiary->full_name ?? 'N/A'); ?></td>
                                    <td><?php echo e($beneficiary->scheme->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($beneficiary->schemeCategory->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($beneficiary->localZakatCommittee->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($beneficiary->institution->name ?? 'N/A'); ?></td>
                                    <td>Rs. <?php echo e(number_format($beneficiary->amount ?? 0, 2)); ?></td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="action_btns d-flex">
                                            <button type="button" class="action_btn mr_10 viewBeneficiaryBtn" 
                                                    data-beneficiary-id="<?php echo e($beneficiary->id); ?>" 
                                                    title="View Details">
                                                <i class="ti-eye"></i>
                                            </button>
                                            <?php if($beneficiary->status == 'pending' || $beneficiary->status == 'rejected'): ?>
                                            <button type="button" class="action_btn mr_10 editBeneficiaryBtn" 
                                                    data-beneficiary-id="<?php echo e($beneficiary->id); ?>" 
                                                    title="Edit">
                                                <i class="ti-pencil"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php if($beneficiary->status == 'pending' || $beneficiary->status == 'submitted'): ?>
                                            <button type="button" class="action_btn mr_10 verifyBeneficiaryBtn" 
                                                    data-beneficiary-id="<?php echo e($beneficiary->id); ?>" 
                                                    title="Verify/Submit">
                                                <i class="ti-check"></i>
                                            </button>
                                            <?php endif; ?>
                                            <button type="button" class="action_btn mr_10 deleteBeneficiaryBtn" 
                                                    data-beneficiary-id="<?php echo e($beneficiary->id); ?>" 
                                                    data-beneficiary-name="<?php echo e($beneficiary->full_name); ?>" 
                                                    title="Delete">
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
                        <p class="mb-0">No beneficiaries have been added to this phase yet.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<!-- DataTables Buttons JS -->
<script src="<?php echo e(asset('assets/vendors/datatable/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/vfs_fonts.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const phaseId = <?php echo e($phase->id); ?>;
        const phaseSchemeId = <?php echo e($phase->scheme_id ?? 0); ?>;
        const phaseSchemeName = <?php echo json_encode($phase->scheme->name ?? 'N/A', 15, 512) ?>;
        const phaseScheme = <?php echo json_encode($phase->scheme ?? null, 15, 512) ?>;
        const phaseDistrictId = <?php echo e($phase->district_id ?? 0); ?>;
        const schemes = <?php echo json_encode($schemes ?? [], 15, 512) ?>;
        const committees = <?php echo json_encode($committees ?? [], 15, 512) ?>.filter(c => c.district?.id == phaseDistrictId);
        const institutions = <?php echo json_encode($institutions ?? [], 15, 512) ?>.filter(i => i.district?.id == phaseDistrictId);
        const isInstitutionUser = <?php echo e(auth()->user()->isInstitutionUser() ? 'true' : 'false'); ?>;
        const currentInstitution = <?php echo json_encode(auth()->user()->institution ?? null, 15, 512) ?>;
        
        // Initialize DataTable for beneficiaries if table exists
        let beneficiariesTable;
        if ($('#beneficiariesTable').length) {
            beneficiariesTable = $('#beneficiariesTable').DataTable({
                responsive: true,
                pageLength: 25,
                scrollX: true,
                scrollCollapse: true,
                order: [[1, 'asc']],
                autoWidth: false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="ti-files"></i> Copy',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude Actions column
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="ti-file"></i> CSV',
                        className: 'btn btn-sm btn-secondary',
                        filename: 'phase-' + phaseId + '-beneficiaries-' + new Date().toISOString().split('T')[0],
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="ti-file"></i> Excel',
                        className: 'btn btn-sm btn-success',
                        filename: 'phase-' + phaseId + '-beneficiaries-' + new Date().toISOString().split('T')[0],
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="ti-file"></i> PDF',
                        className: 'btn btn-sm btn-danger',
                        filename: 'phase-' + phaseId + '-beneficiaries-' + new Date().toISOString().split('T')[0],
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ti-printer"></i> Print',
                        className: 'btn btn-sm btn-info',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries to show",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });

            // Apply filters
            $('#filterScheme').on('change', function() {
                beneficiariesTable.column(2).search(this.value).draw();
            });

            $('#filterCategory').on('change', function() {
                beneficiariesTable.column(3).search(this.value).draw();
            });

            $('#filterCommittee').on('change', function() {
                beneficiariesTable.column(4).search(this.value).draw();
            });

            $('#filterStatus').on('change', function() {
                beneficiariesTable.column(6).search(this.value).draw();
            });

            $('#filterSearch').on('keyup', function() {
                beneficiariesTable.search(this.value).draw();
            });

            // Clear filters button
            $('#clearFilters').on('click', function() {
                $('#filterScheme').val('').trigger('change');
                $('#filterCategory').val('').trigger('change');
                $('#filterCommittee').val('').trigger('change');
                $('#filterStatus').val('').trigger('change');
                $('#filterSearch').val('');
                beneficiariesTable.search('').columns().search('').draw();
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
        
        // Fetch Beneficiary Data from API
        function fetchBeneficiaryData(cnic, callback) {
            // API Configuration
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            // Function to display API data
            function displayApiData(data) {
                // Function to show API data for a field
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
            
            // If token is available, use it directly
            if (apiToken) {
                fetchMemberData(apiToken);
            } else {
                // Try to login first to get token
                const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
                const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
                
                if (!apiUsername || !apiPassword) {
                    if (callback) callback();
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
                            fetchMemberData(loginResponse.data.access_token);
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

        // Custom Searchable Select Functions
        function initializeCustomSelect() {
            const $display = $('#lzc_select_display');
            const $dropdown = $('#lzc_select_dropdown');
            const $hiddenInput = $('#add_local_zakat_committee_id');
            const $searchInput = $('#lzc_search_input');
            const $options = $('.custom-select-option');
            
            // Toggle dropdown
            $display.on('click', function(e) {
                e.stopPropagation();
                const isVisible = $dropdown.is(':visible');
                $dropdown.toggle();
                if (!isVisible) {
                    $display.addClass('active');
                    $searchInput.focus();
                } else {
                    $display.removeClass('active');
                }
            });
            
            // Search functionality
            $searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $options.each(function() {
                    const text = $(this).data('text').toLowerCase();
                    if (text.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            
            // Select option
            $options.on('click', function(e) {
                e.stopPropagation();
                const value = $(this).data('value');
                const text = $(this).data('text');
                
                $hiddenInput.val(value);
                $display.find('.select-placeholder').text(text).css('color', '#000');
                $display.removeClass('active');
                $dropdown.hide();
                $searchInput.val('');
                
                // Update selected state
                $options.removeClass('selected');
                $(this).addClass('selected');
                
                // Show all options again
                $options.show();
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.custom-searchable-select').length) {
                    $dropdown.hide();
                    $display.removeClass('active');
                }
            });
            
            // Prevent closing when clicking inside dropdown
            $dropdown.on('click', function(e) {
                e.stopPropagation();
            });
        }

        // Add Beneficiary Button Click
        $('#addBeneficiaryBtn').on('click', function() {
            showAddBeneficiaryModal();
        });

        function showAddBeneficiaryModal() {
            // Check if scheme is institutional
            const isInstitutional = phaseScheme?.is_institutional || false;
            const institutionalType = phaseScheme?.institutional_type || null;
            const beneficiaryRequiredFields = phaseScheme?.beneficiary_required_fields || [];
            const allowsRepresentative = phaseScheme?.allows_representative || false;
            const hasAgeRestriction = phaseScheme?.has_age_restriction || false;
            const minimumAge = phaseScheme?.minimum_age || 0;
            
            // Filter committees to only show those from phase's district
            const filteredCommittees = committees.filter(c => c.district?.id == phaseDistrictId);

            let committeeOptions = '';
            filteredCommittees.forEach(function(committee) {
                const displayText = `${committee.name} [${committee.code ?? 'N/A'}] - ${committee.district?.name ?? 'N/A'}`;
                committeeOptions += `<div class="custom-select-option" data-value="${committee.id}" data-text="${displayText}" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0;">${displayText}</div>`;
            });
            
            // Filter institutions based on institutional type
            let filteredInstitutions = [];
            if (isInstitutional) {
                if (institutionalType === 'educational') {
                    filteredInstitutions = institutions.filter(i => 
                        ['middle_school', 'high_school', 'college', 'university'].includes(i.type)
                    );
                } else if (institutionalType === 'madarsa') {
                    filteredInstitutions = institutions.filter(i => i.type === 'madarsa');
                } else if (institutionalType === 'health') {
                    filteredInstitutions = institutions.filter(i => i.type === 'hospital');
                }
            }
            
            let institutionOptions = '';
            filteredInstitutions.forEach(function(institution) {
                const displayText = `${institution.name} [${institution.code ?? 'N/A'}] - ${institution.district?.name ?? 'N/A'}`;
                institutionOptions += `<div class="custom-select-option" data-value="${institution.id}" data-text="${displayText}" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0;">${displayText}</div>`;
            });

            Swal.fire({
                title: 'Add New Beneficiary',
                html: `
                    <form id="addBeneficiaryForm" style="text-align: left;">
                        <input type="hidden" name="phase_id" value="${phaseId}">
                        <input type="hidden" name="scheme_id" id="add_scheme_id" value="${phaseSchemeId}">
                        
                        <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Scheme Information</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label"><strong>Scheme:</strong></label>
                                        <p class="mb-0">${phaseSchemeName}</p>
                                    </div>
                                    <div class="col-md-12 mb-3" id="add_schemeCategoryDiv" style="display: none;">
                                        <label class="form-label">Scheme Category <span class="text-danger">*</span></label>
                                        <select name="scheme_category_id" id="add_scheme_category_id" class="form-control">
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${isInstitutional ? `
                        <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Institution</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        ${isInstitutionUser && currentInstitution ? `
                                            <label class="form-label">Institution</label>
                                            <p class="mb-0"><strong>${currentInstitution.name} [${currentInstitution.code ?? 'N/A'}] - ${currentInstitution.district?.name ?? 'N/A'}</strong></p>
                                            <input type="hidden" name="institution_id" id="add_institution_id" value="${currentInstitution.id}">
                                        ` : `
                                            <label class="form-label">Institution <span class="text-danger">*</span></label>
                                            <div class="custom-searchable-select" style="position: relative;">
                                                <input type="hidden" name="institution_id" id="add_institution_id" required>
                                                <div class="custom-select-display" id="institution_select_display" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem; background-color: #fff; cursor: pointer; min-height: 38px; display: flex; align-items: center;">
                                                    <span class="select-placeholder" style="color: #6c757d;">Select Institution</span>
                                                    <span class="select-arrow" style="margin-left: auto;">▼</span>
                                                </div>
                                                <div class="custom-select-dropdown" id="institution_select_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ced4da; border-radius: 0.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 1000; max-height: 300px; overflow-y: auto; margin-top: 2px;">
                                                    <div class="custom-select-search" style="padding: 8px; border-bottom: 1px solid #dee2e6;">
                                                        <input type="text" id="institution_search_input" class="form-control form-control-sm" placeholder="Search institution..." autocomplete="off">
                                                    </div>
                                                    <div class="custom-select-options" id="institution_select_options" style="max-height: 250px; overflow-y: auto;">
                                                        ${institutionOptions}
                                                    </div>
                                                </div>
                                            </div>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : `
                        <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Local Zakat Committee Selection</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Local Zakat Committee <span class="text-danger">*</span></label>
                                        <div class="custom-searchable-select" style="position: relative;">
                                            <input type="hidden" name="local_zakat_committee_id" id="add_local_zakat_committee_id" required>
                                            <div class="custom-select-display" id="lzc_select_display" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem; background-color: #fff; cursor: pointer; min-height: 38px; display: flex; align-items: center;">
                                                <span class="select-placeholder" style="color: #6c757d;">Select Committee</span>
                                                <span class="select-arrow" style="margin-left: auto;">▼</span>
                                            </div>
                                            <div class="custom-select-dropdown" id="lzc_select_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ced4da; border-radius: 0.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 1000; max-height: 300px; overflow-y: auto; margin-top: 2px;">
                                                <div class="custom-select-search" style="padding: 8px; border-bottom: 1px solid #dee2e6;">
                                                    <input type="text" id="lzc_search_input" class="form-control form-control-sm" placeholder="Search committee..." autocomplete="off">
                                                </div>
                                                <div class="custom-select-options" id="lzc_select_options" style="max-height: 250px; overflow-y: auto;">
                                                    ${committeeOptions}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `}

                        <hr>
                        <h6>Beneficiary Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CNIC <span class="text-danger" id="add_cnicRequired" style="display: ${beneficiaryRequiredFields.includes('cnic') ? 'inline' : 'none'};">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="cnic" id="add_cnic" class="form-control" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" ${beneficiaryRequiredFields.includes('cnic') ? 'required' : ''}>
                                    <button type="button" class="btn btn-primary" id="fetchBeneficiaryDetailsBtn" title="Fetch details from Wheat Distribution System">
                                        <i class="ti-search"></i> Fetch Details
                                    </button>
                                </div>
                                <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger" id="add_full_nameRequired" style="display: ${beneficiaryRequiredFields.includes('full_name') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="full_name" id="add_full_name" class="form-control" ${beneficiaryRequiredFields.includes('full_name') ? 'required' : ''}>
                                    <div id="apiFullName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_full_name" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father/Husband Name <span class="text-danger" id="add_father_husband_nameRequired" style="display: ${beneficiaryRequiredFields.includes('father_husband_name') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="father_husband_name" id="add_father_husband_name" class="form-control" ${beneficiaryRequiredFields.includes('father_husband_name') ? 'required' : ''}>
                                    <div id="apiFatherHusbandName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_father_husband_name" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number <span class="text-danger" id="add_mobile_numberRequired" style="display: ${beneficiaryRequiredFields.includes('mobile_number') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="mobile_number" id="add_mobile_number" class="form-control" placeholder="03XX-XXXXXXX" maxlength="12" ${beneficiaryRequiredFields.includes('mobile_number') ? 'required' : ''}>
                                    <div id="apiMobileNumber" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_mobile_number" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Format: 03XX-XXXXXXX</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger" id="add_date_of_birthRequired" style="display: ${beneficiaryRequiredFields.includes('date_of_birth') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <input type="date" name="date_of_birth" id="add_date_of_birth" class="form-control" ${beneficiaryRequiredFields.includes('date_of_birth') ? 'required' : ''}>
                                    <div id="apiDateOfBirth" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_date_of_birth" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender <span class="text-danger" id="add_genderRequired" style="display: ${beneficiaryRequiredFields.includes('gender') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <select name="gender" id="add_gender" class="form-control" ${beneficiaryRequiredFields.includes('gender') ? 'required' : ''}>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div id="apiGender" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_gender" data-is-select="true" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            ${institutionalType === 'educational' ? `
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class <span class="text-danger">*</span></label>
                                <input type="text" name="class" id="add_class" class="form-control" placeholder="e.g., 5th, 10th, 1st Year" required>
                            </div>
                            ` : ''}
                            <div class="col-md-6 mb-3" id="add_amountDiv">
                                <label class="form-label">Amount <span class="text-danger" id="add_amountRequired" style="display: none;">*</span></label>
                                <input type="number" name="amount" id="add_amount" class="form-control" step="0.01" min="0" readonly>
                                <small class="text-muted" id="add_amountHint">Amount will be auto-filled from category</small>
                            </div>
                        </div>
                        <div id="add_representativeDiv" style="display: none;">
                            <hr>
                            <h6>Representative Information</h6>
                            <div class="alert alert-info mb-3" role="alert">
                                <i class="ti-info-alt"></i> <strong>Note:</strong> For beneficiaries below 18 years, a representative (above 18) is required. You can fetch family members from the Wheat Distribution System or enter details manually.
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary btn-sm" id="fetchFamilyMembersBtn" title="Fetch family members from Wheat Distribution System">
                                        <i class="ti-search"></i> Fetch Family Members
                                    </button>
                                    <div id="familyTreeContainer" style="display: none; margin-top: 15px;">
                                        <label class="form-label mb-2"><strong>Select Family Member (Click on any node):</strong></label>
                                        <div id="familyTreeGrid" class="family-tree-grid"></div>
                                        <small class="text-muted d-block mt-2">Click on any family member node to auto-fill representative details</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CNIC <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="representative[cnic]" id="add_rep_cnic" class="form-control" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}">
                                        <button type="button" class="btn btn-primary" id="fetchRepDetailsBtn" title="Fetch details from Wheat Distribution System">
                                            <i class="ti-search"></i> Fetch Details
                                        </button>
                                    </div>
                                    <div class="position-relative" style="margin-top: 5px;">
                                        <div id="apiRepCnic" class="api-data-container" style="display: none;">
                                            <div class="api-data-value"></div>
                                            <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_rep_cnic" title="Copy to form field">
                                                <i class="ti-check"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" name="representative[full_name]" id="add_rep_full_name" class="form-control">
                                        <div id="apiRepFullName" class="api-data-container" style="display: none;">
                                            <div class="api-data-value"></div>
                                            <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_rep_full_name" title="Copy to form field">
                                                <i class="ti-check"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" name="representative[father_husband_name]" id="add_rep_father_husband_name" class="form-control">
                                        <div id="apiRepFatherHusbandName" class="api-data-container" style="display: none;">
                                            <div class="api-data-value"></div>
                                            <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_rep_father_husband_name" title="Copy to form field">
                                                <i class="ti-check"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <div class="position-relative">
                                        <input type="text" name="representative[mobile_number]" id="add_rep_mobile_number" class="form-control" placeholder="03XX-XXXXXXX" maxlength="12">
                                        <div id="apiRepMobileNumber" class="api-data-container" style="display: none;">
                                            <div class="api-data-value"></div>
                                            <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_rep_mobile_number" title="Copy to form field">
                                                <i class="ti-check"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Format: 03XX-XXXXXXX</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="date" name="representative[date_of_birth]" id="add_rep_date_of_birth" class="form-control">
                                        <div id="apiRepDateOfBirth" class="api-data-container" style="display: none;">
                                            <div class="api-data-value"></div>
                                            <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_rep_date_of_birth" title="Copy to form field">
                                                <i class="ti-check"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <select name="representative[gender]" id="add_rep_gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div id="apiRepGender" class="api-data-container" style="display: none;">
                                            <div class="api-data-value"></div>
                                            <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_rep_gender" data-is-select="true" title="Copy to form field">
                                                <i class="ti-check"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" name="representative[relationship]" id="add_rep_relationship" class="form-control" placeholder="e.g., Father, Mother, Brother">
                                        <div id="apiRepRelationship" class="api-data-container" style="display: none;">
                                            <div class="api-data-value"></div>
                                            <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="add_rep_relationship" title="Copy to form field">
                                                <i class="ti-check"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                `,
                width: '800px',
                showCancelButton: true,
                confirmButtonText: 'Add Beneficiary',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#000000',
                customClass: {
                    cancelButton: 'btn btn-dark',
                    confirmButton: 'btn btn-primary'
                },
                didOpen: () => {
                    // Initialize custom searchable select for LZC or Institution based on scheme type
                    const isInstitutional = phaseScheme?.is_institutional || false;
                    if (isInstitutional) {
                        initCustomSelect('#institution_select_display', '#institution_select_dropdown', '#institution_search_input', '#institution_select_options', '#add_institution_id');
                    } else {
                        initializeCustomSelect();
                    }

                    // CNIC masking
                    $('#add_cnic').on('input', function() {
                        $(this).val(formatCNIC($(this).val()));
                    });
                    $('#add_rep_cnic').on('input', function() {
                        $(this).val(formatCNIC($(this).val()));
                    });
                    
                    // Fetch Details Button Click
                    $('#fetchBeneficiaryDetailsBtn').on('click', function() {
                        const fetchBtn = $(this);
                        const originalHtml = fetchBtn.html();
                        fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                        
                        // Clear any previously displayed API data
                        $('.api-data-container').hide();
                        $('.api-data-value').text('');
                        
                        // Get CNIC from form
                        const cnic = $('#add_cnic').val().trim();
                        
                        if (!cnic) {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'CNIC Required', 'Please enter a CNIC first.');
                            return;
                        }
                        
                        // Validate CNIC format (should be 13 digits)
                        const cnicDigits = cnic.replace(/\D/g, '');
                        if (cnicDigits.length !== 13) {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'Invalid CNIC', 'Please enter a valid CNIC (13 digits).');
                            return;
                        }
                        
                        // Fetch API data
                        fetchBeneficiaryData(cnic, function() {
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
                            // For select dropdowns, try to match the value
                            const genderMap = {
                                'Male': 'male',
                                'Female': 'female',
                                'Other': 'other'
                            };
                            const mappedValue = genderMap[originalValue] || originalValue.toLowerCase();
                            targetElement.val(mappedValue).trigger('change');
                        } else if (targetElement.attr('type') === 'date') {
                            // For date fields, use the original value (should be YYYY-MM-DD)
                            targetElement.val(originalValue);
                            // Trigger both change and input events to check age and show/hide representative form
                            targetElement.trigger('change').trigger('input');
                        } else {
                            // For text fields, use the original value
                            targetElement.val(originalValue);
                        }
                        
                        // Show feedback
                        const originalText = copyBtn.html();
                        copyBtn.html('<i class="ti-check"></i> Copied!').addClass('btn-success').removeClass('btn-success');
                        setTimeout(function() {
                            copyBtn.html(originalText);
                        }, 2000);
                    });

                    // Mobile number masking
                    $('#add_mobile_number').on('input', function() {
                        $(this).val(formatMobileNumber($(this).val()));
                    });
                    $('#add_rep_mobile_number').on('input', function() {
                        $(this).val(formatMobileNumber($(this).val()));
                    });

                    // Load scheme categories and configure amount field based on scheme type
                    // Always load categories via AJAX to ensure we have the latest data
                    if (phaseSchemeId) {
                        const isLumpSum = (phaseScheme && phaseScheme.is_lump_sum) ? true : false;
                        
                        $.ajax({
                            url: '<?php echo e(route("beneficiaries.scheme-categories", ":id")); ?>'.replace(':id', phaseSchemeId),
                            type: 'GET',
                            success: function(categories) {
                                const hasCategories = categories && categories.length > 0;
                                
                                if (hasCategories) {
                                    // Scheme has categories - show category dropdown
                                    const categorySelect = $('#add_scheme_category_id');
                                    categorySelect.empty();
                                    categorySelect.append('<option value="">Select Category</option>');
                                    
                                    categories.forEach(function(category) {
                                        categorySelect.append(`<option value="${category.id}" data-amount="${category.amount}">${category.name} (Rs. ${parseFloat(category.amount).toFixed(2)})</option>`);
                                    });
                                    
                                    // Show category div and configure amount field
                                    $('#add_schemeCategoryDiv').show();
                                    $('#add_amount').prop('required', false);
                                    $('#add_amount').prop('readonly', true);
                                    $('#add_amount').val('');
                                    $('#add_amountRequired').hide();
                                    $('#add_amountHint').text('Amount will be auto-filled from selected category').show();
                                    $('#add_amountDiv').show();
                                } else {
                                    // No categories - this is a lump sum scheme
                                    // Hide category div
                                    $('#add_schemeCategoryDiv').hide();
                                    
                                    // Lump sum scheme - amount field is editable and required
                                    $('#add_amount').prop('required', true);
                                    $('#add_amount').prop('readonly', false);
                                    $('#add_amount').val('');
                                    $('#add_amountRequired').show();
                                    $('#add_amountDiv').show();
                                    
                                    // Fetch remaining amount for lump sum scheme
                                    fetchLumpSumRemainingAmount(phaseId, phaseSchemeId);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error loading scheme categories:', error);
                                // On error, treat as lump sum
                                $('#add_schemeCategoryDiv').hide();
                                $('#add_amount').prop('required', true);
                                $('#add_amount').prop('readonly', false);
                                $('#add_amount').val('');
                                $('#add_amountRequired').show();
                                $('#add_amountHint').text('Enter the amount for this beneficiary').show();
                                $('#add_amountDiv').show();
                            }
                        });
                    }

                    // Auto-set amount when category is selected
                    $('#add_scheme_category_id').on('change', function() {
                        const selectedOption = $(this).find('option:selected');
                        const amount = selectedOption.data('amount');
                        if (amount) {
                            $('#add_amount').val(amount);
                        } else {
                            $('#add_amount').val('');
                        }
                    });

                    // Calculate age from date of birth and auto-show/hide representative section
                    function checkAgeAndRequireRepresentative() {
                        const dob = $('#add_date_of_birth').val();
                        // Get scheme settings
                        const allowsRepresentative = phaseScheme?.allows_representative || false;
                        const hasAgeRestriction = phaseScheme?.has_age_restriction || false;
                        const minimumAge = phaseScheme?.minimum_age || 0;
                        
                        if (dob) {
                            const birthDate = new Date(dob);
                            const today = new Date();
                            let age = today.getFullYear() - birthDate.getFullYear();
                            const monthDiff = today.getMonth() - birthDate.getMonth();
                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }
                            
                            // Check scheme settings
                            if (age < 18) {
                                // Check if scheme allows representative
                                if (!allowsRepresentative) {
                                    // If scheme has age restriction and doesn't allow representative, < 18 beneficiaries are not eligible
                                    if (hasAgeRestriction && minimumAge >= 18) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Not Eligible',
                                            text: 'This scheme has age restriction (minimum age: ' + minimumAge + ') and does not allow representatives. Beneficiaries below 18 years are not eligible for this scheme.',
                                        });
                                        $('#add_date_of_birth').val('');
                                        $('#add_representativeDiv').hide();
                                        // Remove required from representative fields
                                        $('#add_rep_cnic').prop('required', false);
                                        $('#add_rep_full_name').prop('required', false);
                                        $('#add_rep_father_husband_name').prop('required', false);
                                        $('#add_rep_date_of_birth').prop('required', false);
                                        $('#add_rep_gender').prop('required', false);
                                        $('#add_rep_relationship').prop('required', false);
                                        return;
                                    }
                                    // If no age restriction or minimum age < 18, just hide representative section
                                    $('#add_representativeDiv').hide();
                                    // Remove required from representative fields
                                    $('#add_rep_cnic').prop('required', false);
                                    $('#add_rep_full_name').prop('required', false);
                                    $('#add_rep_father_husband_name').prop('required', false);
                                    $('#add_rep_date_of_birth').prop('required', false);
                                    $('#add_rep_gender').prop('required', false);
                                    $('#add_rep_relationship').prop('required', false);
                                } else {
                                    // Scheme allows representative - show representative section and make fields required
                                    $('#add_representativeDiv').show();
                                    
                                    // Make representative fields required
                                    $('#add_rep_cnic').prop('required', true);
                                    $('#add_rep_full_name').prop('required', true);
                                    $('#add_rep_father_husband_name').prop('required', true);
                                    $('#add_rep_date_of_birth').prop('required', true);
                                    $('#add_rep_gender').prop('required', true);
                                    $('#add_rep_relationship').prop('required', true);
                                }
                            } else {
                                // Age is 18 or above - hide representative section and remove required
                                $('#add_representativeDiv').hide();
                                
                                // Remove required from representative fields
                                $('#add_rep_cnic').prop('required', false);
                                $('#add_rep_full_name').prop('required', false);
                                $('#add_rep_father_husband_name').prop('required', false);
                                $('#add_rep_date_of_birth').prop('required', false);
                                $('#add_rep_gender').prop('required', false);
                                $('#add_rep_relationship').prop('required', false);
                                
                                // Clear representative fields
                                $('#add_rep_cnic').val('');
                                $('#add_rep_full_name').val('');
                                $('#add_rep_father_husband_name').val('');
                                $('#add_rep_mobile_number').val('');
                                $('#add_rep_date_of_birth').val('');
                                $('#add_rep_gender').val('');
                                $('#add_rep_relationship').val('');
                            }
                        } else {
                            // No date of birth - hide representative section
                            $('#add_representativeDiv').hide();
                            
                            // Remove required from representative fields
                            $('#add_rep_cnic').prop('required', false);
                            $('#add_rep_full_name').prop('required', false);
                            $('#add_rep_father_husband_name').prop('required', false);
                            $('#add_rep_date_of_birth').prop('required', false);
                            $('#add_rep_gender').prop('required', false);
                            $('#add_rep_relationship').prop('required', false);
                        }
                    }

                    // Check age when date of birth changes
                    $('#add_date_of_birth').on('change', checkAgeAndRequireRepresentative);
                    
                    // Also check on input (for manual typing)
                    $('#add_date_of_birth').on('input', checkAgeAndRequireRepresentative);
                    
                    // Fetch Representative Details Button Click (Individual CNIC lookup)
                    $('#fetchRepDetailsBtn').on('click', function() {
                        const fetchBtn = $(this);
                        const originalHtml = fetchBtn.html();
                        fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                        
                        // Clear any previously displayed API data for representative
                        $('#apiRepCnic, #apiRepFullName, #apiRepFatherHusbandName, #apiRepMobileNumber, #apiRepDateOfBirth, #apiRepGender').hide();
                        $('#apiRepCnic .api-data-value, #apiRepFullName .api-data-value, #apiRepFatherHusbandName .api-data-value, #apiRepMobileNumber .api-data-value, #apiRepDateOfBirth .api-data-value, #apiRepGender .api-data-value').text('');
                        
                        // Get CNIC from form
                        const cnic = $('#add_rep_cnic').val().trim();
                        
                        if (!cnic) {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'CNIC Required', 'Please enter a CNIC first.');
                            return;
                        }
                        
                        // Validate CNIC format (should be 13 digits)
                        const cnicDigits = cnic.replace(/\D/g, '');
                        if (cnicDigits.length !== 13) {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'Invalid CNIC', 'Please enter a valid CNIC (13 digits).');
                            return;
                        }
                        
                        // Fetch API data for representative
                        fetchRepMemberData(cnic, function() {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                        });
                    });
                    
                    // Fetch Representative Member Data from API
                    function fetchRepMemberData(cnic, callback) {
                        const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
                        const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
                        const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
                        const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
                        
                        function displayRepApiData(data) {
                            function showRepApiData(containerId, value, displayValue = null) {
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
                            
                            showRepApiData('apiRepCnic', data.cnic);
                            showRepApiData('apiRepFullName', data.full_name);
                            showRepApiData('apiRepFatherHusbandName', data.father_husband_name);
                            showRepApiData('apiRepMobileNumber', data.mobile_number);
                            showRepApiData('apiRepDateOfBirth', data.date_of_birth, formatDateForDisplay(data.date_of_birth));
                            showRepApiData('apiRepGender', data.gender);
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
                                        displayRepApiData(response.data);
                                        showCustomToast('success', 'Member Found', 'Representative member data fetched. Review and copy to form fields.');
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
                    
                    // Fetch Family Members Button Click
                    $('#fetchFamilyMembersBtn').on('click', function() {
                        const fetchBtn = $(this);
                        const originalHtml = fetchBtn.html();
                        fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                        
                        // Get beneficiary CNIC
                        const beneficiaryCnic = $('#add_cnic').val().trim();
                        
                        if (!beneficiaryCnic) {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'Beneficiary CNIC Required', 'Please enter beneficiary CNIC first.');
                            return;
                        }
                        
                        // Validate CNIC format
                        const cnicDigits = beneficiaryCnic.replace(/\D/g, '');
                        if (cnicDigits.length !== 13) {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'Invalid CNIC', 'Please enter a valid beneficiary CNIC (13 digits).');
                            return;
                        }
                        
                        // Fetch family members
                        fetchFamilyMembers(beneficiaryCnic, function() {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                        });
                    });
                    
                    // Family Member Node Click Handler
                    $(document).on('click', '.family-tree-node', function() {
                        // Remove previous selection
                        $('.family-tree-node').removeClass('selected');
                        
                        // Add selection to clicked node
                        $(this).addClass('selected');
                        
                        // Get family member data
                        const familyMember = $(this).data('family-member');
                        if (!familyMember) {
                            return;
                        }
                        
                        // Auto-fill all representative fields
                        $('#add_rep_cnic').val(familyMember.cnic);
                        $('#add_rep_full_name').val(familyMember.full_name);
                        $('#add_rep_father_husband_name').val(familyMember.father_husband_name);
                        $('#add_rep_mobile_number').val(familyMember.mobile_number || '');
                        $('#add_rep_date_of_birth').val(familyMember.date_of_birth || '');
                        
                        // Set gender
                        const genderMap = {
                            'Male': 'male',
                            'Female': 'female',
                            'Other': 'other'
                        };
                        const genderValue = genderMap[familyMember.gender] || familyMember.gender.toLowerCase();
                        $('#add_rep_gender').val(genderValue).trigger('change');
                        
                        // Set relationship
                        $('#add_rep_relationship').val(familyMember.relationship || '');
                        
                        // Format CNIC and mobile if needed
                        if ($('#add_rep_cnic').val()) {
                            $('#add_rep_cnic').val(formatCNIC($('#add_rep_cnic').val()));
                        }
                        if ($('#add_rep_mobile_number').val()) {
                            $('#add_rep_mobile_number').val(formatMobileNumber($('#add_rep_mobile_number').val()));
                        }
                        
                        showCustomToast('success', 'Family Member Selected', 'Representative details have been auto-filled from the selected family member.');
                    });
                    
                    // Fetch Family Members from API
                    function fetchFamilyMembers(beneficiaryCnic, callback) {
                        const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
                        const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
                        const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
                        const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
                        
                        function fetchFamilyMembersFromAPI(token) {
                            $.ajax({
                                url: `${apiBaseUrl}/external/zakat/member/family-members`,
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${token}`,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                data: JSON.stringify({ cnic: beneficiaryCnic }),
                                contentType: 'application/json',
                                success: function(response) {
                                    if (callback) callback();
                                    if (response.success && response.data && response.data.length > 0) {
                                        // Display family tree in hierarchical structure
                                        displayFamilyTree(response.data);
                                        
                                        // Show tree grid
                                        $('#familyTreeContainer').show();
                                        
                                        showCustomToast('success', 'Family Members Found', `Found ${response.data.length} eligible family member(s). Click on any node to auto-fill representative details.`);
                                    } else {
                                        $('#familyTreeContainer').hide();
                                        showCustomToast('info', 'No Family Members Found', response.message || 'No eligible family members found. Please enter representative details manually.');
                                    }
                                },
                                error: function(xhr) {
                                    if (callback) callback();
                                    $('#familyTreeContainer').hide();
                                    let errorTitle = 'API Error';
                                    let errorMessage = 'Failed to fetch family members.';
                                    let errorIcon = 'error';
                                    
                                    if (xhr.status === 401) {
                                        errorTitle = 'Authentication Failed';
                                        errorMessage = 'Authentication failed. Please check API credentials.';
                                    } else if (xhr.status === 404) {
                                        const response = xhr.responseJSON;
                                        if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                                            errorTitle = 'Member Not Found';
                                            errorMessage = response.message || 'Beneficiary member not found in the system.';
                                            errorIcon = 'info';
                                        } else if (response && response.error_code === 'NO_HOUSEHOLD') {
                                            errorTitle = 'No Household';
                                            errorMessage = response.message || 'Beneficiary is not associated with any household.';
                                            errorIcon = 'info';
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
                            fetchFamilyMembersFromAPI(apiToken);
                        } else {
                            if (!apiUsername || !apiPassword) {
                                if (callback) callback();
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
                                        fetchFamilyMembersFromAPI(loginResponse.data.access_token);
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
                    
                    // Create Family Tree Node HTML (Compact Version)
                    function createFamilyTreeNode(member, isRoot) {
                        const nodeClass = isRoot ? 'family-tree-node root-node' : 'family-tree-node child-node';
                        const relationshipBadge = member.relationship || 'Family Member';
                        const ageBadge = member.age ? `${member.age} yrs` : 'N/A';
                        
                        const nodeHtml = `
                            <div class="${nodeClass}" data-family-member='${JSON.stringify(member)}'>
                                <div class="family-tree-node-header">
                                    <h6 class="family-tree-node-name" title="${member.full_name || 'N/A'}">${member.full_name || 'N/A'}</h6>
                                    <span class="family-tree-node-relationship">${relationshipBadge}</span>
                                </div>
                                <div class="family-tree-node-age">Age: ${ageBadge}</div>
                            </div>
                        `;
                        
                        return $(nodeHtml);
                    }
                    
                    // Display Family Tree in Hierarchical Structure
                    function displayFamilyTree(familyMembers) {
                        const treeGrid = $('#familyTreeGrid');
                        treeGrid.empty();
                        
                        // Get currently selected representative CNIC (if any)
                        const selectedRepCnic = $('#add_rep_cnic').val().trim().replace(/\D/g, '');
                        
                        // Separate head and other members
                        const headMember = familyMembers.find(m => m.relationship && m.relationship.toLowerCase() === 'head');
                        const otherMembers = familyMembers.filter(m => !m.relationship || m.relationship.toLowerCase() !== 'head');
                        
                        // Create root container
                        const rootContainer = $('<div class="family-tree-root"></div>');
                        
                        // Display head node (root) at top
                        if (headMember) {
                            const headNode = createFamilyTreeNode(headMember, true);
                            // Check if this is the selected member
                            const headCnic = (headMember.cnic || '').replace(/\D/g, '');
                            if (selectedRepCnic && headCnic === selectedRepCnic) {
                                headNode.addClass('selected');
                            }
                            rootContainer.append(headNode);
                        }
                        
                        // Create children container
                        if (otherMembers.length > 0) {
                            const childrenContainer = $('<div class="family-tree-children"></div>');
                            
                            // Display other members as child nodes
                            otherMembers.forEach(function(member) {
                                const childNode = createFamilyTreeNode(member, false);
                                // Check if this is the selected member
                                const memberCnic = (member.cnic || '').replace(/\D/g, '');
                                if (selectedRepCnic && memberCnic === selectedRepCnic) {
                                    childNode.addClass('selected');
                                }
                                childrenContainer.append(childNode);
                            });
                            
                            rootContainer.append(childrenContainer);
                        }
                        
                        treeGrid.append(rootContainer);
                    }
                },
                preConfirm: () => {
                    const form = document.getElementById('addBeneficiaryForm');
                    const formData = new FormData(form);
                    const data = {};
                    
                    // Calculate age first to determine if representative is required
                    const dob = $('#add_date_of_birth').val();
                    const allowsRepresentative = phaseScheme?.allows_representative || false;
                    let requiresRepresentative = 0;
                    if (dob) {
                        const birthDate = new Date(dob);
                        const today = new Date();
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const monthDiff = today.getMonth() - birthDate.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        // Representative required only if age < 18 AND scheme allows representative
                        requiresRepresentative = (age < 18 && allowsRepresentative) ? 1 : 0;
                    }
                    data.requires_representative = requiresRepresentative;
                    
                    // Add institution_id or local_zakat_committee_id based on scheme type
                    const isInstitutional = phaseScheme?.is_institutional || false;
                    if (isInstitutional) {
                        const institutionId = $('#add_institution_id').val();
                        if (institutionId) {
                            data.institution_id = institutionId;
                        }
                        // Add class for educational schemes
                        if (phaseScheme?.institutional_type === 'educational') {
                            const classValue = $('#add_class').val();
                            if (classValue) {
                                data.class = classValue;
                            }
                        }
                    } else {
                        const lzcId = $('#add_local_zakat_committee_id').val();
                        if (lzcId) {
                            data.local_zakat_committee_id = lzcId;
                        }
                    }
                    
                    // Convert FormData to object
                    // Only include representative data if age < 18
                    for (let [key, value] of formData.entries()) {
                        if (key.startsWith('representative[')) {
                            // Only collect representative data if representative is required (age < 18)
                            if (requiresRepresentative === 1) {
                                const repKey = key.match(/representative\[(.*?)\]/)[1];
                                if (!data.representative) data.representative = {};
                                data.representative[repKey] = value;
                            }
                            // If age >= 18, skip representative fields entirely
                        } else {
                            data[key] = value;
                        }
                    }
                    
                    // Validate representative fields if age < 18 AND scheme allows representative
                    if (requiresRepresentative === 1) {
                        if (!data.representative || !data.representative.cnic || data.representative.cnic === '') {
                            Swal.showValidationMessage('Representative CNIC is required for beneficiaries under 18 years of age.');
                            return false;
                        }
                        if (!data.representative || !data.representative.full_name || data.representative.full_name === '') {
                            Swal.showValidationMessage('Representative Full Name is required for beneficiaries under 18 years of age.');
                            return false;
                        }
                        if (!data.representative || !data.representative.father_husband_name || data.representative.father_husband_name === '') {
                            Swal.showValidationMessage('Representative Father/Husband Name is required for beneficiaries under 18 years of age.');
                            return false;
                        }
                        if (!data.representative || !data.representative.date_of_birth || data.representative.date_of_birth === '') {
                            Swal.showValidationMessage('Representative Date of Birth is required for beneficiaries under 18 years of age.');
                            return false;
                        }
                        if (!data.representative || !data.representative.gender || data.representative.gender === '') {
                            Swal.showValidationMessage('Representative Gender is required for beneficiaries under 18 years of age.');
                            return false;
                        }
                        if (!data.representative || !data.representative.relationship || data.representative.relationship === '') {
                            Swal.showValidationMessage('Representative Relationship is required for beneficiaries under 18 years of age.');
                            return false;
                        }
                        
                        // Validate representative age (must be 18+)
                        if (data.representative.date_of_birth) {
                            const repBirthDate = new Date(data.representative.date_of_birth);
                            const today = new Date();
                            let repAge = today.getFullYear() - repBirthDate.getFullYear();
                            const monthDiff = today.getMonth() - repBirthDate.getMonth();
                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < repBirthDate.getDate())) {
                                repAge--;
                            }
                            if (repAge < 18) {
                                Swal.showValidationMessage('Representative must be 18 years or above. The provided representative is ' + repAge + ' years old.');
                                return false;
                            }
                        }
                    } else {
                        // If age >= 18, ensure no representative data is sent
                        delete data.representative;
                    }
                    
                    // Validate LZC or Institution selection based on scheme type
                    if (isInstitutional) {
                        if (!data.institution_id || data.institution_id === '') {
                            Swal.showValidationMessage('Please select an Institution.');
                            return false;
                        }
                        // Validate class for educational schemes
                        if (phaseScheme?.institutional_type === 'educational') {
                            if (!data.class || data.class === '') {
                                Swal.showValidationMessage('Please enter the class for educational beneficiaries.');
                                return false;
                            }
                        }
                    } else {
                        if (!data.local_zakat_committee_id || data.local_zakat_committee_id === '') {
                            Swal.showValidationMessage('Please select a Local Zakat Committee.');
                            return false;
                        }
                    }
                    
                    // Validate scheme category if scheme has categories
                    if (phaseScheme && phaseScheme.categories && phaseScheme.categories.length > 0) {
                        if (!data.scheme_category_id || data.scheme_category_id === '') {
                            Swal.showValidationMessage('Please select a scheme category.');
                            return false;
                        }
                    }
                    
                    // Validate amount based on scheme type
                    if (phaseScheme) {
                        const hasCategories = phaseScheme.categories && phaseScheme.categories.length > 0;
                        const isLumpSum = phaseScheme.is_lump_sum;
                        
                        if (isLumpSum) {
                            // Lump sum requires amount to be entered manually
                            if (!data.amount || data.amount <= 0) {
                                Swal.showValidationMessage('Please enter the amount for this beneficiary.');
                                return false;
                            }
                        } else if (hasCategories) {
                            // Amount should be set from category
                            if (!data.amount || data.amount <= 0) {
                                Swal.showValidationMessage('Please select a scheme category to set the amount.');
                                return false;
                            }
                        }
                    }

                    return $.ajax({
                        url: '<?php echo e(route("beneficiaries.store-ajax")); ?>',
                        type: 'POST',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            
                            // Check if it's a duplicate CNIC error
                            if (response && response.error_type === 'duplicate_cnic' && response.duplicate_details) {
                                const details = response.duplicate_details;
                                
                                // Close the current modal first
                                Swal.close();
                                
                                // Show detailed duplicate CNIC modal
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Duplicate CNIC Entry',
                                    html: `
                                        <div style="text-align: left; padding: 10px 0;">
                                            <p style="margin-bottom: 15px; font-weight: 600; color: #dc3545;">
                                                This CNIC cannot be registered again in the same financial year.
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
                                // Handle other errors - show custom toast for better UX
                                let errorMessage = 'An error occurred.';
                                if (response && response.message) {
                                    errorMessage = response.message;
                                } else if (response && response.errors) {
                                    const errors = Object.values(response.errors).flat();
                                    errorMessage = errors.join('<br>');
                                }
                                
                                // Close the modal and show error toast
                                Swal.close();
                                showCustomToast('error', 'Error', errorMessage);
                            }
                        }
                    });
                }
            });
        }

        // Edit Beneficiary Button Click
        $(document).on('click', '.editBeneficiaryBtn', function() {
            const beneficiaryId = $(this).data('beneficiary-id');
            editBeneficiary(beneficiaryId);
        });

        function editBeneficiary(beneficiaryId) {
            // Fetch beneficiary details first
            $.ajax({
                url: '<?php echo e(route("beneficiaries.get-details", ":id")); ?>'.replace(':id', beneficiaryId),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const b = response.beneficiary;
                        showEditBeneficiaryModal(b);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load beneficiary details.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load beneficiary details.'
                    });
                }
            });
        }

        // Helper function to initialize custom select
        function initCustomSelect(displaySelector, dropdownSelector, searchSelector, optionsSelector, hiddenInputSelector) {
            const $display = $(displaySelector);
            const $dropdown = $(dropdownSelector);
            const $hiddenInput = $(hiddenInputSelector);
            const $searchInput = $(searchSelector);
            const $options = $(optionsSelector).find('.custom-select-option');
            
            // Toggle dropdown
            $display.on('click', function(e) {
                e.stopPropagation();
                const isVisible = $dropdown.is(':visible');
                $dropdown.toggle();
                if (!isVisible) {
                    $display.addClass('active');
                    $searchInput.focus();
                } else {
                    $display.removeClass('active');
                }
            });
            
            // Search functionality
            $searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $options.each(function() {
                    const text = $(this).data('text').toLowerCase();
                    if (text.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            
            // Select option
            $options.on('click', function(e) {
                e.stopPropagation();
                const value = $(this).data('value');
                const text = $(this).data('text');
                
                $hiddenInput.val(value);
                $display.find('.select-placeholder').text(text).css('color', '#000');
                $display.removeClass('active');
                $dropdown.hide();
                $searchInput.val('');
                
                // Update selected state
                $options.removeClass('selected');
                $(this).addClass('selected');
                
                // Show all options again
                $options.show();
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest($display.closest('.custom-searchable-select')).length) {
                    $dropdown.hide();
                    $display.removeClass('active');
                }
            });
            
            // Prevent closing when clicking inside dropdown
            $dropdown.on('click', function(e) {
                e.stopPropagation();
            });
        }

        // Helper function to load scheme categories
        function loadSchemeCategories(schemeId, categorySelectSelector, categoryDivSelector, selectedCategoryId = null, beneficiaryId = null) {
            $.ajax({
                url: '<?php echo e(route("beneficiaries.scheme-categories", ":id")); ?>'.replace(':id', schemeId),
                type: 'GET',
                success: function(categories) {
                    const $categorySelect = $(categorySelectSelector);
                    const $categoryDiv = $(categoryDivSelector);
                    
                    $categorySelect.empty();
                    $categorySelect.append('<option value="">Select Category</option>');
                    
                    if (categories && categories.length > 0) {
                        categories.forEach(function(category) {
                            const selected = selectedCategoryId && category.id == selectedCategoryId ? 'selected' : '';
                            $categorySelect.append(`<option value="${category.id}" data-amount="${category.amount}" ${selected}>${category.name} - Rs. ${parseFloat(category.amount).toFixed(2)}</option>`);
                        });
                        $categoryDiv.show();
                        
                        // Set amount if category is selected
                        if (selectedCategoryId) {
                            const selectedOption = $categorySelect.find(`option[value="${selectedCategoryId}"]`);
                            if (selectedOption.length) {
                                const amount = selectedOption.data('amount');
                                if (categoryDivSelector === '#edit_schemeCategoryDiv') {
                                    $('#edit_amount').val(amount || 0);
                                    $('#edit_amount').prop('readonly', true);
                                    $('#edit_amountRequired').hide();
                                    $('#edit_amountHint').text('Amount will be auto-filled from selected category').show();
                                }
                            }
                        }
                    } else {
                        $categoryDiv.hide();
                        
                        // If no categories, it's a lump sum scheme
                        if (categoryDivSelector === '#edit_schemeCategoryDiv') {
                            const phaseId = <?php echo e($phase->id); ?>;
                            
                            // Make amount field editable and required for lump sum
                            $('#edit_amount').prop('readonly', false);
                            $('#edit_amount').prop('required', true);
                            $('#edit_amountRequired').show();
                            
                            // Fetch remaining amount for lump sum scheme (exclude current beneficiary)
                            fetchLumpSumRemainingAmountForEdit(phaseId, schemeId, beneficiaryId);
                        }
                    }
                },
                error: function() {
                    $(categoryDivSelector).hide();
                    
                    // On error, treat as lump sum for edit modal
                    if (categoryDivSelector === '#edit_schemeCategoryDiv') {
                        const phaseId = <?php echo e($phase->id); ?>;
                        
                        // Make amount field editable and required for lump sum
                        $('#edit_amount').prop('readonly', false);
                        $('#edit_amount').prop('required', true);
                        $('#edit_amountRequired').show();
                        
                        // Fetch remaining amount for lump sum scheme (exclude current beneficiary)
                        fetchLumpSumRemainingAmountForEdit(phaseId, schemeId, beneficiaryId);
                    }
                }
            });
        }
        
        // Function to fetch remaining amount for lump sum schemes in edit modal
        function fetchLumpSumRemainingAmountForEdit(phaseId, schemeId, beneficiaryId = null) {
            $.ajax({
                url: '<?php echo e(route("beneficiaries.scheme-remaining-amount")); ?>',
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
                        
                        $('#edit_amountHint').html(`Enter the amount for this beneficiary. <strong>Remaining Amount: Rs. ${formattedRemaining}</strong>`).show();
                        
                        // Set max attribute to prevent entering more than remaining
                        $('#edit_amount').attr('max', remainingAmount);
                        
                        // Update hint dynamically as user types
                        $('#edit_amount').off('input.remainingAmountEdit').on('input.remainingAmountEdit', function() {
                            const enteredAmount = parseFloat($(this).val()) || 0;
                            const newRemaining = remainingAmount - enteredAmount;
                            const formattedNewRemaining = newRemaining.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            
                            if (newRemaining < 0) {
                                $('#edit_amountHint').html(`Enter the amount for this beneficiary. <strong class="text-danger">Remaining Amount: Rs. ${formattedNewRemaining}</strong> (Exceeds limit!)`).show();
                            } else {
                                $('#edit_amountHint').html(`Enter the amount for this beneficiary. <strong>Remaining Amount: Rs. ${formattedNewRemaining}</strong>`).show();
                            }
                        });
                    } else {
                        $('#edit_amountHint').text('Enter the amount for this beneficiary').show();
                    }
                },
                error: function() {
                    $('#edit_amountHint').text('Enter the amount for this beneficiary').show();
                }
            });
        }

        // Setup API handlers for edit modal
        function setupEditModalApiHandlers(beneficiaryId) {
            // Beneficiary fetch details button
            $(document).off('click', '#edit_fetchBeneficiaryDetailsBtn').on('click', '#edit_fetchBeneficiaryDetailsBtn', function() {
                const fetchBtn = $(this);
                const originalHtml = fetchBtn.html();
                fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                
                const cnic = $('#edit_cnic').val().trim();
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
            $(document).off('click', '#edit_fetchRepDetailsBtn').on('click', '#edit_fetchRepDetailsBtn', function() {
                const fetchBtn = $(this);
                const originalHtml = fetchBtn.html();
                fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                
                const cnic = $('#edit_rep_cnic').val().trim();
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
            $(document).off('click', '#edit_fetchFamilyMembersBtn').on('click', '#edit_fetchFamilyMembersBtn', function() {
                const fetchBtn = $(this);
                const originalHtml = fetchBtn.html();
                fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                
                const beneficiaryCnic = $('#edit_cnic').val().trim();
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

            // Copy buttons for edit modal
            $(document).off('click', '.api-copy-btn').on('click', '.api-copy-btn', function() {
                const targetId = $(this).data('target');
                const container = $(this).closest('.api-data-container');
                const value = container.data('original-value');
                const isSelect = $(this).data('is-select') === true;
                
                if (value) {
                    if (isSelect) {
                        $(`#${targetId}`).val(value.toLowerCase()).trigger('change');
                    } else {
                        $(`#${targetId}`).val(value);
                        
                        // If it's date of birth, check age and show representative form if needed
                        if (targetId === 'edit_date_of_birth') {
                            checkAgeAndRequireRepresentativeForEdit();
                        }
                    }
                    showCustomToast('success', 'Copied', 'Data copied to form field.');
                }
            });

            // Date of birth change handler for edit modal
            $('#edit_date_of_birth').on('change', function() {
                checkAgeAndRequireRepresentativeEdit();
            });
            
            // Also check on input (for manual typing)
            $('#edit_date_of_birth').on('input', checkAgeAndRequireRepresentativeEdit);

            // Scheme category change handler for edit modal
            $('#edit_scheme_category_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const amount = selectedOption.data('amount');
                if (amount) {
                    $('#edit_amount').val(amount);
                }
            });
        }

        // Check age and show representative form for edit modal
        function checkAgeAndRequireRepresentativeEdit() {
            const dob = $('#edit_date_of_birth').val();
            // Get scheme from the schemes array
            const schemeId = $('#edit_scheme_id').val();
            const scheme = schemes.find(s => s.id == schemeId);
            const allowsRepresentative = scheme?.allows_representative || false;
            const hasAgeRestriction = scheme?.has_age_restriction || false;
            const minimumAge = scheme?.minimum_age || 0;
            
            if (dob) {
                const birthDate = new Date(dob);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                // Check scheme settings
                if (age < 18) {
                    // Check if scheme allows representative
                    if (!allowsRepresentative) {
                        // If scheme has age restriction and doesn't allow representative, < 18 beneficiaries are not eligible
                        if (hasAgeRestriction && minimumAge >= 18) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Not Eligible',
                                text: 'This scheme has age restriction (minimum age: ' + minimumAge + ') and does not allow representatives. Beneficiaries below 18 years are not eligible for this scheme.',
                            });
                            $('#edit_date_of_birth').val(dob);
                            $('#edit_representativeDiv').hide();
                            // Remove required from representative fields
                            $('#edit_rep_cnic').prop('required', false);
                            $('#edit_rep_full_name').prop('required', false);
                            $('#edit_rep_father_husband_name').prop('required', false);
                            $('#edit_rep_date_of_birth').prop('required', false);
                            $('#edit_rep_gender').prop('required', false);
                            $('#edit_rep_relationship').prop('required', false);
                            return;
                        }
                        // If no age restriction or minimum age < 18, just hide representative section
                        $('#edit_representativeDiv').hide();
                        // Remove required from representative fields
                        $('#edit_rep_cnic').prop('required', false);
                        $('#edit_rep_full_name').prop('required', false);
                        $('#edit_rep_father_husband_name').prop('required', false);
                        $('#edit_rep_date_of_birth').prop('required', false);
                        $('#edit_rep_gender').prop('required', false);
                        $('#edit_rep_relationship').prop('required', false);
                    } else {
                        // Scheme allows representative - show representative section and make fields required
                        $('#edit_representativeDiv').slideDown();
                        
                        // Make representative fields required
                        $('#edit_rep_cnic').prop('required', true);
                        $('#edit_rep_full_name').prop('required', true);
                        $('#edit_rep_father_husband_name').prop('required', true);
                        $('#edit_rep_date_of_birth').prop('required', true);
                        $('#edit_rep_gender').prop('required', true);
                        $('#edit_rep_relationship').prop('required', true);
                    }
                } else {
                    // Age is 18 or above - hide representative section and remove required
                    $('#edit_representativeDiv').slideUp();
                    
                    // Remove required from representative fields
                    $('#edit_rep_cnic').prop('required', false);
                    $('#edit_rep_full_name').prop('required', false);
                    $('#edit_rep_father_husband_name').prop('required', false);
                    $('#edit_rep_date_of_birth').prop('required', false);
                    $('#edit_rep_gender').prop('required', false);
                    $('#edit_rep_relationship').prop('required', false);
                    
                    // Clear representative fields
                    $('#edit_rep_cnic').val('');
                    $('#edit_rep_full_name').val('');
                    $('#edit_rep_father_husband_name').val('');
                    $('#edit_rep_mobile_number').val('');
                    $('#edit_rep_date_of_birth').val('');
                    $('#edit_rep_gender').val('');
                    $('#edit_rep_relationship').val('');
                }
            } else {
                // No date of birth - hide representative section
                $('#edit_representativeDiv').hide();
                
                // Remove required from representative fields
                $('#edit_rep_cnic').prop('required', false);
                $('#edit_rep_full_name').prop('required', false);
                $('#edit_rep_father_husband_name').prop('required', false);
                $('#edit_rep_date_of_birth').prop('required', false);
                $('#edit_rep_gender').prop('required', false);
                $('#edit_rep_relationship').prop('required', false);
            }
        }

        // Fetch beneficiary data for edit modal
        function fetchBeneficiaryDataForEdit(cnic, callback) {
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            function displayApiDataForEdit(data) {
                function showApiData(containerId, value, displayValue = null) {
                    if (value && value !== 'N/A' && value !== null && value !== '') {
                        const container = $(`#edit_${containerId}`);
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
                            displayApiDataForEdit(response.data);
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
                            errorMessage = 'Authentication failed. Please check API credentials.';
                        } else if (xhr.status === 404) {
                            const response = xhr.responseJSON;
                            if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                                errorTitle = 'Member Not Found';
                                errorMessage = response.message || 'Member with this CNIC was not found in the verified database. Please enter details manually.';
                                errorIcon = 'info';
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
                fetchMemberData(apiToken);
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
                            fetchMemberData(loginResponse.data.access_token);
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

        // Fetch representative data for edit modal
        function fetchRepresentativeDataForEdit(cnic, callback) {
            fetchBeneficiaryDataForEdit(cnic, callback);
        }

        // Fetch family members for edit modal
        function fetchFamilyMembersForEdit(beneficiaryCnic, callback) {
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            function fetchFamilyMembersFromAPI(token) {
                $.ajax({
                    url: `${apiBaseUrl}/external/zakat/member/family-members`,
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify({ cnic: beneficiaryCnic }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (callback) callback();
                        if (response.success && response.data && response.data.length > 0) {
                            displayFamilyTreeForEdit(response.data);
                            $('#edit_familyTreeContainer').show();
                            showCustomToast('success', 'Family Members Found', `Found ${response.data.length} eligible family member(s). Click on any node to auto-fill representative details.`);
                        } else {
                            $('#edit_familyTreeContainer').hide();
                            showCustomToast('info', 'No Family Members Found', response.message || 'No eligible family members found. Please enter representative details manually.');
                        }
                    },
                    error: function(xhr) {
                        if (callback) callback();
                        $('#edit_familyTreeContainer').hide();
                        let errorTitle = 'API Error';
                        let errorMessage = 'Failed to fetch family members.';
                        let errorIcon = 'error';
                        
                        if (xhr.status === 401) {
                            errorTitle = 'Authentication Failed';
                            errorMessage = 'Authentication failed. Please check API credentials.';
                        } else if (xhr.status === 404) {
                            const response = xhr.responseJSON;
                            if (response && response.error_code === 'MEMBER_NOT_FOUND') {
                                errorTitle = 'Member Not Found';
                                errorMessage = response.message || 'Beneficiary member not found in the system.';
                                errorIcon = 'info';
                            } else if (response && response.error_code === 'NO_HOUSEHOLD') {
                                errorTitle = 'No Household';
                                errorMessage = response.message || 'Beneficiary is not associated with any household.';
                                errorIcon = 'info';
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
                fetchFamilyMembersFromAPI(apiToken);
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
                            fetchFamilyMembersFromAPI(loginResponse.data.access_token);
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

        // Display family tree for edit modal
        function displayFamilyTreeForEdit(familyMembers) {
            const treeGrid = $('#edit_familyTreeGrid');
            treeGrid.empty();
            
            const headMember = familyMembers.find(m => m.relationship && m.relationship.toLowerCase() === 'head');
            const otherMembers = familyMembers.filter(m => !m.relationship || m.relationship.toLowerCase() !== 'head');
            
            const rootContainer = $('<div class="family-tree-root"></div>');
            
            if (headMember) {
                const headNode = createFamilyTreeNodeForEdit(headMember, true);
                const selectedRepCnic = $('#edit_rep_cnic').val().trim().replace(/\D/g, '');
                const headCnic = (headMember.cnic || '').replace(/\D/g, '');
                if (selectedRepCnic && headCnic === selectedRepCnic) {
                    headNode.addClass('selected');
                }
                rootContainer.append(headNode);
            }
            
            if (otherMembers.length > 0) {
                const childrenContainer = $('<div class="family-tree-children"></div>');
                const selectedRepCnic = $('#edit_rep_cnic').val().trim().replace(/\D/g, '');
                
                otherMembers.forEach(function(member) {
                    const childNode = createFamilyTreeNodeForEdit(member, false);
                    const memberCnic = (member.cnic || '').replace(/\D/g, '');
                    if (selectedRepCnic && memberCnic === selectedRepCnic) {
                        childNode.addClass('selected');
                    }
                    childrenContainer.append(childNode);
                });
                
                rootContainer.append(childrenContainer);
            }
            
            treeGrid.append(rootContainer);
        }

        // Create family tree node for edit modal
        function createFamilyTreeNodeForEdit(member, isRoot) {
            const nodeClass = isRoot ? 'family-tree-node root-node' : 'family-tree-node child-node';
            const relationshipBadge = member.relationship || 'Family Member';
            const ageBadge = member.age ? `${member.age} yrs` : 'N/A';
            
            const nodeHtml = `
                <div class="${nodeClass}" data-family-member='${JSON.stringify(member)}'>
                    <div class="family-tree-node-header">
                        <h6 class="family-tree-node-name" title="${member.full_name || 'N/A'}">${member.full_name || 'N/A'}</h6>
                        <span class="family-tree-node-relationship">${relationshipBadge}</span>
                    </div>
                    <div class="family-tree-node-age">Age: ${ageBadge}</div>
                </div>
            `;
            
            return $(nodeHtml);
        }

        // Family member node click handler for edit modal
        $(document).on('click', '#edit_familyTreeGrid .family-tree-node', function() {
            $('.family-tree-node').removeClass('selected');
            $(this).addClass('selected');
            
            const familyMember = $(this).data('family-member');
            if (!familyMember) {
                return;
            }
            
            $('#edit_rep_cnic').val(familyMember.cnic);
            $('#edit_rep_full_name').val(familyMember.full_name);
            $('#edit_rep_father_husband_name').val(familyMember.father_husband_name);
            $('#edit_rep_mobile_number').val(familyMember.mobile_number || '');
            $('#edit_rep_date_of_birth').val(familyMember.date_of_birth || '');
            
            const genderMap = {
                'Male': 'male',
                'Female': 'female',
                'Other': 'other'
            };
            const genderValue = genderMap[familyMember.gender] || familyMember.gender.toLowerCase();
            $('#edit_rep_gender').val(genderValue).trigger('change');
            
            $('#edit_rep_relationship').val(familyMember.relationship || '');
            
            if ($('#edit_rep_cnic').val()) {
                $('#edit_rep_cnic').val(formatCNIC($('#edit_rep_cnic').val()));
            }
            if ($('#edit_rep_mobile_number').val()) {
                $('#edit_rep_mobile_number').val(formatMobileNumber($('#edit_rep_mobile_number').val()));
            }
            
            showCustomToast('success', 'Family Member Selected', 'Representative details have been auto-filled from the selected family member.');
        });

        function showEditBeneficiaryModal(beneficiary) {
            // Get scheme information
            const scheme = schemes.find(s => s.id == beneficiary.scheme_id);
            const isInstitutional = scheme?.is_institutional || false;
            const institutionalType = scheme?.institutional_type || null;
            const beneficiaryRequiredFields = scheme?.beneficiary_required_fields || [];
            const allowsRepresentative = scheme?.allows_representative || false;
            const hasAgeRestriction = scheme?.has_age_restriction || false;
            const minimumAge = scheme?.minimum_age || 0;
            
            // Filter committees to only show those from phase's district
            const filteredCommittees = committees.filter(c => c.district?.id == phaseDistrictId);

            let committeeOptions = '';
            filteredCommittees.forEach(function(committee) {
                const displayText = `${committee.name} [${committee.code ?? 'N/A'}] - ${committee.district?.name ?? 'N/A'}`;
                const selected = committee.id == beneficiary.local_zakat_committee_id ? 'selected' : '';
                committeeOptions += `<div class="custom-select-option" data-value="${committee.id}" data-text="${displayText}" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0; ${selected ? 'background-color: #e7f3ff;' : ''}">${displayText}</div>`;
            });

            // Get selected committee display text
            const selectedCommittee = filteredCommittees.find(c => c.id == beneficiary.local_zakat_committee_id);
            const selectedCommitteeText = selectedCommittee ? `${selectedCommittee.name} [${selectedCommittee.code ?? 'N/A'}] - ${selectedCommittee.district?.name ?? 'N/A'}` : 'Select Committee';
            
            // Filter institutions based on institutional type
            let filteredInstitutions = [];
            if (isInstitutional) {
                if (institutionalType === 'educational') {
                    filteredInstitutions = institutions.filter(i => 
                        ['middle_school', 'high_school', 'college', 'university'].includes(i.type)
                    );
                } else if (institutionalType === 'madarsa') {
                    filteredInstitutions = institutions.filter(i => i.type === 'madarsa');
                } else if (institutionalType === 'health') {
                    filteredInstitutions = institutions.filter(i => i.type === 'hospital');
                }
            }
            
            let institutionOptions = '';
            let selectedInstitutionText = 'Select Institution';
            if (isInstitutional && beneficiary.institution_id) {
                const selectedInstitution = institutions.find(i => i.id == beneficiary.institution_id);
                if (selectedInstitution) {
                    selectedInstitutionText = `${selectedInstitution.name} [${selectedInstitution.code ?? 'N/A'}] - ${selectedInstitution.district?.name ?? 'N/A'}`;
                }
            }
            filteredInstitutions.forEach(function(institution) {
                const displayText = `${institution.name} [${institution.code ?? 'N/A'}] - ${institution.district?.name ?? 'N/A'}`;
                const selected = institution.id == beneficiary.institution_id ? 'selected' : '';
                institutionOptions += `<div class="custom-select-option" data-value="${institution.id}" data-text="${displayText}" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0; ${selected ? 'background-color: #e7f3ff;' : ''}">${displayText}</div>`;
            });

            // Format dates
            const dob = beneficiary.date_of_birth || '';
            const repDob = beneficiary.representative?.date_of_birth || '';

            // Check if representative is required (age < 18 AND scheme allows representative)
            let requiresRep = beneficiary.requires_representative || false;
            if (dob) {
                const birthDate = new Date(dob);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                // Representative required only if age < 18 AND scheme allows representative
                requiresRep = (age < 18 && allowsRepresentative);
            }

            Swal.fire({
                title: 'Edit Beneficiary',
                html: `
                    <form id="editBeneficiaryForm" style="text-align: left;">
                        <input type="hidden" name="beneficiary_id" value="${beneficiary.id}">
                        <input type="hidden" name="scheme_id" id="edit_scheme_id" value="${beneficiary.scheme_id}">
                        
                        <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Scheme Information</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label"><strong>Scheme:</strong></label>
                                        <p class="mb-0">${beneficiary.scheme_name || 'N/A'}</p>
                                    </div>
                                    <div class="col-md-12 mb-3" id="edit_schemeCategoryDiv" style="display: ${beneficiary.scheme_category_id ? 'block' : 'none'};">
                                        <label class="form-label">Scheme Category <span class="text-danger">*</span></label>
                                        <select name="scheme_category_id" id="edit_scheme_category_id" class="form-control">
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${isInstitutional ? `
                        <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Institution</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        ${isInstitutionUser && currentInstitution ? `
                                            <label class="form-label">Institution</label>
                                            <p class="mb-0"><strong>${currentInstitution.name} [${currentInstitution.code ?? 'N/A'}] - ${currentInstitution.district?.name ?? 'N/A'}</strong></p>
                                            <input type="hidden" name="institution_id" id="edit_institution_id" value="${currentInstitution.id}">
                                        ` : `
                                            <label class="form-label">Institution <span class="text-danger">*</span></label>
                                            <div class="custom-searchable-select" style="position: relative;">
                                                <input type="hidden" name="institution_id" id="edit_institution_id" value="${beneficiary.institution_id || ''}" required>
                                                <div class="custom-select-display" id="edit_institution_select_display" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem; background-color: #fff; cursor: pointer; min-height: 38px; display: flex; align-items: center;">
                                                    <span class="select-placeholder" style="color: #212529;">${selectedInstitutionText}</span>
                                                    <span class="select-arrow" style="margin-left: auto;">▼</span>
                                                </div>
                                                <div class="custom-select-dropdown" id="edit_institution_select_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ced4da; border-radius: 0.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 1000; max-height: 300px; overflow-y: auto; margin-top: 2px;">
                                                    <div class="custom-select-search" style="padding: 8px; border-bottom: 1px solid #dee2e6;">
                                                        <input type="text" id="edit_institution_search_input" class="form-control form-control-sm" placeholder="Search institution..." autocomplete="off">
                                                    </div>
                                                    <div class="custom-select-options" id="edit_institution_select_options" style="max-height: 250px; overflow-y: auto;">
                                                        ${institutionOptions}
                                                    </div>
                                                </div>
                                            </div>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : `
                        <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Local Zakat Committee Selection</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Local Zakat Committee <span class="text-danger">*</span></label>
                                        <div class="custom-searchable-select" style="position: relative;">
                                            <input type="hidden" name="local_zakat_committee_id" id="edit_local_zakat_committee_id" value="${beneficiary.local_zakat_committee_id || ''}" required>
                                            <div class="custom-select-display" id="edit_lzc_select_display" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem; background-color: #fff; cursor: pointer; min-height: 38px; display: flex; align-items: center;">
                                                <span class="select-placeholder" style="color: #212529;">${selectedCommitteeText}</span>
                                                <span class="select-arrow" style="margin-left: auto;">▼</span>
                                            </div>
                                            <div class="custom-select-dropdown" id="edit_lzc_select_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ced4da; border-radius: 0.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 1000; max-height: 300px; overflow-y: auto; margin-top: 2px;">
                                                <div class="custom-select-search" style="padding: 8px; border-bottom: 1px solid #dee2e6;">
                                                    <input type="text" id="edit_lzc_search_input" class="form-control form-control-sm" placeholder="Search committee..." autocomplete="off">
                                                </div>
                                                <div class="custom-select-options" id="edit_lzc_select_options" style="max-height: 250px; overflow-y: auto;">
                                                    ${committeeOptions}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `}

                        <hr>
                        <h6>Beneficiary Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CNIC <span class="text-danger" id="edit_cnicRequired" style="display: ${beneficiaryRequiredFields.includes('cnic') ? 'inline' : 'none'};">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="cnic" id="edit_cnic" class="form-control" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" value="${beneficiary.cnic || ''}" ${beneficiaryRequiredFields.includes('cnic') ? 'required' : ''}>
                                    <button type="button" class="btn btn-primary" id="edit_fetchBeneficiaryDetailsBtn" title="Fetch details from Wheat Distribution System">
                                        <i class="ti-search"></i> Fetch Details
                                    </button>
                                </div>
                                <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger" id="edit_full_nameRequired" style="display: ${beneficiaryRequiredFields.includes('full_name') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="full_name" id="edit_full_name" class="form-control" value="${beneficiary.full_name || ''}" ${beneficiaryRequiredFields.includes('full_name') ? 'required' : ''}>
                                    <div id="edit_apiFullName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_full_name" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father/Husband Name <span class="text-danger" id="edit_father_husband_nameRequired" style="display: ${beneficiaryRequiredFields.includes('father_husband_name') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="father_husband_name" id="edit_father_husband_name" class="form-control" value="${beneficiary.father_husband_name || ''}" ${beneficiaryRequiredFields.includes('father_husband_name') ? 'required' : ''}>
                                    <div id="edit_apiFatherHusbandName" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_father_husband_name" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number <span class="text-danger" id="edit_mobile_numberRequired" style="display: ${beneficiaryRequiredFields.includes('mobile_number') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <input type="text" name="mobile_number" id="edit_mobile_number" class="form-control" placeholder="03XX-XXXXXXX" maxlength="12" value="${beneficiary.mobile_number || ''}" ${beneficiaryRequiredFields.includes('mobile_number') ? 'required' : ''}>
                                    <div id="edit_apiMobileNumber" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_mobile_number" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Format: 03XX-XXXXXXX</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="date" name="date_of_birth" id="edit_date_of_birth" class="form-control" value="${dob}" required>
                                    <div id="edit_apiDateOfBirth" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_date_of_birth" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender <span class="text-danger" id="edit_genderRequired" style="display: ${beneficiaryRequiredFields.includes('gender') ? 'inline' : 'none'};">*</span></label>
                                <div class="position-relative">
                                    <select name="gender" id="edit_gender" class="form-control" ${beneficiaryRequiredFields.includes('gender') ? 'required' : ''}>
                                        <option value="">Select Gender</option>
                                        <option value="male" ${beneficiary.gender === 'male' ? 'selected' : ''}>Male</option>
                                        <option value="female" ${beneficiary.gender === 'female' ? 'selected' : ''}>Female</option>
                                        <option value="other" ${beneficiary.gender === 'other' ? 'selected' : ''}>Other</option>
                                    </select>
                                    <div id="edit_apiGender" class="api-data-container" style="display: none;">
                                        <div class="api-data-value"></div>
                                        <button type="button" class="btn btn-sm btn-success api-copy-btn" data-target="edit_gender" data-is-select="true" title="Copy to form field">
                                            <i class="ti-check"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            ${institutionalType === 'educational' ? `
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class <span class="text-danger">*</span></label>
                                <input type="text" name="class" id="edit_class" class="form-control" placeholder="e.g., 5th, 10th, 1st Year" value="${beneficiary.class || ''}" required>
                            </div>
                            ` : ''}
                            <div class="col-md-6 mb-3" id="edit_amountDiv">
                                <label class="form-label">Amount <span class="text-danger" id="edit_amountRequired" style="display: none;">*</span></label>
                                <input type="number" name="amount" id="edit_amount" class="form-control" step="0.01" min="0" value="${beneficiary.amount || 0}" readonly>
                                <small class="text-muted" id="edit_amountHint">Amount will be auto-filled from category</small>
                            </div>
                        </div>
                        <div id="edit_representativeDiv" style="display: ${requiresRep ? 'block' : 'none'};">
                            <hr>
                            <h6>Representative Information</h6>
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
                                        <input type="text" name="representative[cnic]" id="edit_rep_cnic" class="form-control" placeholder="12345-1234567-1" maxlength="15" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" value="${beneficiary.representative?.cnic || ''}">
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
                                        <input type="text" name="representative[full_name]" id="edit_rep_full_name" class="form-control" value="${beneficiary.representative?.full_name || ''}">
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
                                        <input type="text" name="representative[father_husband_name]" id="edit_rep_father_husband_name" class="form-control" value="${beneficiary.representative?.father_husband_name || ''}">
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
                                        <input type="text" name="representative[mobile_number]" id="edit_rep_mobile_number" class="form-control" placeholder="03XX-XXXXXXX" maxlength="12" value="${beneficiary.representative?.mobile_number || ''}">
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
                                        <input type="date" name="representative[date_of_birth]" id="edit_rep_date_of_birth" class="form-control" value="${repDob}">
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
                                            <option value="male" ${beneficiary.representative?.gender === 'male' ? 'selected' : ''}>Male</option>
                                            <option value="female" ${beneficiary.representative?.gender === 'female' ? 'selected' : ''}>Female</option>
                                            <option value="other" ${beneficiary.representative?.gender === 'other' ? 'selected' : ''}>Other</option>
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
                                        <input type="text" name="representative[relationship]" id="edit_rep_relationship" class="form-control" placeholder="e.g., Father, Mother, Brother" value="${beneficiary.representative?.relationship || ''}">
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
                    </form>
                `,
                width: '900px',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#000000',
                customClass: {
                    cancelButton: 'btn btn-dark',
                    confirmButton: 'btn btn-primary'
                },
                didOpen: () => {
                    // Initialize custom select for LZC
                    initCustomSelect('#edit_lzc_select_display', '#edit_lzc_select_dropdown', '#edit_lzc_search_input', '#edit_lzc_select_options', '#edit_local_zakat_committee_id');

                    // Load scheme categories if scheme has categories
                    if (beneficiary.scheme_id) {
                        loadSchemeCategories(beneficiary.scheme_id, '#edit_scheme_category_id', '#edit_schemeCategoryDiv', beneficiary.scheme_category_id);
                    }

                    // Setup API fetch handlers for edit modal (similar to add modal)
                    setupEditModalApiHandlers(beneficiary.id);
                },
                preConfirm: () => {
                    const form = document.getElementById('editBeneficiaryForm');
                    const formData = new FormData(form);
                    const data = {};
                    
                    // Calculate age first to determine if representative is required
                    const dob = $('#edit_date_of_birth').val();
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
                    
                    // Convert FormData to object
                    for (let [key, value] of formData.entries()) {
                        if (key.startsWith('representative[')) {
                            if (requiresRepresentative) {
                                const repKey = key.replace('representative[', '').replace(']', '');
                                if (!data.representative) data.representative = {};
                                data.representative[repKey] = value;
                            }
                        } else if (key !== 'beneficiary_id') {
                            data[key] = value;
                        }
                    }
                    
                    // Validate LZC selection
                    if (!data.local_zakat_committee_id || data.local_zakat_committee_id === '') {
                        Swal.showValidationMessage('Please select a Local Zakat Committee.');
                        return false;
                    }
                    
                    // Validate scheme category if scheme has categories
                    if (phaseScheme && phaseScheme.categories && phaseScheme.categories.length > 0) {
                        if (!data.scheme_category_id || data.scheme_category_id === '') {
                            Swal.showValidationMessage('Please select a scheme category.');
                            return false;
                        }
                    }
                    
                    // Validate amount based on scheme type
                    if (phaseScheme) {
                        const hasCategories = phaseScheme.categories && phaseScheme.categories.length > 0;
                        const isLumpSum = phaseScheme.is_lump_sum;
                        
                        if (isLumpSum) {
                            if (!data.amount || data.amount <= 0) {
                                Swal.showValidationMessage('Please enter the amount for this beneficiary.');
                                return false;
                            }
                        } else if (hasCategories) {
                            if (!data.amount || data.amount <= 0) {
                                Swal.showValidationMessage('Please select a scheme category to set the amount.');
                                return false;
                            }
                        }
                    }

                    return $.ajax({
                        url: '<?php echo e(route("beneficiaries.update-ajax", ":id")); ?>'.replace(':id', beneficiary.id),
                        type: 'POST',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            
                            // Check if it's a duplicate CNIC error
                            if (response && response.error_type === 'duplicate_cnic' && response.duplicate_details) {
                                const details = response.duplicate_details;
                                
                                // Close the current modal first
                                Swal.close();
                                
                                // Show detailed duplicate CNIC modal
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Duplicate CNIC Entry',
                                    html: `
                                        <div style="text-align: left; padding: 10px 0;">
                                            <p style="margin-bottom: 15px; font-weight: 600; color: #dc3545;">
                                                This CNIC cannot be registered again in the same financial year.
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
                                // Handle other errors - show custom toast for better UX
                                let errorMessage = 'An error occurred.';
                                if (response && response.message) {
                                    errorMessage = response.message;
                                } else if (response && response.errors) {
                                    const errors = Object.values(response.errors).flat();
                                    errorMessage = errors.join('<br>');
                                }
                                
                                // Close the modal and show error toast
                                Swal.close();
                                showCustomToast('error', 'Error', errorMessage);
                            }
                        }
                    });
                }
            });
        }

        // View Beneficiary Button Click
        $(document).on('click', '.viewBeneficiaryBtn', function() {
            const beneficiaryId = $(this).data('beneficiary-id');
            viewBeneficiary(beneficiaryId);
        });

        function viewBeneficiary(beneficiaryId) {
            $.ajax({
                url: '<?php echo e(route("beneficiaries.get-details", ":id")); ?>'.replace(':id', beneficiaryId),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const b = response.beneficiary;
                        let repHtml = '';
                        if (b.representative) {
                            repHtml = `
                                <hr>
                                <h6>Representative Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2"><strong>CNIC:</strong> ${b.representative.cnic || 'N/A'}</div>
                                    <div class="col-md-6 mb-2"><strong>Full Name:</strong> ${b.representative.full_name || 'N/A'}</div>
                                    <div class="col-md-6 mb-2"><strong>Father/Husband Name:</strong> ${b.representative.father_husband_name || 'N/A'}</div>
                                    <div class="col-md-6 mb-2"><strong>Mobile Number:</strong> ${b.representative.mobile_number || 'N/A'}</div>
                                    <div class="col-md-6 mb-2"><strong>Date of Birth:</strong> ${b.representative.date_of_birth || 'N/A'}</div>
                                    <div class="col-md-6 mb-2"><strong>Gender:</strong> ${b.representative.gender || 'N/A'}</div>
                                    <div class="col-md-6 mb-2"><strong>Relationship:</strong> ${b.representative.relationship || 'N/A'}</div>
                                </div>
                            `;
                        }

                        Swal.fire({
                            title: 'Beneficiary Details',
                            html: `
                                <div style="text-align: left;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2"><strong>CNIC:</strong> ${b.cnic || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Full Name:</strong> ${b.full_name || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Father/Husband Name:</strong> ${b.father_husband_name || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Mobile Number:</strong> ${b.mobile_number || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Date of Birth:</strong> ${b.date_of_birth || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Gender:</strong> ${b.gender || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Scheme:</strong> ${b.scheme_name || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Category:</strong> ${b.scheme_category_name || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Local Zakat Committee:</strong> ${b.local_zakat_committee_name || 'N/A'}</div>
                                        <div class="col-md-6 mb-2"><strong>Amount:</strong> Rs. ${parseFloat(b.amount || 0).toFixed(2)}</div>
                                        <div class="col-md-6 mb-2"><strong>Status:</strong> <span class="badge bg-${getStatusColor(b.status)}">${b.status || 'N/A'}</span></div>
                                        <div class="col-md-12 mb-2"><strong>Representative Required:</strong> ${b.requires_representative ? 'Yes (Age < 18)' : 'No (Age ≥ 18)'}</div>
                                        ${b.district_remarks ? `<div class="col-md-12 mb-2"><strong><?php echo e(auth()->user()->isInstitutionUser() ? 'Remarks' : 'District Remarks'); ?>:</strong> ${b.district_remarks}</div>` : ''}
                                        ${b.admin_remarks ? `<div class="col-md-12 mb-2"><strong>Admin Remarks:</strong> ${b.admin_remarks}</div>` : ''}
                                        ${b.rejection_remarks ? `<div class="col-md-12 mb-2"><strong>Rejection Remarks:</strong> ${b.rejection_remarks}</div>` : ''}
                                    </div>
                                    ${repHtml}
                                </div>
                            `,
                            width: '700px',
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load beneficiary details.'
                    });
                }
            });
        }

        function getStatusColor(status) {
            const colors = {
                'pending': 'warning',
                'submitted': 'info',
                'approved': 'success',
                'rejected': 'danger',
                'paid': 'primary'
            };
            return colors[status] || 'secondary';
        }

        // Delete Beneficiary Button Click
        $(document).on('click', '.deleteBeneficiaryBtn', function() {
            const beneficiaryId = $(this).data('beneficiary-id');
            const beneficiaryName = $(this).data('beneficiary-name');
            deleteBeneficiary(beneficiaryId, beneficiaryName);
        });

        function deleteBeneficiary(beneficiaryId, beneficiaryName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete beneficiary: ${beneficiaryName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#000000',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    cancelButton: 'btn btn-dark',
                    confirmButton: 'btn btn-primary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo e(route("beneficiaries.delete-ajax", ":id")); ?>'.replace(':id', beneficiaryId),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to delete beneficiary.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        }

        // Verify/Submit Beneficiary Button Click
        $(document).on('click', '.verifyBeneficiaryBtn', function() {
            const beneficiaryId = $(this).data('beneficiary-id');
            verifyBeneficiary(beneficiaryId);
        });

        function verifyBeneficiary(beneficiaryId) {
            // Fetch beneficiary details first
            $.ajax({
                url: '<?php echo e(route("beneficiaries.get-details", ":id")); ?>'.replace(':id', beneficiaryId),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const b = response.beneficiary;
                        showVerifyBeneficiaryModal(b);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load beneficiary details.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load beneficiary details.'
                    });
                }
            });
        }

        function showVerifyBeneficiaryModal(beneficiary) {
            // Format dates
            const dob = beneficiary.date_of_birth || 'N/A';
            const repDob = beneficiary.representative?.date_of_birth || 'N/A';
            
            // Build representative HTML
            let repHtml = '';
            if (beneficiary.representative) {
                const repCnic = beneficiary.representative.cnic || '';
                const repCnicDigits = repCnic.replace(/\D/g, '');
                const showRepFetchBtn = repCnicDigits.length === 13;
                
                repHtml = `
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Representative Information</h6>
                        ${showRepFetchBtn ? `
                            <button type="button" class="btn btn-sm btn-primary" id="verify_fetchRepDetailsBtn" title="Fetch representative details from Wheat Distribution System">
                                <i class="ti-search"></i> Fetch API Details
                            </button>
                        ` : ''}
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>CNIC:</strong> 
                            <span id="verify_rep_cnic">${beneficiary.representative.cnic || 'N/A'}</span>
                            <div id="verify_apiRepCnic" class="api-data-container" style="display: none; margin-top: 5px;">
                                <div class="api-data-value"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Full Name:</strong> 
                            <span id="verify_rep_full_name">${beneficiary.representative.full_name || 'N/A'}</span>
                            <div id="verify_apiRepFullName" class="api-data-container" style="display: none; margin-top: 5px;">
                                <div class="api-data-value"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Father/Husband Name:</strong> 
                            <span id="verify_rep_father_husband_name">${beneficiary.representative.father_husband_name || 'N/A'}</span>
                            <div id="verify_apiRepFatherHusbandName" class="api-data-container" style="display: none; margin-top: 5px;">
                                <div class="api-data-value"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Mobile Number:</strong> 
                            <span id="verify_rep_mobile_number">${beneficiary.representative.mobile_number || 'N/A'}</span>
                            <div id="verify_apiRepMobileNumber" class="api-data-container" style="display: none; margin-top: 5px;">
                                <div class="api-data-value"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Date of Birth:</strong> 
                            <span id="verify_rep_date_of_birth">${repDob}</span>
                            <div id="verify_apiRepDateOfBirth" class="api-data-container" style="display: none; margin-top: 5px;">
                                <div class="api-data-value"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Gender:</strong> 
                            <span id="verify_rep_gender">${beneficiary.representative.gender || 'N/A'}</span>
                            <div id="verify_apiRepGender" class="api-data-container" style="display: none; margin-top: 5px;">
                                <div class="api-data-value"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Relationship:</strong> 
                            <span id="verify_rep_relationship">${beneficiary.representative.relationship || 'N/A'}</span>
                            <div id="verify_apiRepRelationship" class="api-data-container" style="display: none; margin-top: 5px;">
                                <div class="api-data-value"></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            Swal.fire({
                title: 'Verify & Submit Beneficiary',
                html: `
                    <form id="verifyBeneficiaryForm" style="text-align: left;">
                        <div class="card mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Beneficiary Details</h6>
                                <button type="button" class="btn btn-sm btn-primary" id="verify_fetchBeneficiaryDetailsBtn" title="Fetch details from Wheat Distribution System">
                                    <i class="ti-search"></i> Fetch API Details
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <strong>CNIC:</strong> 
                                        <span id="verify_cnic">${beneficiary.cnic || 'N/A'}</span>
                                        <div id="verify_apiCnic" class="api-data-container" style="display: none; margin-top: 5px;">
                                            <div class="api-data-value"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Full Name:</strong> 
                                        <span id="verify_full_name">${beneficiary.full_name || 'N/A'}</span>
                                        <div id="verify_apiFullName" class="api-data-container" style="display: none; margin-top: 5px;">
                                            <div class="api-data-value"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Father/Husband Name:</strong> 
                                        <span id="verify_father_husband_name">${beneficiary.father_husband_name || 'N/A'}</span>
                                        <div id="verify_apiFatherHusbandName" class="api-data-container" style="display: none; margin-top: 5px;">
                                            <div class="api-data-value"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Mobile Number:</strong> 
                                        <span id="verify_mobile_number">${beneficiary.mobile_number || 'N/A'}</span>
                                        <div id="verify_apiMobileNumber" class="api-data-container" style="display: none; margin-top: 5px;">
                                            <div class="api-data-value"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Date of Birth:</strong> 
                                        <span id="verify_date_of_birth">${dob}</span>
                                        <div id="verify_apiDateOfBirth" class="api-data-container" style="display: none; margin-top: 5px;">
                                            <div class="api-data-value"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Gender:</strong> 
                                        <span id="verify_gender">${beneficiary.gender || 'N/A'}</span>
                                        <div id="verify_apiGender" class="api-data-container" style="display: none; margin-top: 5px;">
                                            <div class="api-data-value"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Scheme:</strong> ${beneficiary.scheme_name || 'N/A'}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Category:</strong> ${beneficiary.scheme_category_name || 'N/A'}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Local Zakat Committee:</strong> ${beneficiary.local_zakat_committee_name || 'N/A'}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Amount:</strong> Rs. ${parseFloat(beneficiary.amount || 0).toFixed(2)}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Status:</strong> <span class="badge bg-${getStatusColor(beneficiary.status)}">${beneficiary.status || 'N/A'}</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Representative Required:</strong> ${beneficiary.requires_representative ? 'Yes (Age < 18)' : 'No (Age ≥ 18)'}
                                    </div>
                                </div>
                                ${repHtml}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong><?php echo e(auth()->user()->isInstitutionUser() ? 'Remarks' : 'District Remarks'); ?></strong></label>
                            <textarea name="district_remarks" id="verify_district_remarks" class="form-control" rows="3" placeholder="Enter any remarks or observations after comparing with API data...">${beneficiary.district_remarks || ''}</textarea>
                            <small class="text-muted">Add any remarks or observations after comparing the beneficiary data with the API data.</small>
                        </div>
                    </form>
                `,
                width: '900px',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#000000',
                customClass: {
                    cancelButton: 'btn btn-dark',
                    confirmButton: 'btn btn-primary'
                },
                didOpen: () => {
                    // Setup API fetch handler for beneficiary
                    $(document).off('click', '#verify_fetchBeneficiaryDetailsBtn').on('click', '#verify_fetchBeneficiaryDetailsBtn', function() {
                        const fetchBtn = $(this);
                        const originalHtml = fetchBtn.html();
                        fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                        
                        const cnic = $('#verify_cnic').text().trim();
                        if (!cnic || cnic === 'N/A') {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'CNIC Required', 'Beneficiary CNIC is required to fetch API data.');
                            return;
                        }
                        
                        const cnicDigits = cnic.replace(/\D/g, '');
                        if (cnicDigits.length !== 13) {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                            showCustomToast('warning', 'Invalid CNIC', 'Please ensure beneficiary has a valid CNIC (13 digits).');
                            return;
                        }
                        
                        fetchBeneficiaryDataForVerify(cnic, beneficiary.id, function() {
                            fetchBtn.prop('disabled', false).html(originalHtml);
                        });
                    });

                    // Setup fetch handler for representative if button exists
                    if (beneficiary.representative && beneficiary.representative.cnic) {
                        const repCnic = beneficiary.representative.cnic;
                        const repCnicDigits = repCnic.replace(/\D/g, '');
                        if (repCnicDigits.length === 13) {
                            $(document).off('click', '#verify_fetchRepDetailsBtn').on('click', '#verify_fetchRepDetailsBtn', function() {
                                const fetchBtn = $(this);
                                const originalHtml = fetchBtn.html();
                                fetchBtn.prop('disabled', true).html('<i class="ti-reload"></i> Fetching...');
                                
                                fetchRepresentativeDataForVerify(repCnic, function() {
                                    fetchBtn.prop('disabled', false).html(originalHtml);
                                });
                            });
                        }
                    }
                },
                preConfirm: () => {
                    const remarks = $('#verify_district_remarks').val();
                    return $.ajax({
                        url: '<?php echo e(route("beneficiaries.verify-ajax", ":id")); ?>'.replace(':id', beneficiary.id),
                        type: 'POST',
                        data: {
                            action: 'submit',
                            district_remarks: remarks
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to submit beneficiary.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.showValidationMessage(errorMessage);
                        }
                    });
                }
            });
        }

        // Fetch beneficiary data for verify modal
        function fetchBeneficiaryDataForVerify(cnic, beneficiaryId, callback) {
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            function displayApiDataForVerify(data) {
                function showApiData(containerId, value, displayValue = null) {
                    if (value && value !== 'N/A' && value !== null && value !== '') {
                        const container = $(`#verify_${containerId}`);
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
                            displayApiDataForVerify(response.data);
                            showCustomToast('success', 'API Data Fetched', 'Verified member data fetched. Compare with beneficiary details above.');
                        } else {
                            showCustomToast('info', 'Member Not Found', response.message || 'Member with this CNIC was not found in the verified database.');
                        }
                    },
                    error: function(xhr) {
                        if (callback) callback();
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
                    }
                });
            }
            
            if (apiToken) {
                fetchMemberData(apiToken);
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
                            fetchMemberData(loginResponse.data.access_token);
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

        // Fetch representative data for verify modal
        function fetchRepresentativeDataForVerify(cnic, callback) {
            const apiBaseUrl = '<?php echo e(config("wheat_api.base_url", "http://localhost:8001/api")); ?>';
            const apiToken = '<?php echo e(config("wheat_api.token", "")); ?>';
            const apiUsername = '<?php echo e(config("wheat_api.username", "")); ?>';
            const apiPassword = '<?php echo e(config("wheat_api.password", "")); ?>';
            
            function displayApiDataForVerify(data) {
                function showApiData(containerId, value, displayValue = null) {
                    if (value && value !== 'N/A' && value !== null && value !== '') {
                        const container = $(`#verify_${containerId}`);
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
                            displayApiDataForVerify(response.data);
                            showCustomToast('success', 'API Data Fetched', 'Verified representative data fetched. Compare with representative details above.');
                        } else {
                            showCustomToast('info', 'Member Not Found', response.message || 'Representative with this CNIC was not found in the verified database.');
                        }
                    },
                    error: function(xhr) {
                        if (callback) callback();
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
                    }
                });
            }
            
            if (apiToken) {
                fetchMemberData(apiToken);
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
                            fetchMemberData(loginResponse.data.access_token);
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

        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#000000',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    cancelButton: 'btn btn-dark',
                    confirmButton: 'btn btn-primary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            
            return false;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/beneficiaries/phase-applications.blade.php ENDPATH**/ ?>