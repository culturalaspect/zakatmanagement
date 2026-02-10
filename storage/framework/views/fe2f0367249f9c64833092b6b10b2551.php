

<?php $__env->startSection('title', config('app.name') . ' - View Scheme'); ?>
<?php $__env->startSection('page_title', 'View Scheme'); ?>
<?php $__env->startSection('breadcrumb', 'View Scheme'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Scheme Details</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('schemes.edit', $scheme)); ?>" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p><?php echo e($scheme->name); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Percentage:</strong>
                        <p><?php echo e(number_format($scheme->percentage, 2)); ?>%</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <strong>Description:</strong>
                        <p><?php echo e($scheme->description ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Age Restriction:</strong>
                        <p>
                            <?php if($scheme->has_age_restriction): ?>
                                <span class="badge bg-info">Yes (Minimum Age: <?php echo e($scheme->minimum_age); ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                            <?php if($scheme->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <?php if($scheme->categories->count() > 0): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Scheme Categories</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $scheme->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($category->name); ?></td>
                                        <td>Rs. <?php echo e(number_format($category->amount, 2)); ?></td>
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
                        <a href="<?php echo e(route('schemes.index')); ?>" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/schemes/show.blade.php ENDPATH**/ ?>