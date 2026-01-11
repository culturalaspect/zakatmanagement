<?php $__env->startSection('title', config('app.name') . ' - Installment Details'); ?>
<?php $__env->startSection('page_title', 'Installment Details'); ?>
<?php $__env->startSection('breadcrumb', 'Installment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Installment #<?php echo e($installment->installment_number); ?> - Disbursement Plan</h3>
                    </div>
                    <div class="header_more_tool no-print">
                        <a href="<?php echo e(route('installments.edit', $installment)); ?>" class="btn btn-primary">
                            <i class="ti-pencil"></i> Edit
                        </a>
                        <a href="<?php echo e(route('installments.print', $installment)); ?>" class="btn btn-info" target="_blank">
                            <i class="ti-printer"></i> Print
                        </a>
                        <a href="<?php echo e(route('installments.pdf', $installment)); ?>" class="btn btn-success" target="_blank">
                            <i class="ti-download"></i> Download PDF
                        </a>
                        <a href="<?php echo e(route('fund-allocations.show', $installment->fundAllocation)); ?>" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Financial Year:</label>
                        <p><?php echo e($installment->fundAllocation->financialYear->name); ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Installment Number:</label>
                        <p><?php echo e($installment->installment_number); ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Installment Amount:</label>
                        <p>Rs. <?php echo e(number_format($installment->installment_amount, 2)); ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Release Date:</label>
                        <p><?php echo e($installment->release_date->format('Y-m-d')); ?></p>
                    </div>
                </div>

                <?php if($installment->districtQuotas->count() > 0): ?>
                <hr>
                <h5 class="mb-3">Disbursement Plan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="text-align: center;">
                        <thead style="background-color: #343a40 !important;">
                            <tr>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">District</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Percentage</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Total Beneficiaries</th>
                                <th colspan="<?php echo e($installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id')->count()); ?>" class="text-center" style="vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Scheme Distributions</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;">Total Regular Zakat Fund (Rs.)</th>
                            </tr>
                            <tr>
                                <?php
                                    $schemes = $installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id');
                                ?>
                                <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th style="text-align: center; vertical-align: middle; color: #ffffff !important; background-color: #343a40 !important;"><?php echo e($scheme->percentage); ?>% - <?php echo e($scheme->name); ?> (Rs.)</th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $grandTotalBeneficiaries = 0;
                                $grandTotalAmount = 0;
                                $schemeTotals = [];
                            ?>
                            <?php $__currentLoopData = $installment->districtQuotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $grandTotalBeneficiaries += $quota->total_beneficiaries;
                                    $grandTotalAmount += $quota->total_amount;
                                    $districtTotal = 0;
                                ?>
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;"><strong><?php echo e($quota->district->name); ?></strong></td>
                                    <td style="text-align: center; vertical-align: middle;"><?php echo e(number_format($quota->percentage, 0)); ?>%</td>
                                    <td style="text-align: center; vertical-align: middle;"><?php echo e(number_format($quota->total_beneficiaries, 1)); ?></td>
                                    <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $distribution = $quota->schemeDistributions->where('scheme_id', $scheme->id)->first();
                                            $amount = $distribution ? $distribution->amount : 0;
                                            $districtTotal += $amount;
                                            if (!isset($schemeTotals[$scheme->id])) {
                                                $schemeTotals[$scheme->id] = 0;
                                            }
                                            $schemeTotals[$scheme->id] += $amount;
                                        ?>
                                        <td style="text-align: center; vertical-align: middle;"><?php echo e(number_format($amount, 2)); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td style="text-align: center; vertical-align: middle;"><strong><?php echo e(number_format($districtTotal, 2)); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-info fw-bold">
                                <td style="text-align: center; vertical-align: middle;"><strong>Grand Total:</strong></td>
                                <td style="text-align: center; vertical-align: middle;">100%</td>
                                <td style="text-align: center; vertical-align: middle;"><strong><?php echo e(number_format($grandTotalBeneficiaries, 1)); ?></strong></td>
                                <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td style="text-align: center; vertical-align: middle;"><strong><?php echo e(number_format($schemeTotals[$scheme->id] ?? 0, 2)); ?></strong></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td style="text-align: center; vertical-align: middle;"><strong><?php echo e(number_format($grandTotalAmount, 2)); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <hr>
                <p class="text-muted">No disbursement plan configured for this installment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/installments/show.blade.php ENDPATH**/ ?>