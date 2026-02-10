<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Year Summary</title>
    <style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px;} .header{text-align:center;margin-bottom:20px;border-bottom:2px solid #000;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:8px;} th{background:#333;color:#fff;} .footer{margin-top:20px;text-align:center;font-size:10px;color:#666;}</style>
</head>
<body>
    <div class="header"><h1>Financial Year Summary</h1><p>Generated: {{ date('d M Y H:i') }}</p></div>
    <table>
        <thead>
            <tr><th>Financial Year</th><th>Start</th><th>End</th><th>Total Allocated (Rs.)</th><th>Beneficiaries</th><th>Disbursed (Rs.)</th><th>Paid (Rs.)</th></tr>
        </thead>
        <tbody>
            @foreach($years as $fy)
                <tr>
                    <td>{{ $fy->name }}</td>
                    <td>{{ $fy->start_date?->format('d M Y') }}</td>
                    <td>{{ $fy->end_date?->format('d M Y') }}</td>
                    <td>{{ number_format($fy->total_allocated ?? 0, 2) }}</td>
                    <td>{{ number_format($fy->beneficiaries_count ?? 0) }}</td>
                    <td>{{ number_format($fy->amount_disbursed ?? 0, 2) }}</td>
                    <td>{{ number_format($fy->amount_paid ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Zakat Management System - {{ date('Y-m-d H:i:s') }}</div>
</body>
</html>
