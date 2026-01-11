

<?php $__env->startSection('title', config('app.name') . ' - Edit Beneficiary'); ?>
<?php $__env->startSection('page_title', 'Edit Beneficiary'); ?>
<?php $__env->startSection('breadcrumb', 'Edit Beneficiary'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Beneficiary</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="<?php echo e(route('beneficiaries.update', $beneficiary)); ?>" method="POST" id="beneficiaryForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phase <span class="text-danger">*</span></label>
                            <select name="phase_id" id="phase_id" class="form-control" required>
                                <option value="">Select Phase</option>
                                <?php $__currentLoopData = $phases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($phase->id); ?>" 
                                        data-allocation="<?php echo e($phase->installment->fundAllocation->id ?? ''); ?>"
                                        <?php echo e(old('phase_id', $beneficiary->phase_id) == $phase->id ? 'selected' : ''); ?>>
                                    <?php echo e($phase->name); ?> - <?php echo e($phase->installment->fundAllocation->financialYear->name ?? ''); ?> (<?php echo e($phase->district->name ?? ''); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['phase_id'];
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
                            <label class="form-label">Scheme <span class="text-danger">*</span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control" required>
                                <option value="">Select Scheme</option>
                                <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($scheme->id); ?>" 
                                        data-has-age="<?php echo e($scheme->has_age_restriction ? '1' : '0'); ?>" 
                                        data-min-age="<?php echo e($scheme->minimum_age ?? ''); ?>"
                                        <?php echo e(old('scheme_id', $beneficiary->scheme_id) == $scheme->id ? 'selected' : ''); ?>>
                                    <?php echo e($scheme->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['scheme_id'];
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
                        <div class="col-md-6 mb-3" id="schemeCategoryDiv" style="display: none;">
                            <label class="form-label">Scheme Category <span class="text-danger">*</span></label>
                            <select name="scheme_category_id" id="scheme_category_id" class="form-control">
                                <option value="">Select Category</option>
                            </select>
                            <?php $__errorArgs = ['scheme_category_id'];
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
                            <label class="form-label">Local Zakat Committee <span class="text-danger">*</span></label>
                            <select name="local_zakat_committee_id" class="form-control" required>
                                <option value="">Select Committee</option>
                                <?php $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($committee->id); ?>" <?php echo e(old('local_zakat_committee_id', $beneficiary->local_zakat_committee_id) == $committee->id ? 'selected' : ''); ?>>
                                    <?php echo e($committee->name); ?> (<?php echo e($committee->district->name ?? ''); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['local_zakat_committee_id'];
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
                    
                    <hr>
                    <h5>Beneficiary Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CNIC <span class="text-danger">*</span></label>
                            <input type="text" name="cnic" class="form-control" value="<?php echo e(old('cnic', $beneficiary->cnic)); ?>" required>
                            <?php $__errorArgs = ['cnic'];
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
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo e(old('full_name', $beneficiary->full_name)); ?>" required>
                            <?php $__errorArgs = ['full_name'];
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
                            <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                            <input type="text" name="father_husband_name" class="form-control" value="<?php echo e(old('father_husband_name', $beneficiary->father_husband_name)); ?>" required>
                            <?php $__errorArgs = ['father_husband_name'];
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
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" value="<?php echo e(old('mobile_number', $beneficiary->mobile_number)); ?>">
                            <?php $__errorArgs = ['mobile_number'];
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
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?php echo e(old('date_of_birth', $beneficiary->date_of_birth ? $beneficiary->date_of_birth->format('Y-m-d') : '')); ?>" required>
                            <?php $__errorArgs = ['date_of_birth'];
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
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo e(old('gender', $beneficiary->gender) == 'male' ? 'selected' : ''); ?>>Male</option>
                                <option value="female" <?php echo e(old('gender', $beneficiary->gender) == 'female' ? 'selected' : ''); ?>>Female</option>
                                <option value="other" <?php echo e(old('gender', $beneficiary->gender) == 'other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <?php $__errorArgs = ['gender'];
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
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" value="<?php echo e(old('amount', $beneficiary->amount)); ?>" step="0.01" min="0" required>
                            <?php $__errorArgs = ['amount'];
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
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requires_representative" id="requires_representative" value="1" <?php echo e(old('requires_representative', $beneficiary->requires_representative) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="requires_representative">Requires Representative</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="representativeDiv" style="display: <?php echo e(old('requires_representative', $beneficiary->requires_representative) ? 'block' : 'none'); ?>;">
                        <hr>
                        <h5>Representative Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CNIC <span class="text-danger">*</span></label>
                                <input type="text" name="representative[cnic]" class="form-control" value="<?php echo e(old('representative.cnic', $beneficiary->representative->cnic ?? '')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="representative[full_name]" class="form-control" value="<?php echo e(old('representative.full_name', $beneficiary->representative->full_name ?? '')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father/Husband Name <span class="text-danger">*</span></label>
                                <input type="text" name="representative[father_husband_name]" class="form-control" value="<?php echo e(old('representative.father_husband_name', $beneficiary->representative->father_husband_name ?? '')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="representative[mobile_number]" class="form-control" value="<?php echo e(old('representative.mobile_number', $beneficiary->representative->mobile_number ?? '')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="representative[date_of_birth]" class="form-control" value="<?php echo e(old('representative.date_of_birth', $beneficiary->representative && $beneficiary->representative->date_of_birth ? $beneficiary->representative->date_of_birth->format('Y-m-d') : '')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="representative[gender]" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo e(old('representative.gender', $beneficiary->representative->gender ?? '') == 'male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="female" <?php echo e(old('representative.gender', $beneficiary->representative->gender ?? '') == 'female' ? 'selected' : ''); ?>>Female</option>
                                    <option value="other" <?php echo e(old('representative.gender', $beneficiary->representative->gender ?? '') == 'other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                <input type="text" name="representative[relationship]" class="form-control" value="<?php echo e(old('representative.relationship', $beneficiary->representative->relationship ?? '')); ?>" placeholder="e.g., Father, Mother, Brother">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Beneficiary</button>
                            <a href="<?php echo e(route('beneficiaries.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Load scheme categories when scheme is selected
        $('#scheme_id').change(function() {
            const schemeId = $(this).val();
            if (schemeId) {
                $.ajax({
                    url: '<?php echo e(route("beneficiaries.scheme-categories", ":id")); ?>'.replace(':id', schemeId),
                    type: 'GET',
                    success: function(data) {
                        const categorySelect = $('#scheme_category_id');
                        categorySelect.empty();
                        if (data.length > 0) {
                            categorySelect.append('<option value="">Select Category</option>');
                            $('#schemeCategoryDiv').show();
                            data.forEach(function(category) {
                                const selected = category.id == <?php echo e($beneficiary->scheme_category_id ?? 0); ?> ? 'selected' : '';
                                categorySelect.append(`<option value="${category.id}" data-amount="${category.amount}" ${selected}>${category.name} (Rs. ${category.amount})</option>`);
                            });
                        } else {
                            $('#schemeCategoryDiv').hide();
                        }
                    }
                });
            } else {
                $('#schemeCategoryDiv').hide();
            }
        });
        
        // Trigger change on page load if scheme is already selected
        if ($('#scheme_id').val()) {
            $('#scheme_id').trigger('change');
        }
        
        // Auto-set amount when category is selected
        $('#scheme_category_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const amount = selectedOption.data('amount');
            if (amount) {
                $('#amount').val(amount);
            }
        });
        
        // Show/hide representative section
        $('#requires_representative').change(function() {
            if ($(this).is(':checked')) {
                $('#representativeDiv').show();
            } else {
                $('#representativeDiv').hide();
            }
        });
        
        // Check age and auto-require representative if needed
        $('#date_of_birth').change(function() {
            const dob = new Date($(this).val());
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            if (age < 18) {
                $('#requires_representative').prop('checked', true).trigger('change');
            }
        });
        
        // Trigger age check on page load
        if ($('#date_of_birth').val()) {
            $('#date_of_birth').trigger('change');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/beneficiaries/edit.blade.php ENDPATH**/ ?>