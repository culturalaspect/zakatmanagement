<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Phase-wise Report</title>
    <style>body{font-family:Arial,sans-serif;font-size:11px;margin:15px;} .header{text-align:center;margin-bottom:15px;border-bottom:2px solid #000;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:6px;} th{background:#333;color:#fff;} .footer{margin-top:15px;text-align:center;font-size:10px;color:#666;}</style>
</head>
<body>
    <div class="header"><h1>Phase-wise Report</h1><p>Generated: {{ date('d M Y H:i') }}</p></div>
    <table>
        <thead>
            <tr><th>Phase</th><th>District</th><th>Scheme</th><th>FY</th><th>Inst#</th><th>Status</th><th>Beneficiaries</th><th>Amount (Rs.)</th></tr>
        </thead>
        <tbody>
            @foreach($phases as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->district->name ?? '' }}</td>
                    <td>{{ $p->scheme->name ?? '' }}</td>
                    <td>{{ $p->installment->fundAllocation->financialYear->name ?? '' }}</td>
                    <td>{{ $p->installment->installment_number ?? '' }}</td>
                    <td>{{ $p->status ?? '' }}</td>
                    <td>{{ number_format($p->beneficiaries_count ?? 0) }}</td>
                    <td>{{ number_format($p->beneficiaries_amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Zakat Management System - {{ date('Y-m-d H:i:s') }}</div>
</body>
</html>
