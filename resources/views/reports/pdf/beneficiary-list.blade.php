<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beneficiary List</title>
    <style>body{font-family:Arial,sans-serif;font-size:10px;margin:12px;} .header{text-align:center;margin-bottom:12px;border-bottom:2px solid #000;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:4px;} th{background:#333;color:#fff;} .footer{margin-top:12px;text-align:center;font-size:9px;color:#666;} page-break-inside:avoid;</style>
</head>
<body>
    <div class="header"><h1>Beneficiary List Report</h1><p>Generated: {{ date('d M Y H:i') }}</p></div>
    <table>
        <thead>
            <tr>
                <th>Sr</th><th>CNIC</th><th>Name</th><th>Father/Husband</th><th>Mobile</th><th>District</th><th>Scheme</th><th>Amount (Rs.)</th><th>Status</th><th>LZC/Institution</th><th>Submitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beneficiaries as $i => $b)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $b->cnic ?? $b->representative?->cnic ?? '-' }}</td>
                    <td>{{ $b->full_name }}</td>
                    <td>{{ $b->father_husband_name ?? '-' }}</td>
                    <td>{{ $b->mobile_number ?? '-' }}</td>
                    <td>{{ $b->phase->district->name ?? '' }}</td>
                    <td>{{ $b->scheme->name ?? '' }}</td>
                    <td>{{ number_format($b->amount, 2) }}</td>
                    <td>{{ $b->status }}</td>
                    <td>{{ $b->localZakatCommittee->name ?? $b->institution->name ?? '-' }}</td>
                    <td>{{ $b->submitted_at?->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Zakat Management System - {{ date('Y-m-d H:i:s') }} - Total: {{ $beneficiaries->count() }} records</div>
</body>
</html>
