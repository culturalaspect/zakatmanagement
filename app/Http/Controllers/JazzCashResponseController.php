<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\FinancialYear;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class JazzCashResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdministratorHQ()) {
                abort(403, 'Access denied. Only Super Admin and Administrator HQ can upload JazzCash responses.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $financialYears = FinancialYear::orderBy('name', 'desc')->get();
        return view('jazzcash-response.index', compact('financialYears'));
    }

    public function downloadTemplate(Request $request)
    {
        // No validation needed - just download empty template
        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title (optional - can be filled by user)
        $sheet->setCellValue('A1', 'DESERVING LIST FOR DISTRIBUTION OF ZAKAT FUND THROUGH (ONE LINK ONLINE SYSTEM) OF [INSTALLMENT NUMBER], INSTALLMENT FOR');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'FINANCIAL YEAR , [YEAR] From ([START DATE] To [END DATE])');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Empty row
        $sheet->setCellValue('A3', '');
        $sheet->mergeCells('A3:L3');

        // Header row - matching JazzCash format exactly
        $headers = [
            'S No',
            'NAME OF MUSTAHIQ',
            'FATHER /Husbend wife Name',
            'CNIC NO',
            'MOB,NO',
            'LZC Name',
            'Amount',
            'CHARGES',
            'TOTAL',
            'STATUS',
            'REASON',
            'TID'
        ];

        $headerRow = 4;
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $sheet->getStyle($col . $headerRow)->getFont()->setBold(true);
            $sheet->getStyle($col . $headerRow)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('D3D3D3');
            $sheet->getStyle($col . $headerRow)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($col . $headerRow)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            $col++;
        }

        // Set column widths for better readability
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);

        // Create writer and download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'JazzCash_Response_Template_' . date('Y-m-d') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'financial_year_id' => 'required|exists:financial_years,id',
            'installment_number' => 'required|integer|min:1',
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $financialYear = FinancialYear::findOrFail($request->financial_year_id);
            $installment = Installment::whereHas('fundAllocation', function($q) use ($financialYear) {
                $q->where('financial_year_id', $financialYear->id);
            })->where('installment_number', $request->installment_number)->first();

            if (!$installment) {
                return back()->withErrors(['error' => 'Installment not found for the selected financial year.'])->withInput();
            }

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Find header row by looking for "S No" or "S No" in any row
            $headerRowIndex = null;
            foreach ($rows as $index => $row) {
                if (!empty($row[0])) {
                    $firstCell = strtolower(trim($row[0]));
                    if ($firstCell === 's no' || $firstCell === 's.no' || $firstCell === 'serial no' || $firstCell === 'serial number') {
                        $headerRowIndex = $index;
                        break;
                    }
                }
            }

            if ($headerRowIndex === null) {
                return back()->withErrors(['error' => 'Could not find header row in Excel file. Please download and use the template file.'])->withInput();
            }

            // Get data rows (skip header row)
            $beneficiaryData = array_slice($rows, $headerRowIndex + 1);
            
            $processed = 0;
            $updated = 0;
            $allRecords = []; // Track all records with status

            DB::beginTransaction();

            foreach ($beneficiaryData as $rowIndex => $row) {
                // Skip empty rows - check if row has any data
                if (empty(array_filter($row))) {
                    continue;
                }

                // CNIC is in column D (index 3)
                $cnicFromExcel = isset($row[3]) ? trim((string)$row[3]) : '';
                if (empty($cnicFromExcel)) {
                    continue;
                }

                // Format CNIC with dashes to match database format (XXXXX-XXXXXXX-X)
                $formattedCnic = $this->formatCnicWithDashes($cnicFromExcel);
                
                // Extract data from Excel row
                $record = [
                    'row' => $rowIndex + $headerRowIndex + 2, // Actual Excel row number
                    's_no' => isset($row[0]) ? trim((string)$row[0]) : '',
                    'name' => isset($row[1]) ? trim((string)$row[1]) : 'N/A',
                    'father_husband_name' => isset($row[2]) ? trim((string)$row[2]) : '',
                    'cnic' => $formattedCnic,
                    'mobile' => isset($row[4]) ? trim((string)$row[4]) : '',
                    'lzc_name' => isset($row[5]) ? trim((string)$row[5]) : '',
                    'amount' => isset($row[6]) ? $this->parseDecimal($row[6]) : 0,
                    'charges' => isset($row[7]) ? $this->parseDecimal($row[7]) : 0,
                    'total' => isset($row[8]) ? $this->parseDecimal($row[8]) : 0,
                    'status' => isset($row[9]) ? strtoupper(trim((string)$row[9])) : '',
                    'reason' => isset($row[10]) ? trim((string)$row[10]) : '',
                    'tid' => isset($row[11]) ? trim((string)$row[11]) : '',
                    'processed' => false,
                    'success' => false,
                    'failure_reason' => '',
                ];

                // Find beneficiary by CNIC within the financial year and scheme
                // We need to find beneficiaries that belong to phases of this installment
                // Try both with and without dashes for comparison
                $beneficiary = Beneficiary::where(function($q) use ($formattedCnic, $cnicFromExcel) {
                        // Try with formatted dashes (database format)
                        $q->where('cnic', $formattedCnic)
                          // Also try without dashes in case database has it stored differently
                          ->orWhereRaw('REPLACE(cnic, "-", "") = ?', [preg_replace('/\D/', '', $formattedCnic)]);
                    })
                    ->whereHas('phase', function($q) use ($installment) {
                        $q->where('installment_id', $installment->id);
                    })
                    ->where('status', 'approved') // Only update approved beneficiaries
                    ->first();

                if (!$beneficiary) {
                    $record['processed'] = true;
                    $record['success'] = false;
                    $record['failure_reason'] = 'Beneficiary not found in system for the selected financial year and installment';
                    $allRecords[] = $record;
                    $processed++;
                    continue;
                }

                try {
                    // Check if JazzCash transaction was successful
                    $jazzcashStatus = strtoupper(trim($record['status']));
                    $isSuccess = ($jazzcashStatus === 'SUCCESS');
                    
                    // Update beneficiary with JazzCash payment information
                    $beneficiary->jazzcash_amount = $record['amount'];
                    $beneficiary->jazzcash_charges = $record['charges'];
                    $beneficiary->jazzcash_total = $record['total'];
                    $beneficiary->jazzcash_status = $record['status'];
                    $beneficiary->jazzcash_reason = $record['reason'];
                    $beneficiary->jazzcash_tid = $record['tid'];
                    
                    // Update status to 'paid' only if payment was successful
                    if ($isSuccess) {
                        $beneficiary->status = 'paid';
                        $beneficiary->payment_received_at = now();
                        $record['success'] = true;
                        $record['failure_reason'] = '';
                    } else {
                        // Transaction failed - don't mark as paid
                        // Keep beneficiary status as 'approved' (or whatever it was)
                        // Store the failure reason from JazzCash
                        $failureReason = !empty($record['reason']) 
                            ? 'JazzCash transaction failed: ' . $record['reason']
                            : 'JazzCash transaction failed: Status is ' . $record['status'];
                        $record['success'] = false;
                        $record['failure_reason'] = $failureReason;
                    }
                    
                    $beneficiary->save();
                    
                    $record['processed'] = true;
                    if ($isSuccess) {
                        $updated++;
                    }
                } catch (\Exception $e) {
                    $record['processed'] = true;
                    $record['success'] = false;
                    $record['failure_reason'] = 'System error: ' . $e->getMessage();
                    Log::error('Error updating beneficiary', [
                        'cnic' => $formattedCnic,
                        'cnic_from_excel' => $cnicFromExcel,
                        'error' => $e->getMessage(),
                    ]);
                }

                $allRecords[] = $record;
                $processed++;
            }

            DB::commit();

            $successCount = count(array_filter($allRecords, function($r) { return $r['success']; }));
            $failedCount = count(array_filter($allRecords, function($r) { return $r['processed'] && !$r['success']; }));

            $message = "Processed {$processed} records. Successfully updated {$successCount} beneficiaries.";
            if ($failedCount > 0) {
                $message .= " {$failedCount} records failed to process.";
            }

            return redirect()->route('jazzcash-response.index')
                ->with('success', $message)
                ->with('allRecords', $allRecords);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing JazzCash response file', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Error processing file: ' . $e->getMessage()])->withInput();
        }
    }

    private function parseDecimal($value)
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        // Remove any non-numeric characters except decimal point
        $cleaned = preg_replace('/[^0-9.]/', '', (string) $value);
        return $cleaned ? (float) $cleaned : 0;
    }

    /**
     * Format CNIC with dashes (from 13 digits to XXXXX-XXXXXXX-X format)
     * 
     * @param string $cnic CNIC number (with or without dashes)
     * @return string Formatted CNIC with dashes
     */
    private function formatCnicWithDashes($cnic)
    {
        // Remove all non-numeric characters
        $cnic = preg_replace('/\D/', '', (string) $cnic);
        
        // Handle scientific notation (e.g., 7.15019E+12)
        if (stripos($cnic, 'E') !== false) {
            $cnic = sprintf('%.0f', (float) $cnic);
        }
        
        // Ensure we have exactly 13 digits
        if (strlen($cnic) < 13) {
            $cnic = str_pad($cnic, 13, '0', STR_PAD_LEFT);
        } elseif (strlen($cnic) > 13) {
            $cnic = substr($cnic, 0, 13);
        }
        
        // Format as XXXXX-XXXXXXX-X
        if (strlen($cnic) === 13) {
            return substr($cnic, 0, 5) . '-' . substr($cnic, 5, 7) . '-' . substr($cnic, 12, 1);
        }
        
        return $cnic; // Return as-is if not 13 digits
    }

    public function getInstallments(Request $request)
    {
        $request->validate([
            'financial_year_id' => 'required|exists:financial_years,id',
        ]);

        $installments = Installment::whereHas('fundAllocation', function($q) use ($request) {
            $q->where('financial_year_id', $request->financial_year_id);
        })->orderBy('installment_number')->get();

        return response()->json($installments->map(function($installment) {
            return [
                'id' => $installment->id,
                'number' => $installment->installment_number,
                'amount' => $installment->installment_amount,
            ];
        }));
    }
}
