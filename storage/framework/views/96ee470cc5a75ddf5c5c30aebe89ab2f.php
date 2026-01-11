

<?php $__env->startSection('title', config('app.name') . ' - Settings'); ?>
<?php $__env->startSection('page_title', 'Settings'); ?>
<?php $__env->startSection('breadcrumb', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Change Password</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="<?php echo e(route('settings.update-password')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" required>
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                            <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Security Tips</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="ti-lock text-primary me-2"></i>
                        <strong>Use a strong password</strong>
                        <p class="text-muted small mb-0">Include uppercase, lowercase, numbers, and special characters</p>
                    </li>
                    <li class="mb-3">
                        <i class="ti-reload text-primary me-2"></i>
                        <strong>Change regularly</strong>
                        <p class="text-muted small mb-0">Update your password every 90 days for better security</p>
                    </li>
                    <li class="mb-3">
                        <i class="ti-shield text-primary me-2"></i>
                        <strong>Keep it private</strong>
                        <p class="text-muted small mb-0">Never share your password with anyone</p>
                    </li>
                    <li>
                        <i class="ti-key text-primary me-2"></i>
                        <strong>Unique password</strong>
                        <p class="text-muted small mb-0">Don't reuse passwords from other accounts</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/settings/show.blade.php ENDPATH**/ ?>