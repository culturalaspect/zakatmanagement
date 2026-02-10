<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beneficiary Status Report</title>
    <style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px;} .header{text-align:center;margin-bottom:20px;border-bottom:2px solid #000;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:8px;} th{background:#333;color:#fff;} .footer{margin-top:20px;text-align:center;font-size:10px;color:#666;}</style>
</head>
<body>
    <div class="header"><h1>Beneficiary Status Report</h1><p>Generated: {{ date('d M Y H:i') }}</p></div>
    <table>
        <thead><tr><th>Status</th><th>Count</th><th>Total Amount (Rs.)</th></tr></thead>
        <tbody>
            @foreach($stats as $status => $s)
                <tr><td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td><td>{{ number_format($s['count']) }}</td><td>{{ number_format($s['amount'], 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Zakat Management System - {{ date('Y-m-d H:i:s') }}</div>
</body>
</html>
