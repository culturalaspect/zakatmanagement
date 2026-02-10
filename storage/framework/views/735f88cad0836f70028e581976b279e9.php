<?php $__env->startSection('title', config('app.name') . ' - District-wise Report'); ?>
<?php $__env->startSection('page_title', 'District-wise Report'); ?>
<?php $__env->startSection('breadcrumb', 'Reports / District-wise'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="<?php echo e(route('reports.district-wise')); ?>" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">District</label>
                        <select name="district_id" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($d->id); ?>" <?php echo e(request('district_id') == $d->id ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fund Allocation</label>
                        <select name="fund_allocation_id" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($fa->id); ?>" <?php echo e(request('fund_allocation_id') == $fa->id ? 'selected' : ''); ?>>
                                    <?php echo e($fa->financialYear->name ?? ''); ?> - <?php echo e($fa->date?->format('d M Y')); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Apply</button>
                        <?php echo $__env->make('reports.partials.export-buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>District</th>
                                <th class="text-end">Beneficiaries</th>
                                <th class="text-end">Total Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $districtStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($name); ?></td>
                                    <td class="text-end"><?php echo e(number_format($stat['count'])); ?></td>
                                    <td class="text-end"><?php echo e(number_format($stat['amount'], 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3" class="text-center">No data</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if($districtStats->isNotEmpty()): ?>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-end"><?php echo e(number_format($districtStats->sum(fn($s) => $s['count']))); ?></th>
                                <th class="text-end"><?php echo e(number_format($districtStats->sum(fn($s) => $s['amount']), 2)); ?></th>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/reports/district-wise.blade.php ENDPATH**/ ?>