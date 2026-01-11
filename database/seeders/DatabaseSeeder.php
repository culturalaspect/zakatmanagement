<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\District;
use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@zakat.gov.pk',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Create Administrator HQ
        User::create([
            'name' => 'Administrator HQ',
            'email' => 'adminhq@zakat.gov.pk',
            'password' => Hash::make('password'),
            'role' => 'administrator_hq',
        ]);

        // Create Districts
        $districts = [
            ['name' => 'Gilgit', 'population' => 200000],
            ['name' => 'Ghizer', 'population' => 150000],
            ['name' => 'Diamer', 'population' => 250000],
            ['name' => 'Astore', 'population' => 120000],
            ['name' => 'Skardu', 'population' => 100000],
            ['name' => 'Ghanche', 'population' => 180000],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }

        // Create Schemes
        $schemes = [
            [
                'name' => 'Guzara Allowance',
                'description' => '60% Guzara Allowance @Rs. 5000 per mustahiq',
                'percentage' => 60,
                'has_age_restriction' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Education Stipend',
                'description' => '18% Education stipend to students',
                'percentage' => 18,
                'has_age_restriction' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Deeni Madris Stipend',
                'description' => '08% stipend for Deeni Madris',
                'percentage' => 8,
                'has_age_restriction' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Marriage Assistance Grant',
                'description' => '08% Marriage Assistance Grant to un-married women @Rs. 20000',
                'percentage' => 8,
                'has_age_restriction' => true,
                'minimum_age' => 18,
                'is_active' => true,
            ],
            [
                'name' => 'Health Care',
                'description' => '06% Health Care through Government DHQ Hospitals (Lump Sump Amount)',
                'percentage' => 6,
                'has_age_restriction' => false,
                'is_active' => true,
            ],
        ];

        foreach ($schemes as $schemeData) {
            $scheme = Scheme::create($schemeData);
            
            // Add categories for Education Stipend
            if ($scheme->name === 'Education Stipend') {
                SchemeCategory::create(['scheme_id' => $scheme->id, 'name' => 'Middle School', 'amount' => 1000]);
                SchemeCategory::create(['scheme_id' => $scheme->id, 'name' => 'High School', 'amount' => 1500]);
                SchemeCategory::create(['scheme_id' => $scheme->id, 'name' => 'College', 'amount' => 3000]);
                SchemeCategory::create(['scheme_id' => $scheme->id, 'name' => 'University', 'amount' => 5000]);
            }
            
            // Add categories for Deeni Madris
            if ($scheme->name === 'Deeni Madris Stipend') {
                SchemeCategory::create(['scheme_id' => $scheme->id, 'name' => 'Hifz / Nazira', 'amount' => 900]);
                SchemeCategory::create(['scheme_id' => $scheme->id, 'name' => 'Doray Hadees', 'amount' => 4500]);
            }
        }

        // Seed Zakat Council Members
        $this->call([
            ZakatCouncilMemberSeeder::class,
            FinancialYearSeeder::class,
        ]);

        // Seed Address Library (Districts, Tehsils, Union Councils, Villages, Mohallas)
        // Uncomment the line below to seed address data from CSV
        // $this->call(AddressLibrarySeeder::class);

        // Seed Local Zakat Committees with random mohallas
        // Uncomment the line below to seed Local Zakat Committees
        // $this->call(LocalZakatCommitteeSeeder::class);
        
        // Seed LZC Members (3-6 members per committee)
        // Uncomment the line below to seed LZC Members
        // $this->call(LZCMemberSeeder::class);
    }
}
