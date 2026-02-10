<?php $__env->startSection('title', config('app.name') . ' - Executive Summary Report'); ?>
<?php $__env->startSection('page_title', 'Executive Summary Report'); ?>
<?php $__env->startSection('breadcrumb', 'Reports / Executive Summary'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="<?php echo e(route('reports.executive-summary')); ?>" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Fund Allocation</label>
                        <select name="fund_allocation_id" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($fa->id); ?>" <?php echo e(request('fund_allocation_id') == $fa->id ? 'selected' : ''); ?>>
                                    <?php echo e($fa->financialYear->name ?? ''); ?> - <?php echo e($fa->date?->format('d M Y')); ?> (Rs. <?php echo e(number_format($fa->total_amount, 0)); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Apply</button>
                        <?php echo $__env->make('reports.partials.export-buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </form>

                <h5 class="mb-3">Summary</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr><th width="40%">Total Beneficiaries</th><td><?php echo e(number_format($data['total_beneficiaries'])); ?></td></tr>
                            <tr><th>Pending</th><td><?php echo e(number_format($data['pending'])); ?></td></tr>
                            <tr><th>Submitted</th><td><?php echo e(number_format($data['submitted'])); ?></td></tr>
                            <tr><th>Approved</th><td><?php echo e(number_format($data['approved'])); ?></td></tr>
                            <tr><th>Rejected</th><td><?php echo e(number_format($data['rejected'])); ?></td></tr>
                            <tr><th>Paid</th><td><?php echo e(number_format($data['paid'])); ?></td></tr>
                            <tr><th>Payment Failed</th><td><?php echo e(number_format($data['payment_failed'])); ?></td></tr>
                            <tr><th>Total Amount Disbursed (Rs.)</th><td><?php echo e(number_format($data['total_amount_disbursed'], 2)); ?></td></tr>
                            <tr><th>Total Amount Paid (Rs.)</th><td><?php echo e(number_format($data['total_amount_paid'], 2)); ?></td></tr>
                            <tr><th>Total Funds Allocated (Rs.)</th><td><?php echo e(number_format($data['total_funds_allocated'], 2)); ?></td></tr>
                            <tr><th>Districts</th><td><?php echo e($data['districts_count']); ?></td></tr>
                            <tr><th>Schemes</th><td><?php echo e($data['schemes_count']); ?></td></tr>
                            <tr><th>Open Phases</th><td><?php echo e($data['phases_open']); ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/reports/executive-summary.blade.php ENDPATH**/ ?>