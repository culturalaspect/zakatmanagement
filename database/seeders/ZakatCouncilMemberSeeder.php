<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZakatCouncilMember;
use Carbon\Carbon;

class ZakatCouncilMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Zakat Council Members as per requirements
        // Start date set to September 2025 as per the requirements document
        $startDate = Carbon::create(2025, 9, 1);
        
        $members = [
            [
                'name' => 'Abrar Ahmed Mirza',
                'designation' => 'Chief Secretary Gilgit-Baltistan',
                'role_in_committee' => 'Chairman',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Rehman Shah',
                'designation' => 'Secretary Zaqat & Ushur',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Sakhawat Hussain',
                'designation' => 'Additional Secretary Finance',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Iqbal Jan',
                'designation' => 'Administrator Zakat',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Mufti Kumailuddin',
                'designation' => 'Member Zakat Council',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Muhammad Akhtar',
                'designation' => 'Member Zakat Council',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Molvi Abdul Qadir',
                'designation' => 'Member Zakat Council',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Molana Muhammad Masood Khan',
                'designation' => 'Member Zakat Council',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Mufti Khalid Mehmood',
                'designation' => 'Member Zakat Council',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Molana Ghulam Rasool',
                'designation' => 'Member Zakat Council',
                'role_in_committee' => 'Member',
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
            ],
        ];

        foreach ($members as $member) {
            ZakatCouncilMember::create($member);
        }
    }
}

