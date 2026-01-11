

<?php $__env->startSection('title', config('app.name') . ' - Approved Case Details'); ?>
<?php $__env->startSection('page_title', 'Approved Case Details'); ?>
<?php $__env->startSection('breadcrumb', 'Approved Case Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Approved Beneficiary Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('admin-hq.all-cases')); ?>?tab=approved" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back to Approved Cases
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="alert alert-success" role="alert">
                            <i class="ti-check"></i> <strong>Status:</strong> This beneficiary has been approved.
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Beneficiary Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>CNIC:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->cnic ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->full_name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father/Husband Name:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->father_husband_name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mobile Number:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->mobile_number ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->date_of_birth ? \Carbon\Carbon::parse($beneficiary->date_of_birth)->format('d M Y') : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">
                            <?php if($beneficiary->gender === 'male'): ?>
                                <span class="badge bg-primary">Male</span>
                            <?php elseif($beneficiary->gender === 'female'): ?>
                                <span class="badge bg-info">Female</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($beneficiary->gender ?? 'N/A')); ?></span>
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
                        <h5 class="mb-3">Representative Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>CNIC:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->cnic ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->full_name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Father/Husband Name:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->father_husband_name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Mobile Number:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->mobile_number ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->date_of_birth ? \Carbon\Carbon::parse($beneficiary->representative->date_of_birth)->format('d M Y') : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0">
                            <?php if($beneficiary->representative->gender === 'male'): ?>
                                <span class="badge bg-primary">Male</span>
                            <?php elseif($beneficiary->representative->gender === 'female'): ?>
                                <span class="badge bg-info">Female</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($beneficiary->representative->gender ?? 'N/A')); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Relationship:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->relationship ?? 'N/A'); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($beneficiary->admin_remarks || $beneficiary->district_remarks): ?>
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
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/admin-hq/show-approved-case.blade.php ENDPATH**/ ?>