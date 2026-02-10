<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Summary Report</title>
    <style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px;} .header{text-align:center;margin-bottom:20px;border-bottom:2px solid #000;padding-bottom:10px;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:8px;} th{background:#333;color:#fff;} .footer{margin-top:20px;text-align:center;font-size:10px;color:#666;}</style>
</head>
<body>
    <div class="header">
        <h1>Zakat Management System</h1>
        <h2>Executive Summary Report</h2>
        <p>Generated: <?php echo e(date('d M Y H:i')); ?></p>
    </div>
    <table>
        <tr><th>Metric</th><th>Value</th></tr>
        <tr><td>Total Beneficiaries</td><td><?php echo e(number_format($data['total_beneficiaries'])); ?></td></tr>
        <tr><td>Pending</td><td><?php echo e(number_format($data['pending'])); ?></td></tr>
        <tr><td>Submitted</td><td><?php echo e(number_format($data['submitted'])); ?></td></tr>
        <tr><td>Approved</td><td><?php echo e(number_format($data['approved'])); ?></td></tr>
        <tr><td>Rejected</td><td><?php echo e(number_format($data['rejected'])); ?></td></tr>
        <tr><td>Paid</td><td><?php echo e(number_format($data['paid'])); ?></td></tr>
        <tr><td>Payment Failed</td><td><?php echo e(number_format($data['payment_failed'])); ?></td></tr>
        <tr><td>Total Amount Disbursed (Rs.)</td><td><?php echo e(number_format($data['total_amount_disbursed'], 2)); ?></td></tr>
        <tr><td>Total Amount Paid (Rs.)</td><td><?php echo e(number_format($data['total_amount_paid'], 2)); ?></td></tr>
        <tr><td>Total Funds Allocated (Rs.)</td><td><?php echo e(number_format($data['total_funds_allocated'], 2)); ?></td></tr>
        <tr><td>Districts</td><td><?php echo e($data['districts_count']); ?></td></tr>
        <tr><td>Schemes</td><td><?php echo e($data['schemes_count']); ?></td></tr>
        <tr><td>Open Phases</td><td><?php echo e($data['phases_open']); ?></td></tr>
    </table>
    <div class="footer">Generated on <?php echo e(date('Y-m-d H:i:s')); ?> by Zakat Management System</div>
</body>
</html>
<?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/reports/pdf/executive-summary.blade.php ENDPATH**/ ?>