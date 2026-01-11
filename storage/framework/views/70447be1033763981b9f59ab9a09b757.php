

<?php $__env->startSection('title', config('app.name') . ' - My Profile'); ?>
<?php $__env->startSection('page_title', 'My Profile'); ?>
<?php $__env->startSection('breadcrumb', 'My Profile'); ?>

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
                        <h3 class="m-0">Profile Information</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
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
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                            <?php $__errorArgs = ['email'];
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
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="<?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?>" disabled>
                        </div>
                        <?php if($user->district): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">District</label>
                            <input type="text" class="form-control" value="<?php echo e($user->district->name); ?>" disabled>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
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
                        <h3 class="m-0">Account Information</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="text-center mb-4">
                    <?php if($user->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->image)): ?>
                        <img src="<?php echo e(asset('storage/' . $user->image)); ?>" alt="<?php echo e($user->name); ?>" class="profile-avatar" style="width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; object-fit: cover; border: 3px solid #667eea;">
                    <?php else: ?>
                        <div class="profile-avatar" style="width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 48px; color: white; font-weight: bold;">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>
                    <h4 class="mt-3 mb-1"><?php echo e($user->name); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?></p>
                    <?php if($user->district): ?>
                    <p class="text-muted mb-0"><?php echo e($user->district->name); ?></p>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <p class="mb-0"><?php echo e($user->email); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Member Since</label>
                    <p class="mb-0"><?php echo e($user->created_at->format('M d, Y')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/profile/show.blade.php ENDPATH**/ ?>