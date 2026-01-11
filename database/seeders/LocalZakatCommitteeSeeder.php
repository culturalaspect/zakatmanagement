<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\LocalZakatCommittee;
use App\Models\Mohalla;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LocalZakatCommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting to create Local Zakat Committees...');

        $districts = District::where('is_active', true)->get();
        
        if ($districts->isEmpty()) {
            $this->command->warn('No active districts found. Please seed districts first.');
            return;
        }

        $totalCommittees = 0;
        $totalMohallasAttached = 0;

        foreach ($districts as $district) {
            $this->command->info("Processing district: {$district->name}...");
            
            // Get mohallas for this district through the relationship chain
            $mohallas = Mohalla::whereHas('village.unionCouncil.tehsil', function($query) use ($district) {
                $query->where('tehsils.district_id', $district->id);
            })->where('is_active', true)->get();
            
            if ($mohallas->isEmpty()) {
                $this->command->warn("No mohallas found for district: {$district->name}. Skipping...");
                continue;
            }
            
            // Generate random number of committees between 20-40 for this district
            $committeesCount = rand(20, 40);
            $this->command->info("Creating {$committeesCount} committees for {$district->name}...");
            
            for ($i = 1; $i <= $committeesCount; $i++) {
                // Generate formation date (random date within last 2 years)
                $formationDate = Carbon::now()->subYears(rand(0, 2))->subDays(rand(0, 730));
                
                // Random tenure between 2-5 years
                $tenureYears = rand(2, 5);
                $tenureEndDate = $formationDate->copy()->addYears($tenureYears);
                
                // Generate committee name
                $committeeName = $this->generateCommitteeName($district->name, $i);
                
                // Generate unique code
                $code = LocalZakatCommittee::generateCode($district->id);
                
                // Create committee
                $committee = LocalZakatCommittee::create([
                    'district_id' => $district->id,
                    'code' => $code,
                    'name' => $committeeName,
                    'area_coverage' => $this->generateAreaCoverage($district->name),
                    'formation_date' => $formationDate->format('Y-m-d'),
                    'tenure_end_date' => $tenureEndDate->format('Y-m-d'),
                    'tenure_years' => $tenureYears,
                    'is_active' => rand(0, 10) > 1, // 90% chance of being active
                ]);
                
                // Attach random mohallas (between 1-10 mohallas per committee)
                $mohallasToAttach = $mohallas->random(rand(1, min(10, $mohallas->count())));
                $committee->mohallas()->attach($mohallasToAttach->pluck('id')->toArray());
                
                $totalMohallasAttached += $mohallasToAttach->count();
                $totalCommittees++;
                
                if ($i % 10 === 0) {
                    $this->command->info("  Created {$i}/{$committeesCount} committees for {$district->name}...");
                }
            }
            
            $this->command->info("✓ Completed {$district->name}: {$committeesCount} committees created.");
        }

        $this->command->info("\n=== Summary ===");
        $this->command->info("Total committees created: {$totalCommittees}");
        $this->command->info("Total mohalla attachments: {$totalMohallasAttached}");
        $this->command->info("Average mohallas per committee: " . ($totalCommittees > 0 ? round($totalMohallasAttached / $totalCommittees, 2) : 0));
    }

    /**
     * Generate a committee name
     */
    private function generateCommitteeName($districtName, $index): string
    {
        $prefixes = ['Central', 'Main', 'Primary', 'Head', 'Main', 'Central'];
        $suffixes = ['Committee', 'Council', 'Board', 'Committee', 'Council'];
        
        $prefix = $prefixes[array_rand($prefixes)];
        $suffix = $suffixes[array_rand($suffixes)];
        
        // Sometimes add area name
        $areaNames = ['Urban', 'Rural', 'City', 'Town', 'Village', 'Area'];
        $useArea = rand(0, 1);
        
        if ($useArea) {
            $area = $areaNames[array_rand($areaNames)];
            return "{$prefix} {$area} {$suffix} - {$districtName} #{$index}";
        }
        
        return "{$prefix} {$suffix} - {$districtName} #{$index}";
    }

    /**
     * Generate area coverage description
     */
    private function generateAreaCoverage($districtName): string
    {
        $templates = [
            "Serving multiple mohallas in {$districtName} district",
            "Coverage area includes various mohallas within {$districtName}",
            "Responsible for zakat distribution in selected mohallas of {$districtName}",
            "Serving the community across multiple mohallas in {$districtName} district",
            "Area coverage includes designated mohallas in {$districtName}",
        ];
        
        return $templates[array_rand($templates)];
    }
}
