<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // 20 Staff members
        $staff = [
            [
                'name' => 'Mst Jakia Sultana',
                'email' => 'jakia.sultana@staff.com',
                'designation' => 'Registered Nurse',
                'department' => 'Nursing',
                'phone' => '01811000001',
                'about' => 'Experienced in patient care and emergency response.',
            ],
            [
                'name' => ' Rahim Chowdhury',
                'email' => 'rahim.chowdhury@staff.com',
                'designation' => 'Senior Accountant',
                'department' => 'Finance',
                'phone' => '01811000002',
                'about' => 'Handles hospital budgeting and financial reporting.',
            ],
            [
                'name' => ' Guard Jamal Hossain',
                'email' => 'jamal.hossain@staff.com',
                'designation' => 'Security Officer',
                'department' => 'Security',
                'phone' => '01811000003',
                'about' => 'Ensures safety and security of premises.',
            ],
            [
                'name' => ' Fatima Begum',
                'email' => 'fatima.begum@staff.com',
                'designation' => 'Housekeeper',
                'department' => 'Housekeeping',
                'phone' => '01811000004',
                'about' => 'Maintains cleanliness and hygiene in wards.',
            ],
            [
                'name' => ' Karim Reza',
                'email' => 'karim.reza@staff.com',
                'designation' => 'Nurse Assistant',
                'department' => 'Nursing',
                'phone' => '01811000005',
                'about' => 'Assists in patient monitoring and care.',
            ],
            [
                'name' => ' Sumona Akter',
                'email' => 'sumona.akter@staff.com',
                'designation' => 'Junior Accountant',
                'department' => 'Finance',
                'phone' => '01811000006',
                'about' => 'Manages petty cash and invoices.',
            ],
            [
                'name' => ' Guard Anwar Ali',
                'email' => 'anwar.ali@staff.com',
                'designation' => 'Night Shift Security',
                'department' => 'Security',
                'phone' => '01811000007',
                'about' => 'Oversees security during night hours.',
            ],
            [
                'name' => ' Mohammad Islam',
                'email' => 'mohammad.islam@staff.com',
                'designation' => 'Janitor',
                'department' => 'Housekeeping',
                'phone' => '01811000008',
                'about' => 'Handles waste disposal and floor cleaning.',
            ],
            [
                'name' => ' Parveen Sultana',
                'email' => 'parveen.sultana@staff.com',
                'designation' => 'ICU Nurse',
                'department' => 'Nursing',
                'phone' => '01811000009',
                'about' => 'Specializes in intensive care unit support.',
            ],
            [
                'name' => ' Imran Khan',
                'email' => 'imran.khan@staff.com',
                'designation' => 'Financial Analyst',
                'department' => 'Finance',
                'phone' => '01811000010',
                'about' => 'Analyzes financial data and forecasts.',
            ],
            [
                'name' => ' Guard Rina Das',
                'email' => 'rina.das@staff.com',
                'designation' => 'Security Supervisor',
                'department' => 'Security',
                'phone' => '01811000011',
                'about' => 'Supervises security team and protocols.',
            ],
            [
                'name' => ' Asma Khatun',
                'email' => 'asma.khatun@staff.com',
                'designation' => 'Laundry Attendant',
                'department' => 'Housekeeping',
                'phone' => '01811000012',
                'about' => 'Manages hospital laundry services.',
            ],
            [
                'name' => ' Shahidul Islam',
                'email' => 'shahidul.islam@staff.com',
                'designation' => 'Pediatric Nurse',
                'department' => 'Nursing',
                'phone' => '01811000013',
                'about' => 'Cares for pediatric patients.',
            ],
            [
                'name' => ' Nadia Rahman',
                'email' => 'nadia.rahman@staff.com',
                'designation' => 'Payroll Clerk',
                'department' => 'Finance',
                'phone' => '01811000014',
                'about' => 'Processes employee salaries and benefits.',
            ],
            [
                'name' => ' Guard Babu Mia',
                'email' => 'babu.mia@staff.com',
                'designation' => 'Gate Security',
                'department' => 'Security',
                'phone' => '01811000015',
                'about' => 'Manages entry and exit at main gate.',
            ],
            [
                'name' => ' Rokeya Begum',
                'email' => 'rokeya.begum@staff.com',
                'designation' => 'Sanitizer',
                'department' => 'Housekeeping',
                'phone' => '01811000016',
                'about' => 'Ensures sanitation in operating rooms.',
            ],
            [
                'name' => ' Tanvir Ahmed',
                'email' => 'tanvir.ahmed@staff.com',
                'designation' => 'Surgical Nurse',
                'department' => 'Nursing',
                'phone' => '01811000017',
                'about' => 'Assists in surgical procedures.',
            ],
            [
                'name' => ' Farhana Islam',
                'email' => 'farhana.islam@staff.com',
                'designation' => 'Auditor',
                'department' => 'Finance',
                'phone' => '01811000018',
                'about' => 'Conducts internal audits.',
            ],
            [
                'name' => ' Guard Kamal Hossain',
                'email' => 'kamal.hossain@staff.com',
                'designation' => 'Patrol Officer',
                'department' => 'Security',
                'phone' => '01811000019',
                'about' => 'Patrols hospital premises.',
            ],
            [
                'name' => ' Laila Noor',
                'email' => 'laila.noor@staff.com',
                'designation' => 'Cleaner',
                'department' => 'Housekeeping',
                'phone' => '01811000020',
                'about' => 'General cleaning and maintenance.',
            ],
        ];

        foreach ($staff as $stf) {
            $user = User::updateOrCreate(
                ['email' => $stf['email']],
                [
                    'name' => $stf['name'],
                    'email' => $stf['email'],
                    'password' => Hash::make('staff123'),
                    'role' => 'staff',
                    'email_verified_at' => now(),
                ]
            );

            Staff::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'designation' => $stf['designation'],
                    'department' => $stf['department'],
                    'phone' => $stf['phone'],
                    'about' => $stf['about'],
                ]
            );
        }

        $this->command->info('Demo staff created successfully');
        $this->command->warn('Staff Login: [staff email] | staff123');
    }
}
