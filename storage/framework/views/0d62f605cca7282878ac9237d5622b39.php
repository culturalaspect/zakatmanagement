

<?php $__env->startSection('title', config('app.name') . ' - Edit Scheme'); ?>
<?php $__env->startSection('page_title', 'Edit Scheme'); ?>
<?php $__env->startSection('breadcrumb', 'Edit Scheme'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Scheme</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="<?php echo e(route('schemes.update', $scheme)); ?>" method="POST" id="schemeForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $scheme->name)); ?>" required>
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
                            <label class="form-label">Percentage <span class="text-danger">*</span></label>
                            <input type="number" name="percentage" class="form-control" value="<?php echo e(old('percentage', $scheme->percentage)); ?>" step="0.01" min="0" max="100" required>
                            <?php $__errorArgs = ['percentage'];
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $scheme->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_age_restriction" id="has_age_restriction" value="1" <?php echo e(old('has_age_restriction', $scheme->has_age_restriction) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="has_age_restriction">Has Age Restriction</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="minAgeDiv" style="display: <?php echo e(old('has_age_restriction', $scheme->has_age_restriction) ? 'block' : 'none'); ?>;">
                            <label class="form-label">Minimum Age</label>
                            <input type="number" name="minimum_age" class="form-control" value="<?php echo e(old('minimum_age', $scheme->minimum_age)); ?>" min="0">
                            <?php $__errorArgs = ['minimum_age'];
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
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', $scheme->is_active) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_institutional" id="is_institutional" value="1" <?php echo e(old('is_institutional', $scheme->is_institutional) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_institutional">Institutional Stipend</label>
                                <small class="form-text text-muted d-block">Check if this scheme is for institutions (schools, hospitals, madarsas)</small>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3" id="institutionalTypeDiv" style="display: <?php echo e(old('is_institutional', $scheme->is_institutional) ? 'block' : 'none'); ?>;">
                            <label class="form-label">Institutional Type <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="institutional_type" id="institutional_educational" value="educational" <?php echo e(old('institutional_type', $scheme->institutional_type) == 'educational' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="institutional_educational">Educational Stipend</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="institutional_type" id="institutional_madarsa" value="madarsa" <?php echo e(old('institutional_type', $scheme->institutional_type) == 'madarsa' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="institutional_madarsa">Madarsa Stipend</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="institutional_type" id="institutional_health" value="health" <?php echo e(old('institutional_type', $scheme->institutional_type) == 'health' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="institutional_health">Health Stipend</label>
                            </div>
                            <?php $__errorArgs = ['institutional_type'];
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
                                <input class="form-check-input" type="checkbox" name="allows_representative" id="allows_representative" value="1" <?php echo e(old('allows_representative', $scheme->allows_representative) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="allows_representative">Allows Representative</label>
                                <small class="form-text text-muted d-block">Check if beneficiaries can have a representative to receive the stipend</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Beneficiary Required Fields</h5>
                    <p class="text-muted mb-3">Select which fields are mandatory when adding beneficiaries for this scheme:</p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_cnic" value="cnic" <?php echo e(in_array('cnic', old('beneficiary_required_fields', $scheme->beneficiary_required_fields ?? [])) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="benef_cnic">CNIC</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_full_name" value="full_name" <?php echo e(in_array('full_name', old('beneficiary_required_fields', $scheme->beneficiary_required_fields ?? [])) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="benef_full_name">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_father_husband_name" value="father_husband_name" <?php echo e(in_array('father_husband_name', old('beneficiary_required_fields', $scheme->beneficiary_required_fields ?? [])) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="benef_father_husband_name">Father / Husband Name</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_mobile_number" value="mobile_number" <?php echo e(in_array('mobile_number', old('beneficiary_required_fields', $scheme->beneficiary_required_fields ?? [])) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="benef_mobile_number">Mobile Number</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_date_of_birth" value="date_of_birth" <?php echo e(in_array('date_of_birth', old('beneficiary_required_fields', $scheme->beneficiary_required_fields ?? [])) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="benef_date_of_birth">Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beneficiary_required_fields[]" id="benef_gender" value="gender" <?php echo e(in_array('gender', old('beneficiary_required_fields', $scheme->beneficiary_required_fields ?? [])) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="benef_gender">Gender</label>
                            </div>
                        </div>
                    </div>

                    <div id="representativeFieldsSection" style="display: <?php echo e(old('allows_representative', $scheme->allows_representative) ? 'block' : 'none'); ?>;">
                        <hr class="my-4">
                        <h5 class="mb-3">Representative Required Fields</h5>
                        <p class="text-muted mb-3">Select which fields are mandatory for the representative when adding beneficiaries for this scheme:</p>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_cnic" value="cnic" <?php echo e(in_array('cnic', old('representative_required_fields', $scheme->representative_required_fields ?? [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="rep_cnic">CNIC</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_full_name" value="full_name" <?php echo e(in_array('full_name', old('representative_required_fields', $scheme->representative_required_fields ?? [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="rep_full_name">Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_father_husband_name" value="father_husband_name" <?php echo e(in_array('father_husband_name', old('representative_required_fields', $scheme->representative_required_fields ?? [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="rep_father_husband_name">Father / Husband Name</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_mobile_number" value="mobile_number" <?php echo e(in_array('mobile_number', old('representative_required_fields', $scheme->representative_required_fields ?? [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="rep_mobile_number">Mobile Number</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_date_of_birth" value="date_of_birth" <?php echo e(in_array('date_of_birth', old('representative_required_fields', $scheme->representative_required_fields ?? [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="rep_date_of_birth">Date of Birth</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="representative_required_fields[]" id="rep_gender" value="gender" <?php echo e(in_array('gender', old('representative_required_fields', $scheme->representative_required_fields ?? [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="rep_gender">Gender</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Scheme Categories</h5>
                            <button type="button" class="btn btn-sm btn-secondary" id="addCategory">
                                <i class="ti-plus"></i> Add Category
                            </button>
                        </div>
                    </div>
                    
                    <div id="categoriesContainer">
                        <?php $__currentLoopData = $scheme->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row mb-2 category-row">
                            <div class="col-md-5">
                                <input type="text" name="categories[<?php echo e($index); ?>][name]" class="form-control" value="<?php echo e($category->name); ?>" placeholder="Category Name" required>
                            </div>
                            <div class="col-md-5">
                                <input type="number" name="categories[<?php echo e($index); ?>][amount]" class="form-control" value="<?php echo e($category->amount); ?>" placeholder="Amount" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-category">
                                    <i class="ti-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?php echo e(route('schemes.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    let categoryIndex = <?php echo e($scheme->categories->count()); ?>;
    $(document).ready(function() {
        $('#has_age_restriction').change(function() {
            if ($(this).is(':checked')) {
                $('#minAgeDiv').show();
            } else {
                $('#minAgeDiv').hide();
            }
        });
        
        $('#is_institutional').change(function() {
            if ($(this).is(':checked')) {
                $('#institutionalTypeDiv').show();
                $('input[name="institutional_type"]').prop('required', true);
            } else {
                $('#institutionalTypeDiv').hide();
                $('input[name="institutional_type"]').prop('required', false);
                $('input[name="institutional_type"]').prop('checked', false);
            }
        });
        
        $('#allows_representative').change(function() {
            if ($(this).is(':checked')) {
                $('#representativeFieldsSection').show();
            } else {
                $('#representativeFieldsSection').hide();
                // Uncheck all representative fields
                $('input[name="representative_required_fields[]"]').prop('checked', false);
            }
        });
        
        $('#addCategory').click(function() {
            const categoryHtml = `
                <div class="row mb-2 category-row">
                    <div class="col-md-5">
                        <input type="text" name="categories[${categoryIndex}][name]" class="form-control" placeholder="Category Name" required>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="categories[${categoryIndex}][amount]" class="form-control" placeholder="Amount" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-danger remove-category">
                            <i class="ti-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#categoriesContainer').append(categoryHtml);
            categoryIndex++;
        });
        
        $(document).on('click', '.remove-category', function() {
            $(this).closest('.category-row').remove();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/schemes/edit.blade.php ENDPATH**/ ?>