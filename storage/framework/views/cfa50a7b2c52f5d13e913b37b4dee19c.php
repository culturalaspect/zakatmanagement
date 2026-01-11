

<?php $__env->startSection('title', config('app.name') . ' - Paid Case Details'); ?>
<?php $__env->startSection('page_title', 'Paid Case Details'); ?>
<?php $__env->startSection('breadcrumb', 'Paid Case Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Paid Beneficiary Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('admin-hq.all-cases')); ?>#paid" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back to Paid Cases
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="alert alert-success" role="alert">
                            <i class="ti-check"></i> <strong>Status:</strong> This beneficiary has been paid successfully.
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
                        <p class="mb-0"><?php echo e($beneficiary->date_of_birth ? $beneficiary->date_of_birth->format('Y-m-d') : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p class="mb-0"><?php echo e(ucfirst($beneficiary->gender ?? 'N/A')); ?></p>
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
                        <strong>Amount:</strong>
                        <p class="mb-0">Rs. <?php echo e(number_format($beneficiary->amount, 2)); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phase:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->phase->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Payment Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>JazzCash Amount:</strong>
                        <p class="mb-0">Rs. <?php echo e(number_format($beneficiary->jazzcash_amount ?? 0, 2)); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>JazzCash Charges:</strong>
                        <p class="mb-0">Rs. <?php echo e(number_format($beneficiary->jazzcash_charges ?? 0, 2)); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Total Amount Paid:</strong>
                        <p class="mb-0">Rs. <?php echo e(number_format($beneficiary->jazzcash_total ?? 0, 2)); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>JazzCash Status:</strong>
                        <p class="mb-0"><span class="badge bg-success"><?php echo e($beneficiary->jazzcash_status ?? 'N/A'); ?></span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Transaction ID (TID):</strong>
                        <p class="mb-0"><?php echo e($beneficiary->jazzcash_tid ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Payment Received At:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->payment_received_at ? $beneficiary->payment_received_at->format('Y-m-d H:i:s') : 'N/A'); ?></p>
                    </div>
                    <?php if($beneficiary->requires_representative && $beneficiary->representative): ?>
                    <div class="col-md-12 mb-4">
                        <h5 class="mb-3">Representative Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative CNIC:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->cnic ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative Name:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->full_name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Representative Mobile:</strong>
                        <p class="mb-0"><?php echo e($beneficiary->representative->mobile_number ?? 'N/A'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/admin-hq/show-paid-case.blade.php ENDPATH**/ ?>