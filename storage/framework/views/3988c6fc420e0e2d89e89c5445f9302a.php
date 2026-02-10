<?php if($beneficiaries->isEmpty()): ?>
    <div class="alert alert-info">
        <i class="ti-info-alt"></i> <?php echo e($emptyMessage); ?>

    </div>
<?php else: ?>
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
                            <label class="form-label">District</label>
                            <select id="filterDistrict_<?php echo e($type); ?>" class="form-control form-control-sm">
                                <option value="">All Districts</option>
                                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($district->name); ?>"><?php echo e($district->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Scheme</label>
                            <select id="filterScheme_<?php echo e($type); ?>" class="form-control form-control-sm">
                                <option value="">All Schemes</option>
                                <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($scheme->name); ?>"><?php echo e($scheme->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Financial Year</label>
                            <select id="filterFinancialYear_<?php echo e($type); ?>" class="form-control form-control-sm">
                                <option value="">All Financial Years</option>
                                <?php $__currentLoopData = $financialYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($fy->name); ?>"><?php echo e($fy->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" id="filterSearch_<?php echo e($type); ?>" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" id="clearFilters_<?php echo e($type); ?>" class="btn btn-sm btn-secondary">
                                <i class="ti-close"></i> Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <?php
            $canBulkApprove = auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ());
        ?>
        <?php if($type === 'pending' && $canBulkApprove): ?>
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" id="bulkApproveBtn_<?php echo e($tableId); ?>" class="btn btn-sm btn-success">
                        <i class="ti-check"></i> Approve Selected
                    </button>
                </div>
                <div>
                    <small class="text-muted">Select cases to approve in bulk.</small>
                </div>
            </div>
        <?php endif; ?>
        <table class="table" id="<?php echo e($tableId); ?>">
            <thead>
                <tr>
                    <?php if($type === 'pending' && $canBulkApprove): ?>
                        <th>
                            <input type="checkbox" id="selectAll_<?php echo e($tableId); ?>">
                        </th>
                    <?php endif; ?>
                    <th>CNIC</th>
                    <th>Name</th>
                    <th>District</th>
                    <th>Scheme</th>
                    <th>Amount</th>
                    <?php if($type === 'pending'): ?>
                        <th>Submitted By</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    <?php elseif($type === 'approved'): ?>
                        <th>Approved By</th>
                        <th>Approved At</th>
                        <th>Actions</th>
                    <?php elseif($type === 'rejected'): ?>
                        <th>Rejected At</th>
                        <th>Rejection Remarks</th>
                        <th>Actions</th>
                    <?php elseif($type === 'paid'): ?>
                        <th>Payment Received At</th>
                        <th>JazzCash TID</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    <?php elseif($type === 'payment-failed'): ?>
                        <th>JazzCash Status</th>
                        <th>Failure Reason</th>
                        <th>TID</th>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $beneficiaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficiary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <?php if($type === 'pending' && $canBulkApprove): ?>
                        <td>
                            <input type="checkbox" class="case-checkbox" value="<?php echo e($beneficiary->id); ?>">
                        </td>
                    <?php endif; ?>
                    <td><?php echo e($beneficiary->cnic); ?></td>
                    <td><?php echo e($beneficiary->full_name); ?></td>
                    <td data-financial-year="<?php echo e($beneficiary->phase->installment->fundAllocation->financialYear->name ?? ''); ?>"><?php echo e($beneficiary->phase->district->name ?? 'N/A'); ?></td>
                    <td><?php echo e($beneficiary->scheme->name ?? 'N/A'); ?></td>
                    <td>Rs. <?php echo e(number_format($beneficiary->amount, 2)); ?></td>
                    
                    <?php if($type === 'pending'): ?>
                        <td><?php echo e($beneficiary->submittedBy->name ?? 'N/A'); ?></td>
                        <td><?php echo e($beneficiary->submitted_at ? $beneficiary->submitted_at->format('Y-m-d H:i') : 'N/A'); ?></td>
                        <td>
                            <div class="action_btns d-flex">
                                <a href="<?php echo e(route('beneficiaries.show', $beneficiary)); ?>" class="action_btn mr_10" title="View & Approve/Reject">
                                    <i class="ti-eye"></i> View
                                </a>
                            </div>
                        </td>
                    <?php elseif($type === 'approved'): ?>
                        <td><?php echo e($beneficiary->approvedBy->name ?? 'N/A'); ?></td>
                        <td><?php echo e($beneficiary->approved_at ? $beneficiary->approved_at->format('Y-m-d H:i') : 'N/A'); ?></td>
                        <td>
                            <div class="action_btns d-flex">
                                <a href="<?php echo e(route('admin-hq.show-approved-case', $beneficiary)); ?>" class="action_btn mr_10" title="View Details">
                                    <i class="ti-eye"></i> View
                                </a>
                            </div>
                        </td>
                    <?php elseif($type === 'rejected'): ?>
                        <td><?php echo e($beneficiary->rejected_at ? $beneficiary->rejected_at->format('Y-m-d H:i') : 'N/A'); ?></td>
                        <td data-remarks="<?php echo e($beneficiary->admin_remarks ?? ''); ?>">
                            <?php if($beneficiary->admin_remarks): ?>
                                <span class="text-danger" title="<?php echo e($beneficiary->admin_remarks); ?>">
                                    <?php echo e(Str::limit($beneficiary->admin_remarks, 50)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action_btns d-flex">
                                <a href="<?php echo e(route('admin-hq.show-rejected-case', $beneficiary)); ?>" class="action_btn mr_10" title="View Details">
                                    <i class="ti-eye"></i> View
                                </a>
                            </div>
                        </td>
                    <?php elseif($type === 'paid'): ?>
                        <td><?php echo e($beneficiary->payment_received_at ? $beneficiary->payment_received_at->format('Y-m-d H:i') : 'N/A'); ?></td>
                        <td><?php echo e($beneficiary->jazzcash_tid ?? 'N/A'); ?></td>
                        <td>Rs. <?php echo e(number_format($beneficiary->jazzcash_total ?? 0, 2)); ?></td>
                        <td>
                            <div class="action_btns d-flex">
                                <a href="<?php echo e(route('admin-hq.show-paid-case', $beneficiary)); ?>" class="action_btn mr_10" title="View Details">
                                    <i class="ti-eye"></i> View
                                </a>
                            </div>
                        </td>
                    <?php elseif($type === 'payment-failed'): ?>
                        <td>
                            <span class="badge bg-danger"><?php echo e($beneficiary->jazzcash_status ?? 'N/A'); ?></span>
                        </td>
                        <td>
                            <?php if($beneficiary->jazzcash_reason): ?>
                                <span class="text-danger" title="<?php echo e($beneficiary->jazzcash_reason); ?>">
                                    <?php echo e(Str::limit($beneficiary->jazzcash_reason, 50)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($beneficiary->jazzcash_tid ?? 'N/A'); ?></td>
                        <td>
                            <div class="action_btns d-flex">
                                <a href="<?php echo e(route('admin-hq.show-payment-failed-case', $beneficiary)); ?>" class="action_btn mr_10" title="View Details">
                                    <i class="ti-eye"></i> View
                                </a>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center"><?php echo e($emptyMessage); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if (! $__env->hasRenderedOnce('28689837-76bc-4e89-ab53-b27ce6499a09')): $__env->markAsRenderedOnce('28689837-76bc-4e89-ab53-b27ce6499a09');
$__env->startPush('scripts'); ?>
<script>
    $(document).ready(function () {
        <?php if($type === 'pending' && auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ())): ?>
        (function() {
            const tableId = <?php echo json_encode($tableId, 15, 512) ?>;
            const selectAllId = '#selectAll_' + tableId;
            const bulkApproveBtnId = '#bulkApproveBtn_' + tableId;
            
            // Select/Deselect all checkboxes
            $(document).on('change', selectAllId, function() {
                const checked = $(this).is(':checked');
                $('#' + tableId + ' tbody .case-checkbox').prop('checked', checked);
            });

            // When any row checkbox changes, if any unchecked, uncheck header
            $(document).on('change', '#' + tableId + ' tbody .case-checkbox', function() {
                const total = $('#' + tableId + ' tbody .case-checkbox').length;
                const checked = $('#' + tableId + ' tbody .case-checkbox:checked').length;
                $(selectAllId).prop('checked', total > 0 && total === checked);
            });

            // Bulk approve button click
            $(document).on('click', bulkApproveBtnId, function() {
                const selectedIds = $('#' + tableId + ' tbody .case-checkbox:checked')
                    .map(function() { return $(this).val(); }).get();

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Cases Selected',
                        text: 'Please select at least one case to approve.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Approve Selected Cases',
                    html: `
                        <p>You are about to approve <strong>${selectedIds.length}</strong> selected case(s).</p>
                        <div class="form-group text-left">
                            <label for="bulk_admin_remarks" class="form-label">Admin Remarks (optional)</label>
                            <textarea id="bulk_admin_remarks" class="form-control" rows="3" placeholder="Enter remarks (optional)..."></textarea>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    preConfirm: () => {
                        const remarks = $('#bulk_admin_remarks').val();
                        return $.ajax({
                            url: '<?php echo e(route("admin-hq.bulk-approve")); ?>',
                            type: 'POST',
                            data: {
                                beneficiary_ids: selectedIds,
                                admin_remarks: remarks,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Approved',
                                        text: response.message || 'Selected cases have been approved successfully.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'An error occurred while approving cases.'
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'An error occurred while approving cases.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    const errors = Object.values(xhr.responseJSON.errors).flat();
                                    errorMessage = errors.join('\\n');
                                }
                                Swal.showValidationMessage(errorMessage);
                            }
                        });
                    }
                });
            });
        })();
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); endif; ?>
<?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/admin-hq/partials/cases-table.blade.php ENDPATH**/ ?>