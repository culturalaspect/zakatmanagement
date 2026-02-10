

<?php $__env->startSection('title', config('app.name') . ' - Add Institution'); ?>
<?php $__env->startSection('page_title', 'Add Institution'); ?>
<?php $__env->startSection('breadcrumb', 'Add Institution'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add New Institution</h3>
                        <p class="text-muted mb-0">A unique code will be automatically generated if not provided</p>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="<?php echo e(route('institutions.store')); ?>" method="POST" id="institutionForm">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Institution Name <span class="text-danger">*</span></label>
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
                            <label class="form-label">Institution Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Select Type</option>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('type') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['type'];
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
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" value="<?php echo e(old('code')); ?>" placeholder="Leave empty to auto-generate">
                            <small class="text-muted">Leave empty to auto-generate based on type</small>
                            <?php $__errorArgs = ['code'];
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
                            <label class="form-label">Registration Number</label>
                            <input type="text" name="registration_number" class="form-control" value="<?php echo e(old('registration_number')); ?>">
                            <?php $__errorArgs = ['registration_number'];
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

                    <hr class="my-4">
                    <h5 class="mb-3">Address Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">District</label>
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tehsil</label>
                            <select name="tehsil_id" id="tehsil_id" class="form-control">
                                <option value="">Select Tehsil</option>
                                <?php $__currentLoopData = $tehsils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tehsil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tehsil->id); ?>" 
                                        data-district-id="<?php echo e($tehsil->district_id); ?>"
                                        <?php echo e(old('tehsil_id') == $tehsil->id ? 'selected' : ''); ?>>
                                    <?php echo e($tehsil->name); ?> (<?php echo e($tehsil->district->name); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['tehsil_id'];
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
                            <label class="form-label">Union Council</label>
                            <select name="union_council_id" id="union_council_id" class="form-control">
                                <option value="">Select Union Council</option>
                                <?php $__currentLoopData = $unionCouncils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($uc->id); ?>" 
                                        data-tehsil-id="<?php echo e($uc->tehsil_id); ?>"
                                        <?php echo e(old('union_council_id') == $uc->id ? 'selected' : ''); ?>>
                                    <?php echo e($uc->name); ?> (<?php echo e($uc->tehsil->name); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['union_council_id'];
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
                            <label class="form-label">Village</label>
                            <select name="village_id" id="village_id" class="form-control">
                                <option value="">Select Village</option>
                                <?php $__currentLoopData = $villages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $village): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($village->id); ?>" 
                                        data-union-council-id="<?php echo e($village->union_council_id); ?>"
                                        <?php echo e(old('village_id') == $village->id ? 'selected' : ''); ?>>
                                    <?php echo e($village->name); ?> (<?php echo e($village->unionCouncil->name); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['village_id'];
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
                            <label class="form-label">Mohalla</label>
                            <select name="mohalla_id" id="mohalla_id" class="form-control">
                                <option value="">Select Mohalla</option>
                                <?php $__currentLoopData = $mohallas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mohalla): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($mohalla->id); ?>" 
                                        data-village-id="<?php echo e($mohalla->village_id); ?>"
                                        <?php echo e(old('mohalla_id') == $mohalla->id ? 'selected' : ''); ?>>
                                    <?php echo e($mohalla->name); ?> (<?php echo e($mohalla->village->name); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['mohalla_id'];
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
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Additional address details"><?php echo e(old('address')); ?></textarea>
                            <?php $__errorArgs = ['address'];
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

                    <hr class="my-4">
                    <h5 class="mb-3">Contact Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Principal/Director Name</label>
                            <input type="text" name="principal_director_name" class="form-control" value="<?php echo e(old('principal_director_name')); ?>">
                            <?php $__errorArgs = ['principal_director_name'];
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
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>" placeholder="e.g., 0355-1234567">
                            <?php $__errorArgs = ['phone'];
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
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" placeholder="e.g., info@institution.com">
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
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="<?php echo e(route('institutions.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Filter tehsils based on district
        $('#district_id').on('change', function() {
            const districtId = $(this).val();
            $('#tehsil_id option').show();
            if (districtId) {
                $('#tehsil_id option').not('[value=""]').not('[data-district-id="' + districtId + '"]').hide();
                $('#tehsil_id').val('').trigger('change');
            }
        });

        // Filter union councils based on tehsil
        $('#tehsil_id').on('change', function() {
            const tehsilId = $(this).val();
            $('#union_council_id option').show();
            if (tehsilId) {
                $('#union_council_id option').not('[value=""]').not('[data-tehsil-id="' + tehsilId + '"]').hide();
                $('#union_council_id').val('').trigger('change');
            }
        });

        // Filter villages based on union council
        $('#union_council_id').on('change', function() {
            const unionCouncilId = $(this).val();
            $('#village_id option').show();
            if (unionCouncilId) {
                $('#village_id option').not('[value=""]').not('[data-union-council-id="' + unionCouncilId + '"]').hide();
                $('#village_id').val('').trigger('change');
            }
        });

        // Filter mohallas based on village
        $('#village_id').on('change', function() {
            const villageId = $(this).val();
            $('#mohalla_id option').show();
            if (villageId) {
                $('#mohalla_id option').not('[value=""]').not('[data-village-id="' + villageId + '"]').hide();
                $('#mohalla_id').val('').trigger('change');
            }
        });

        // Initialize on page load
        $('#district_id').trigger('change');
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/institutions/create.blade.php ENDPATH**/ ?>