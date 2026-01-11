<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LocalZakatCommittee;
use App\Models\LZCMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LZCMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting to add members to Local Zakat Committees...');

        // Deactivate expired members first
        LZCMember::deactivateExpiredMembers();

        $committees = LocalZakatCommittee::all();
        
        if ($committees->isEmpty()) {
            $this->command->warn('No Local Zakat Committees found. Please seed committees first.');
            return;
        }

        $totalMembers = 0;
        $totalCommittees = 0;

        // Pakistani first names
        $maleFirstNames = [
            'Muhammad', 'Ali', 'Ahmed', 'Hassan', 'Hussain', 'Ibrahim', 'Usman', 'Omar', 
            'Bilal', 'Zain', 'Hamza', 'Yusuf', 'Ishaq', 'Yaqoob', 'Haroon', 'Musa',
            'Dawood', 'Suleman', 'Ilyas', 'Younis', 'Noor', 'Faisal', 'Tariq', 'Shahid',
            'Kamran', 'Imran', 'Nadeem', 'Rashid', 'Sajid', 'Majid', 'Waseem', 'Aqeel'
        ];

        $femaleFirstNames = [
            'Fatima', 'Ayesha', 'Khadija', 'Maryam', 'Zainab', 'Hafsa', 'Amina', 'Safiya',
            'Ruqayya', 'Umm-e-Kulsoom', 'Hajra', 'Sumayya', 'Asma', 'Haleema', 'Kulsoom',
            'Bushra', 'Nida', 'Sana', 'Sadia', 'Rabia', 'Aisha', 'Hina', 'Saima',
            'Nadia', 'Shazia', 'Farah', 'Nazia', 'Samina', 'Rukhsana', 'Shabana', 'Tahira'
        ];

        // Pakistani last names
        $lastNames = [
            'Khan', 'Ali', 'Ahmed', 'Hassan', 'Hussain', 'Malik', 'Butt', 'Sheikh',
            'Raza', 'Abbas', 'Naqvi', 'Zaidi', 'Jafri', 'Rizvi', 'Hashmi', 'Qureshi',
            'Shah', 'Baig', 'Mir', 'Dar', 'Wani', 'Bhat', 'Lone', 'Rather',
            'Ganie', 'Parray', 'Khanday', 'Thakur', 'Pandit', 'Dhar', 'Raina', 'Mattoo'
        ];

        // Father/Husband name prefixes
        $fatherHusbandPrefixes = [
            'Muhammad', 'Ali', 'Ahmed', 'Hassan', 'Hussain', 'Ibrahim', 'Usman', 'Omar',
            'Bilal', 'Zain', 'Hamza', 'Yusuf', 'Ishaq', 'Yaqoob', 'Haroon', 'Musa'
        ];

        foreach ($committees as $committee) {
            $this->command->info("Processing committee: {$committee->name} (ID: {$committee->id})...");
            
            // Check if committee already has members
            $existingMembersCount = LZCMember::where('local_zakat_committee_id', $committee->id)->count();
            
            if ($existingMembersCount > 0) {
                $this->command->info("  Committee already has {$existingMembersCount} members. Skipping...");
                continue;
            }

            // Generate random number of members between 3-6
            $membersCount = rand(3, 6);
            $this->command->info("  Adding {$membersCount} members to committee: {$committee->name}...");

            // Get committee formation date for start_date reference
            $formationDate = $committee->formation_date ? Carbon::parse($committee->formation_date) : Carbon::now()->subYears(1);
            $tenureEndDate = $committee->tenure_end_date ? Carbon::parse($committee->tenure_end_date) : null;

            $usedCNICs = []; // Track CNICs to avoid duplicates within this seeder run

            for ($i = 1; $i <= $membersCount; $i++) {
                // Generate unique CNIC
                $cnic = $this->generateUniqueCNIC($usedCNICs);
                $usedCNICs[] = $cnic;

                // Random gender
                $gender = rand(0, 1) === 0 ? 'male' : 'female';
                
                // Generate name based on gender
                if ($gender === 'male') {
                    $firstName = $maleFirstNames[array_rand($maleFirstNames)];
                } else {
                    $firstName = $femaleFirstNames[array_rand($femaleFirstNames)];
                }
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = "{$firstName} {$lastName}";

                // Generate father/husband name
                $fatherHusbandPrefix = $fatherHusbandPrefixes[array_rand($fatherHusbandPrefixes)];
                $fatherHusbandLastName = $lastNames[array_rand($lastNames)];
                $fatherHusbandName = "{$fatherHusbandPrefix} {$fatherHusbandLastName}";

                // Generate date of birth (age between 25-65 years)
                $age = rand(25, 65);
                $dateOfBirth = Carbon::now()->subYears($age)->subDays(rand(0, 365));

                // Generate start date (around formation date, within 30 days)
                $startDate = $formationDate->copy()->addDays(rand(0, 30));

                // Generate end date (70% chance of having end date, 30% ongoing)
                $endDate = null;
                if (rand(0, 10) < 7) {
                    // If committee has tenure end date, use it as reference
                    if ($tenureEndDate) {
                        $endDate = $tenureEndDate->copy()->subDays(rand(0, 30));
                    } else {
                        // Otherwise, set end date 2-5 years from start date
                        $endDate = $startDate->copy()->addYears(rand(2, 5));
                    }
                }

                // Generate mobile number (70% chance of having one)
                $mobileNumber = null;
                if (rand(0, 10) < 7) {
                    $mobileNumber = $this->generateMobileNumber();
                }

                // Create member
                try {
                    $member = LZCMember::create([
                        'local_zakat_committee_id' => $committee->id,
                        'cnic' => $cnic,
                        'full_name' => $fullName,
                        'father_husband_name' => $fatherHusbandName,
                        'mobile_number' => $mobileNumber,
                        'date_of_birth' => $dateOfBirth->format('Y-m-d'),
                        'gender' => $gender,
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
                        'is_active' => false, // Members are inactive until verified
                        'verification_status' => 'pending', // Default status
                    ]);

                    $totalMembers++;
                } catch (\Exception $e) {
                    $this->command->error("  Error creating member {$i} for committee {$committee->id}: " . $e->getMessage());
                    continue;
                }
            }

            $totalCommittees++;
            
            if ($totalCommittees % 10 === 0) {
                $this->command->info("  Progress: {$totalCommittees} committees processed, {$totalMembers} members created...");
            }
        }

        $this->command->info("\n=== Summary ===");
        $this->command->info("Total committees processed: {$totalCommittees}");
        $this->command->info("Total members created: {$totalMembers}");
        $this->command->info("Average members per committee: " . ($totalCommittees > 0 ? round($totalMembers / $totalCommittees, 2) : 0));
    }

    /**
     * Generate a unique Pakistani CNIC in format: XXXXX-XXXXXXX-X
     */
    private function generateUniqueCNIC(array &$usedCNICs): string
    {
        do {
            // Generate 5-digit area code (common Pakistani area codes)
            $areaCode = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
            
            // Generate 7-digit serial number
            $serialNumber = str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
            
            // Generate 1-digit check digit
            $checkDigit = rand(0, 9);
            
            $cnic = "{$areaCode}-{$serialNumber}-{$checkDigit}";
        } while (in_array($cnic, $usedCNICs) || LZCMember::where('cnic', $cnic)->exists());

        return $cnic;
    }

    /**
     * Generate a Pakistani mobile number in format: 03XX-XXXXXXX
     */
    private function generateMobileNumber(): string
    {
        // Pakistani mobile network prefixes
        $prefixes = ['0300', '0301', '0302', '0303', '0304', '0305', '0306', '0307', 
                     '0308', '0309', '0310', '0311', '0312', '0313', '0314', '0315',
                     '0316', '0317', '0318', '0319', '0320', '0321', '0322', '0323',
                     '0324', '0325', '0330', '0331', '0332', '0333', '0334', '0335',
                     '0336', '0337', '0340', '0341', '0342', '0343', '0344', '0345',
                     '0346', '0347', '0348', '0349'];
        
        $prefix = $prefixes[array_rand($prefixes)];
        $number = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$number}";
    }
}

