<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Report</title>
    <style>body{font-family:Arial,sans-serif;font-size:11px;margin:15px;} .header{text-align:center;margin-bottom:15px;border-bottom:2px solid #000;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:5px;} th{background:#333;color:#fff;} .summary{margin-bottom:15px;} .summary div{display:inline-block;margin-right:20px;} .footer{margin-top:15px;text-align:center;font-size:10px;color:#666;}</style>
</head>
<body>
    <div class="header"><h1>Payment Report</h1><p>Generated: {{ date('d M Y H:i') }}</p></div>
    <div class="summary">
        <strong>Summary:</strong>
        Paid: {{ $summary['paid_count'] }} (Rs. {{ number_format($summary['paid_amount'], 2) }}) |
        Pending: {{ $summary['pending_count'] }} (Rs. {{ number_format($summary['pending_amount'], 2) }}) |
        Failed: {{ $summary['failed_count'] }} (Rs. {{ number_format($summary['failed_amount'], 2) }})
    </div>
    <table>
        <thead><tr><th>Status</th><th>CNIC</th><th>Name</th><th>District</th><th>Scheme</th><th>Amount (Rs.)</th><th>JazzCash Status</th><th>Remarks</th></tr></thead>
        <tbody>
            @foreach($paid as $b)
                <tr><td>Paid</td><td>{{ $b->cnic ?? $b->representative?->cnic }}</td><td>{{ $b->full_name }}</td><td>{{ $b->phase->district->name ?? '' }}</td><td>{{ $b->scheme->name ?? '' }}</td><td>{{ number_format($b->amount, 2) }}</td><td>{{ $b->jazzcash_status ?? 'SUCCESS' }}</td><td>{{ $b->jazzcash_reason ?? '' }}</td></tr>
            @endforeach
            @foreach($approved as $b)
                <tr><td>Pending</td><td>{{ $b->cnic ?? $b->representative?->cnic }}</td><td>{{ $b->full_name }}</td><td>{{ $b->phase->district->name ?? '' }}</td><td>{{ $b->scheme->name ?? '' }}</td><td>{{ number_format($b->amount, 2) }}</td><td>-</td><td></td></tr>
            @endforeach
            @foreach($paymentFailed as $b)
                <tr><td>Failed</td><td>{{ $b->cnic ?? $b->representative?->cnic }}</td><td>{{ $b->full_name }}</td><td>{{ $b->phase->district->name ?? '' }}</td><td>{{ $b->scheme->name ?? '' }}</td><td>{{ number_format($b->amount, 2) }}</td><td>{{ $b->jazzcash_status ?? '' }}</td><td>{{ $b->jazzcash_reason ?? '' }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Zakat Management System - {{ date('Y-m-d H:i:s') }}</div>
</body>
</html>
