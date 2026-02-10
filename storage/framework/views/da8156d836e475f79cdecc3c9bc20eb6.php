

<?php $__env->startSection('title', config('app.name') . ' - Add User'); ?>
<?php $__env->startSection('page_title', 'Add User'); ?>
<?php $__env->startSection('breadcrumb', 'Add User'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New User</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="<?php echo e(route('users.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
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
                            <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
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
                            <label class="form-label">Password <span class="text-danger">*</span></label>
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
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="">Select Role</option>
                                <?php if(auth()->user()->isSuperAdmin()): ?>
                                    <option value="super_admin" <?php echo e(old('role') === 'super_admin' ? 'selected' : ''); ?>>Super Admin</option>
                                    <option value="administrator_hq" <?php echo e(old('role') === 'administrator_hq' ? 'selected' : ''); ?>>Administrator HQ</option>
                                    <option value="institution" <?php echo e(old('role') === 'institution' ? 'selected' : ''); ?>>Institution</option>
                                <?php endif; ?>
                                <option value="district_user" <?php echo e(old('role') === 'district_user' ? 'selected' : ''); ?>>District User</option>
                            </select>
                            <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if(auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin()): ?>
                                <small class="text-muted">You can only create district users.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3" id="districtField" style="display: none;">
                            <label class="form-label">District <span class="text-danger">*</span></label>
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">Select District</option>
                                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($district->id); ?>" <?php echo e(old('district_id') == $district->id ? 'selected' : ''); ?>>
                                        <?php echo e($district->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['district_id'];
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
                        <div class="col-md-6 mb-3" id="institutionField" style="display: none;">
                            <label class="form-label">Institution <span class="text-danger">*</span></label>
                            <select name="institution_id" id="institution_id" class="form-control select2-institution">
                                <option value="">Select Institution</option>
                                <?php if(isset($institutions)): ?>
                                    <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($institution->id); ?>"
                                            data-district-id="<?php echo e($institution->district_id); ?>"
                                            <?php echo e(old('institution_id') == $institution->id ? 'selected' : ''); ?>>
                                            <?php echo e($institution->name); ?> (<?php echo e($institution->district->name ?? 'N/A'); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['institution_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Only institutions within the selected district will be available.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<?php
    $institutionOptions = isset($institutions)
        ? $institutions->map(function ($institution) {
            return [
                'id' => $institution->id,
                'text' => $institution->name . ' (' . ($institution->district->name ?? 'N/A') . ')',
                'district_id' => $institution->district_id,
            ];
        })->values()
        : collect();
?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const allInstitutions = <?php echo json_encode($institutionOptions, 15, 512) ?>;

    $(document).ready(function() {
        // Initialize Select2 for institution dropdown
        $('.select2-institution').select2({
            placeholder: 'Select Institution',
            width: '100%'
        });

        $('#role').on('change', function() {
            const role = $(this).val();
            if (role === 'district_user' || role === 'institution') {
                $('#districtField').show();
                $('#district_id').prop('required', true);
            } else {
                $('#districtField').hide();
                $('#district_id').prop('required', false);
                $('#district_id').val('');
            }

            if (role === 'institution') {
                $('#institutionField').show();
                $('#institution_id').prop('required', true);
            } else {
                $('#institutionField').hide();
                $('#institution_id').prop('required', false);
                $('#institution_id').val('');
            }

            filterInstitutionsByDistrict();
        });

        function filterInstitutionsByDistrict() {
            const selectedDistrictId = $('#district_id').val();
            const role = $('#role').val();

            if (role === 'institution') {
                $('#institutionField').show();
                $('#institution_id').prop('required', true);

                const hasDistrict = !!selectedDistrictId;
                $('#institution_id').prop('disabled', !hasDistrict);

                // Clear current options and rebuild based on district
                const $inst = $('#institution_id');
                $inst.empty();
                $inst.append(new Option('Select Institution', '', false, false));

                if (hasDistrict) {
                    allInstitutions.forEach(function (inst) {
                        if (inst.district_id == selectedDistrictId) {
                            const opt = new Option(inst.text, inst.id, false, false);
                            $inst.append(opt);
                        }
                    });
                }

                $inst.val('').trigger('change.select2');
            }
        }

        $('#district_id').on('change', function () {
            // Reset selected institution when district changes
            $('#institution_id').val('');
            filterInstitutionsByDistrict();
        });

        // Trigger on page load if old value exists
        if ($('#role').val() === 'district_user' || $('#role').val() === 'institution') {
            $('#districtField').show();
            $('#district_id').prop('required', true);
        }
        if ($('#role').val() === 'institution') {
            $('#institutionField').show();
            $('#institution_id').prop('required', true);
            filterInstitutionsByDistrict();
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/users/create.blade.php ENDPATH**/ ?>