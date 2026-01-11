<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installment #<?php echo e($installment->installment_number); ?> - Disbursement Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        thead {
            background-color: #333;
            color: #fff;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        tbody tr {
            page-break-inside: avoid;
        }
        .total-row {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ZAKAT MANAGEMENT SYSTEM</h1>
        <h2>Installment #<?php echo e($installment->installment_number); ?> - Disbursement Plan</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Financial Year:</div>
            <div class="info-value"><?php echo e($installment->fundAllocation->financialYear->name); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Installment Number:</div>
            <div class="info-value"><?php echo e($installment->installment_number); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Installment Amount:</div>
            <div class="info-value">Rs. <?php echo e(number_format($installment->installment_amount, 2)); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Release Date:</div>
            <div class="info-value"><?php echo e($installment->release_date->format('Y-m-d')); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Generated On:</div>
            <div class="info-value"><?php echo e(date('Y-m-d H:i:s')); ?></div>
        </div>
    </div>

    <?php if($installment->districtQuotas->count() > 0): ?>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 12%;">District</th>
                <th rowspan="2" style="width: 8%;">Percentage</th>
                <th rowspan="2" style="width: 10%;">Total Beneficiaries</th>
                <th colspan="<?php echo e($installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id')->count()); ?>" style="text-align: center;">Scheme Distributions</th>
                <th rowspan="2" style="width: 12%;">Total Regular Zakat Fund (Rs.)</th>
            </tr>
            <tr>
                <?php
                    $schemes = $installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id');
                ?>
                <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th style="font-size: 9px;"><?php echo e($scheme->percentage); ?>% - <?php echo e($scheme->name); ?><br>(Rs.)</th>
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
                    <td><strong><?php echo e($quota->district->name); ?></strong></td>
                    <td><?php echo e(number_format($quota->percentage, 0)); ?>%</td>
                    <td><?php echo e(number_format($quota->total_beneficiaries, 1)); ?></td>
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
                        <td><?php echo e(number_format($amount, 2)); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <td><strong><?php echo e(number_format($districtTotal, 2)); ?></strong></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr class="total-row">
                <td><strong>Grand Total:</strong></td>
                <td>100%</td>
                <td><strong><?php echo e(number_format($grandTotalBeneficiaries, 1)); ?></strong></td>
                <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td><strong><?php echo e(number_format($schemeTotals[$scheme->id] ?? 0, 2)); ?></strong></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td><strong><?php echo e(number_format($grandTotalAmount, 2)); ?></strong></td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
    <p>No disbursement plan configured for this installment.</p>
    <?php endif; ?>

    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Generated on <?php echo e(date('Y-m-d H:i:s')); ?> by Zakat Management System</p>
    </div>
</body>
</html>

<?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/installments/pdf.blade.php ENDPATH**/ ?>