<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DiagnosticUserSeeder extends Seeder
{
    public function run(): void
    {
        // 5 Diagnostic users
        $diagnosticUsers = [
            [
                'name' => 'Dr. Ayesha Rahman',
                'email' => 'diagnostic@example.com',
                'designation' => 'MBBS, MD (Pathology)',
                'speciality' => 'Pathology',
                'phone' => '01911000001',
                'about' => 'Specializes in laboratory diagnostics and pathology.',
            ],
            [
                'name' => 'Dr. Karim Ullah',
                'email' => 'karim.ullah@diagnostic.com',
                'designation' => 'MBBS, MD (Radiology)',
                'speciality' => 'Radiology',
                'phone' => '01911000002',
                'about' => 'Expert in imaging and radiological diagnostics.',
            ],
            [
                'name' => 'Dr. Nadia Chowdhury',
                'email' => 'nadia.chowdhury@diagnostic.com',
                'designation' => 'MBBS, FCPS (Microbiology)',
                'speciality' => 'Microbiology',
                'phone' => '01911000003',
                'about' => 'Focuses on microbial diagnostics and infections.',
            ],
            [
                'name' => 'Dr. Rafiq Ahmed',
                'email' => 'rafiq.ahmed@diagnostic.com',
                'designation' => 'MBBS, MD (Biochemistry)',
                'speciality' => 'Biochemistry',
                'phone' => '01911000004',
                'about' => 'Handles biochemical tests and metabolic disorders.',
            ],
            [
                'name' => 'Dr. Sumaiya Hossain',
                'email' => 'sumaiya.hossain@diagnostic.com',
                'designation' => 'MBBS, MD (Hematology)',
                'speciality' => 'Hematology',
                'phone' => '01911000005',
                'about' => 'Specializes in blood disorders and hematological diagnostics.',
            ],
        ];

        foreach ($diagnosticUsers as $diag) {
            User::updateOrCreate(
                ['email' => $diag['email']],
                [
                    'name' => $diag['name'],
                    'email' => $diag['email'],
                    'password' => Hash::make('diagnostic123'),
                    'role' => 'diagnostic',
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Demo diagnostic users created successfully');
        $this->command->warn('Diagnostic Login: [diagnostic email] | diagnostic123');
    }
}
