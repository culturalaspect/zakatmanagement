<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialYear;
use Carbon\Carbon;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financialYears = [
            [
                'name' => '2023-24',
                'start_date' => Carbon::create(2023, 7, 1), // July 1, 2023
                'end_date' => Carbon::create(2024, 6, 30), // June 30, 2024
                'is_current' => false,
            ],
            [
                'name' => '2024-25',
                'start_date' => Carbon::create(2024, 7, 1), // July 1, 2024
                'end_date' => Carbon::create(2025, 6, 30), // June 30, 2025
                'is_current' => false,
            ],
            [
                'name' => '2025-26',
                'start_date' => Carbon::create(2025, 7, 1), // July 1, 2025
                'end_date' => Carbon::create(2026, 6, 30), // June 30, 2026
                'is_current' => true, // Set as current
            ],
            [
                'name' => '2026-27',
                'start_date' => Carbon::create(2026, 7, 1), // July 1, 2026
                'end_date' => Carbon::create(2027, 6, 30), // June 30, 2027
                'is_current' => false,
            ],
        ];

        foreach ($financialYears as $fy) {
            FinancialYear::create($fy);
        }
    }
}
