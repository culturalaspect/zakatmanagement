<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UnionCouncil;
use App\Models\Village;
use App\Models\Mohalla;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all districts
        $districts = District::all();
        if ($districts->isEmpty()) {
            $this->command->warn('No districts found. Please seed districts first.');
            return;
        }

        // Get all address data
        $tehsils = Tehsil::all();
        $unionCouncils = UnionCouncil::all();
        $villages = Village::all();
        $mohallas = Mohalla::all();

        // Institution type distribution (approximately)
        $typeDistribution = [
            'middle_school' => 30,   // 30%
            'high_school' => 25,      // 25%
            'college' => 15,          // 15%
            'university' => 5,        // 5%
            'madarsa' => 15,          // 15%
            'hospital' => 10,         // 10%
        ];

        $institutions = [];
        $typeCounts = [
            'middle_school' => 0,
            'high_school' => 0,
            'college' => 0,
            'university' => 0,
            'madarsa' => 0,
            'hospital' => 0,
        ];

        // Institution name prefixes/suffixes by type
        $namePatterns = [
            'middle_school' => [
                'prefixes' => ['Government', 'Public', 'Model', 'Community', 'Primary & Middle'],
                'suffixes' => ['Middle School', 'School', 'Boys Middle School', 'Girls Middle School', 'High School'],
            ],
            'high_school' => [
                'prefixes' => ['Government', 'Public', 'Model', 'Community', 'Higher Secondary'],
                'suffixes' => ['High School', 'Higher Secondary School', 'Boys High School', 'Girls High School'],
            ],
            'college' => [
                'prefixes' => ['Government', 'Public', 'Degree', 'Post Graduate'],
                'suffixes' => ['College', 'Degree College', 'Boys College', 'Girls College'],
            ],
            'university' => [
                'prefixes' => ['University of', 'Karakuram International', 'Baltistan', 'Gilgit-Baltistan'],
                'suffixes' => ['University', 'International University'],
            ],
            'madarsa' => [
                'prefixes' => ['Darul', 'Jamia', 'Madarsa', 'Markaz', 'Darul Uloom'],
                'suffixes' => ['Madarsa', 'Islamia', 'Tul Islam', 'Al Islam', 'Al Uloom'],
            ],
            'hospital' => [
                'prefixes' => ['District Headquarters', 'Tehsil Headquarters', 'Civil', 'Government', 'Public'],
                'suffixes' => ['Hospital', 'Health Center', 'Medical Center', 'Hospital & Health Center'],
            ],
        ];

        // Generate 100+ institutions
        for ($i = 0; $i < 120; $i++) {
            // Determine institution type based on distribution
            $type = $this->getRandomType($typeDistribution, $typeCounts);
            $typeCounts[$type]++;

            // Select random district
            $district = $districts->random();
            
            // Select address components (cascade down with fallbacks)
            $districtTehsils = $tehsils->where('district_id', $district->id);
            if ($districtTehsils->isEmpty()) {
                $tehsil = null;
                $unionCouncil = null;
                $village = null;
                $mohalla = null;
            } else {
                $tehsil = $districtTehsils->random();
                $tehsilUnionCouncils = $unionCouncils->where('tehsil_id', $tehsil->id);
                if ($tehsilUnionCouncils->isEmpty()) {
                    $unionCouncil = null;
                    $village = null;
                    $mohalla = null;
                } else {
                    $unionCouncil = $tehsilUnionCouncils->random();
                    $ucVillages = $villages->where('union_council_id', $unionCouncil->id);
                    if ($ucVillages->isEmpty()) {
                        $village = null;
                        $mohalla = null;
                    } else {
                        $village = $ucVillages->random();
                        $villageMohallas = $mohallas->where('village_id', $village->id);
                        $mohalla = $villageMohallas->isEmpty() ? null : $villageMohallas->random();
                    }
                }
            }

            // Generate institution name
            $patterns = $namePatterns[$type];
            $prefix = $faker->randomElement($patterns['prefixes']);
            $suffix = $faker->randomElement($patterns['suffixes']);
            
            // Add location name sometimes
            $locationOptions = array_filter([
                $district->name,
                $tehsil ? $tehsil->name : null,
                $unionCouncil ? $unionCouncil->name : null,
                $village ? $village->name : null,
            ]);
            
            $locationName = $faker->optional(0.6)->randomElement($locationOptions);
            
            $name = $locationName 
                ? "$prefix $locationName $suffix"
                : "$prefix $suffix";

            // Generate code (will be auto-generated if not unique, but let's try to make it unique)
            // Get current count for this type
            $existingCount = Institution::where('type', $type)->count();
            $codePrefix = match($type) {
                'middle_school' => 'MS',
                'high_school' => 'HS',
                'college' => 'COL',
                'university' => 'UNI',
                'madarsa' => 'MAD',
                'hospital' => 'HOS',
                default => 'INS',
            };
            $code = $codePrefix . str_pad($existingCount + $typeCounts[$type] + 1, 4, '0', STR_PAD_LEFT);

            // Generate contact information
            $phone = $faker->optional(0.8)->numerify('0355-#######');
            $email = $faker->optional(0.6)->companyEmail();
            
            // Principal/Director name
            $principalName = $faker->optional(0.7)->name();

            // Registration number (optional)
            $registrationNumber = $faker->optional(0.5)->bothify('REG-####-??');

            // Address
            $address = $faker->optional(0.4)->address();

            $institutions[] = [
                'name' => $name,
                'type' => $type,
                'code' => $code,
                'district_id' => $district->id,
                'tehsil_id' => $tehsil ? $tehsil->id : null,
                'union_council_id' => $unionCouncil ? $unionCouncil->id : null,
                'village_id' => $village ? $village->id : null,
                'mohalla_id' => $mohalla ? $mohalla->id : null,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'principal_director_name' => $principalName,
                'registration_number' => $registrationNumber,
                'is_active' => $faker->boolean(90), // 90% active
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in batches
        DB::table('institutions')->insert($institutions);

        $this->command->info('Successfully seeded ' . count($institutions) . ' institutions.');
        $this->command->info('Type distribution:');
        foreach ($typeCounts as $type => $count) {
            $this->command->info("  - " . ucfirst(str_replace('_', ' ', $type)) . ": $count");
        }
    }

    /**
     * Get random type based on distribution, ensuring we don't exceed limits
     */
    private function getRandomType(array $distribution, array $counts): string
    {
        $total = array_sum($distribution);
        $rand = rand(1, $total);
        
        $cumulative = 0;
        foreach ($distribution as $type => $count) {
            $cumulative += $count;
            if ($rand <= $cumulative) {
                return $type;
            }
        }
        
        // Fallback to first type
        return array_key_first($distribution);
    }
}
