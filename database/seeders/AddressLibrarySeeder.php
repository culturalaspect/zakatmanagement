<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UnionCouncil;
use App\Models\Village;
use App\Models\Mohalla;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddressLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = base_path('address_libraries.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            $this->command->info("Please ensure the CSV file is located at: {$csvPath}");
            return;
        }

        $this->command->info('Starting to import address data from CSV...');

        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $this->command->error('Could not open CSV file.');
            return;
        }

        // Skip header row
        $header = fgetcsv($handle);
        
        $processed = 0;
        $skipped = 0;
        $districts = [];
        $tehsils = [];
        $unionCouncils = [];
        $villages = [];
        $mohallas = [];

        DB::beginTransaction();
        
        try {
            while (($row = fgetcsv($handle)) !== false) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Parse CSV row
                $mohallahName = trim($row[0] ?? '');
                $villageName = trim($row[1] ?? '');
                $unionCouncilName = trim($row[2] ?? '');
                $tehsilName = trim($row[3] ?? '');
                $districtName = trim($row[4] ?? '');

                // Skip if essential data is missing
                if (empty($districtName) || empty($tehsilName) || empty($unionCouncilName) || empty($villageName) || empty($mohallahName)) {
                    $skipped++;
                    continue;
                }

                // Create or find District
                $districtKey = Str::slug(strtolower($districtName));
                if (!isset($districts[$districtKey])) {
                    $district = District::firstOrCreate(
                        ['name' => $districtName],
                        ['population' => 0, 'is_active' => true]
                    );
                    $districts[$districtKey] = $district;
                } else {
                    $district = $districts[$districtKey];
                }

                // Create or find Tehsil
                $tehsilKey = $district->id . '_' . Str::slug(strtolower($tehsilName));
                if (!isset($tehsils[$tehsilKey])) {
                    $tehsil = Tehsil::firstOrCreate(
                        [
                            'district_id' => $district->id,
                            'name' => $tehsilName,
                        ],
                        ['is_active' => true]
                    );
                    $tehsils[$tehsilKey] = $tehsil;
                } else {
                    $tehsil = $tehsils[$tehsilKey];
                }

                // Create or find Union Council
                $unionCouncilKey = $tehsil->id . '_' . Str::slug(strtolower($unionCouncilName));
                if (!isset($unionCouncils[$unionCouncilKey])) {
                    $unionCouncil = UnionCouncil::firstOrCreate(
                        [
                            'tehsil_id' => $tehsil->id,
                            'name' => $unionCouncilName,
                        ],
                        ['is_active' => true]
                    );
                    $unionCouncils[$unionCouncilKey] = $unionCouncil;
                } else {
                    $unionCouncil = $unionCouncils[$unionCouncilKey];
                }

                // Create or find Village
                $villageKey = $unionCouncil->id . '_' . Str::slug(strtolower($villageName));
                if (!isset($villages[$villageKey])) {
                    $village = Village::firstOrCreate(
                        [
                            'union_council_id' => $unionCouncil->id,
                            'name' => $villageName,
                        ],
                        ['is_active' => true]
                    );
                    $villages[$villageKey] = $village;
                } else {
                    $village = $villages[$villageKey];
                }

                // Create or find Mohalla
                $mohallaKey = $village->id . '_' . Str::slug(strtolower($mohallahName));
                if (!isset($mohallas[$mohallaKey])) {
                    $mohalla = Mohalla::firstOrCreate(
                        [
                            'village_id' => $village->id,
                            'name' => $mohallahName,
                        ],
                        ['is_active' => true]
                    );
                    $mohallas[$mohallaKey] = $mohalla;
                }

                $processed++;

                // Progress indicator
                if ($processed % 100 === 0) {
                    $this->command->info("Processed {$processed} records...");
                }
            }

            DB::commit();

            $this->command->info("Successfully imported address data!");
            $this->command->info("Total records processed: {$processed}");
            $this->command->info("Records skipped: {$skipped}");
            $this->command->info("Districts created/found: " . count($districts));
            $this->command->info("Tehsils created/found: " . count($tehsils));
            $this->command->info("Union Councils created/found: " . count($unionCouncils));
            $this->command->info("Villages created/found: " . count($villages));
            $this->command->info("Mohallas created/found: " . count($mohallas));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error importing data: " . $e->getMessage());
            $this->command->error($e->getTraceAsString());
        } finally {
            fclose($handle);
        }
    }
}
