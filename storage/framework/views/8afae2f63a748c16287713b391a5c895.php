

<?php $__env->startSection('title', config('app.name') . ' - View Union Council'); ?>
<?php $__env->startSection('page_title', 'View Union Council'); ?>
<?php $__env->startSection('breadcrumb', 'View Union Council'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Union Council Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('union-councils.edit', $unionCouncil)); ?>" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p><?php echo e($unionCouncil->name); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Tehsil:</strong>
                        <p><?php echo e($unionCouncil->tehsil->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p><?php echo e($unionCouncil->tehsil->district->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            <?php if($unionCouncil->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Villages:</strong>
                        <p><?php echo e($unionCouncil->villages->count() ?? 0); ?></p>
                    </div>
                </div>
                <?php if($unionCouncil->villages->count() > 0): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Villages</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $unionCouncil->villages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $village): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($village->name); ?></td>
                                        <td>
                                            <?php if($village->is_active): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-12">
                        <a href="<?php echo e(route('union-councils.index')); ?>" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/union-councils/show.blade.php ENDPATH**/ ?>