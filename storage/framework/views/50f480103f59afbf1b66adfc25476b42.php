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
        <table class="table" id="<?php echo e($tableId); ?>">
            <thead>
                <tr>
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


<?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/admin-hq/partials/cases-table.blade.php ENDPATH**/ ?>