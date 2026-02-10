<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fund Disbursement Report</title>
    <style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px;} .header{text-align:center;margin-bottom:20px;border-bottom:2px solid #000;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:8px;} th{background:#333;color:#fff;} .footer{margin-top:20px;text-align:center;font-size:10px;color:#666;}</style>
</head>
<body>
    <div class="header"><h1>Fund Disbursement Report</h1><p>Generated: {{ date('d M Y H:i') }}</p></div>
    <table>
        <thead>
            <tr><th>Financial Year</th><th>Date</th><th>Total Allocated (Rs.)</th><th>Installments (Rs.)</th><th>Disbursed (Rs.)</th><th>Paid (Rs.)</th><th>Remaining (Rs.)</th></tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
                <tr>
                    <td>{{ $r['financial_year'] }}</td>
                    <td>{{ $r['date'] }}</td>
                    <td>{{ number_format($r['total_amount'], 2) }}</td>
                    <td>{{ number_format($r['installments_total'], 2) }}</td>
                    <td>{{ number_format($r['disbursed'], 2) }}</td>
                    <td>{{ number_format($r['paid'], 2) }}</td>
                    <td>{{ number_format($r['remaining'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Zakat Management System - {{ date('Y-m-d H:i:s') }}</div>
</body>
</html>
