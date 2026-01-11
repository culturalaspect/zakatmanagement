<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installment #{{ $installment->installment_number }} - Disbursement Plan</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: white;
        }
        .print-container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 8px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-weight: bold;
            font-size: 10px;
            color: #666;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 12px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        thead {
            background-color: #333 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
        }
        th {
            font-weight: bold;
            background-color: #333 !important;
            color: #fff !important;
        }
        tbody tr {
            page-break-inside: avoid;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            background-color: #d1ecf1 !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .print-container {
                padding: 0;
            }
            @page {
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="header">
            <h1>ZAKAT MANAGEMENT SYSTEM</h1>
            <h2>Installment #{{ $installment->installment_number }} - Disbursement Plan</h2>
        </div>

        <div class="info-section">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Financial Year:</div>
                    <div class="info-value">{{ $installment->fundAllocation->financialYear->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Installment Number:</div>
                    <div class="info-value">{{ $installment->installment_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Installment Amount:</div>
                    <div class="info-value">Rs. {{ number_format($installment->installment_amount, 2) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Release Date:</div>
                    <div class="info-value">{{ $installment->release_date->format('Y-m-d') }}</div>
                </div>
            </div>
        </div>

        @if($installment->districtQuotas->count() > 0)
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 12%;">District</th>
                    <th rowspan="2" style="width: 8%;">Percentage</th>
                    <th rowspan="2" style="width: 10%;">Total Beneficiaries</th>
                    <th colspan="{{ $installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id')->count() }}" style="text-align: center;">Scheme Distributions</th>
                    <th rowspan="2" style="width: 12%;">Total Regular Zakat Fund (Rs.)</th>
                </tr>
                <tr>
                    @php
                        $schemes = $installment->districtQuotas->first()->schemeDistributions->pluck('scheme')->unique('id');
                    @endphp
                    @foreach($schemes as $scheme)
                        <th style="font-size: 9px;">{{ $scheme->percentage }}% - {{ $scheme->name }}<br>(Rs.)</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalBeneficiaries = 0;
                    $grandTotalAmount = 0;
                    $schemeTotals = [];
                @endphp
                @foreach($installment->districtQuotas as $quota)
                    @php
                        $grandTotalBeneficiaries += $quota->total_beneficiaries;
                        $grandTotalAmount += $quota->total_amount;
                        $districtTotal = 0;
                    @endphp
                    <tr>
                        <td><strong>{{ $quota->district->name }}</strong></td>
                        <td>{{ number_format($quota->percentage, 0) }}%</td>
                        <td>{{ number_format($quota->total_beneficiaries, 1) }}</td>
                        @foreach($schemes as $scheme)
                            @php
                                $distribution = $quota->schemeDistributions->where('scheme_id', $scheme->id)->first();
                                $amount = $distribution ? $distribution->amount : 0;
                                $districtTotal += $amount;
                                if (!isset($schemeTotals[$scheme->id])) {
                                    $schemeTotals[$scheme->id] = 0;
                                }
                                $schemeTotals[$scheme->id] += $amount;
                            @endphp
                            <td>{{ number_format($amount, 2) }}</td>
                        @endforeach
                        <td><strong>{{ number_format($districtTotal, 2) }}</strong></td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td><strong>Grand Total:</strong></td>
                    <td>100%</td>
                    <td><strong>{{ number_format($grandTotalBeneficiaries, 1) }}</strong></td>
                    @foreach($schemes as $scheme)
                        <td><strong>{{ number_format($schemeTotals[$scheme->id] ?? 0, 2) }}</strong></td>
                    @endforeach
                    <td><strong>{{ number_format($grandTotalAmount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="text-align: center; padding: 20px;">No disbursement plan configured for this installment.</p>
        @endif

        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>Generated on {{ date('Y-m-d H:i:s') }} by Zakat Management System</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>

