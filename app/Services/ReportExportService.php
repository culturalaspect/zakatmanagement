<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportExportService
{
    /**
     * Create Excel download response from array of data.
     *
     * @param string $filename
     * @param string $sheetTitle
     * @param array $headers
     * @param array $rows
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function excelFromArray(string $filename, string $sheetTitle, array $headers, array $rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($sheetTitle, 0, 31));

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];
        $lastCol = self::colLetter(count($headers) - 1);
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);

        $rowNum = 2;
        foreach ($rows as $row) {
            $col = 'A';
            foreach ($row as $cell) {
                $sheet->setCellValue($col . $rowNum, $cell);
                $col++;
            }
            $rowNum++;
        }

        foreach (range('A', $lastCol) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'report_') . '.xlsx';
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Alternative: build spreadsheet and return as download (save to temp then stream).
     */
    public static function downloadExcel(Spreadsheet $spreadsheet, string $filename)
    {
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'report_') . '.xlsx';
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private static function colLetter(int $index): string
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr(($index % 26) + 65) . $letter;
            $index = (int) ($index / 26) - 1;
        }
        return $letter ?: 'A';
    }
}
