<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>District-wise Report</title>
    <style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px;} .header{text-align:center;margin-bottom:20px;border-bottom:2px solid #000;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:8px;} th{background:#333;color:#fff;} .footer{margin-top:20px;text-align:center;font-size:10px;color:#666;}</style>
</head>
<body>
    <div class="header"><h1>District-wise Report</h1><p>Generated: {{ date('d M Y H:i') }}</p></div>
    <table>
        <thead><tr><th>District</th><th>Beneficiaries</th><th>Total Amount (Rs.)</th></tr></thead>
        <tbody>
            @foreach($districtStats as $name => $stat)
                <tr><td>{{ $name }}</td><td>{{ number_format($stat['count']) }}</td><td>{{ number_format($stat['amount'], 2) }}</td></tr>
            @endforeach
        </tbody>
        @if($districtStats->isNotEmpty())
        <tfoot><tr><th>Total</th><th>{{ number_format($districtStats->sum(fn($s) => $s['count'])) }}</th><th>{{ number_format($districtStats->sum(fn($s) => $s['amount']), 2) }}</th></tr></tfoot>
        @endif
    </table>
    <div class="footer">Zakat Management System - {{ date('Y-m-d H:i:s') }}</div>
</body>
</html>
