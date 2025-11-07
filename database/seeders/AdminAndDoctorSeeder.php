<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class AdminAndDoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 20 Doctors
        $doctors = [
            [
                'name' => 'Dr. Xihad Ansari',
                'email' => 'xihad.ansari@gmail.com',
                'designation' => 'MBBS, FCPS (Medicine)',
                'speciality' => 'General Physician',
                'phone' => '01711000001',
                'about' => 'Mental health & counseling.',
            ],
            [
                'name' => 'Dr. Mahmud Hasan',
                'email' => 'doctor@example.com',
                'designation' => 'MBBS, MD (Cardiology)',
                'speciality' => 'Cardiology',
                'phone' => '01711000002',
                'about' => 'Heart care and cardiac consultation.',
            ],
            [
                'name' => 'Dr. Farzana Akter',
                'email' => 'farzana.akter@example.com',
                'designation' => 'MBBS, MD (Neurology)',
                'speciality' => 'Neurology',
                'phone' => '01711000003',
                'about' => 'Neurological disorders & headache clinic.',
            ],
            [
                'name' => 'Dr. Shanto Roy',
                'email' => 'shanto.roy@example.com',
                'designation' => 'MBBS, MS (Orthopedics)',
                'speciality' => 'Orthopedics',
                'phone' => '01711000004',
                'about' => 'Bone, joint & sports injury specialist.',
            ],
            [
                'name' => 'Dr. Nabila Islam',
                'email' => 'nabila.islam@example.com',
                'designation' => 'MBBS, DDV (Dermatology)',
                'speciality' => 'Dermatology',
                'phone' => '01711000005',
                'about' => 'Skin, hair & nail care.',
            ],
            [
                'name' => 'Dr. Tareq Amin',
                'email' => 'tareq.amin@example.com',
                'designation' => 'MBBS, DCH, MD (Pediatrics)',
                'speciality' => 'Pediatrics',
                'phone' => '01711000006',
                'about' => 'Child health & vaccination.',
            ],
            [
                'name' => 'Dr. Nusrat Jahan',
                'email' => 'nusrat.jahan@example.com',
                'designation' => 'MBBS, FCPS (Gynae & Obs)',
                'speciality' => 'Gynecology & Obstetrics',
                'phone' => '01711000007',
                'about' => 'Women’s health & maternity care.',
            ],
            [
                'name' => 'Dr. Imran Kabir',
                'email' => 'imran.kabir@example.com',
                'designation' => 'MBBS, DLO (ENT)',
                'speciality' => 'ENT',
                'phone' => '01711000008',
                'about' => 'Ear, nose & throat diseases.',
            ],
            [
                'name' => 'Dr. Sanjida Rahman',
                'email' => 'sanjida.rahman@example.com',
                'designation' => 'MBBS, DO (Ophthalmology)',
                'speciality' => 'Ophthalmology',
                'phone' => '01711000009',
                'about' => 'Eye specialist & cataract clinic.',
            ],
            [
                'name' => 'Dr. Rakibul Islam',
                'email' => 'rakibul.islam@example.com',
                'designation' => 'MBBS, MD (Gastroenterology)',
                'speciality' => 'Gastroenterology',
                'phone' => '01711000010',
                'about' => 'Liver & digestive diseases.',
            ],
            [
                'name' => 'Dr. Shirin Akhter',
                'email' => 'shirin.akhter@example.com',
                'designation' => 'MBBS, MD (Nephrology)',
                'speciality' => 'Nephrology',
                'phone' => '01711000011',
                'about' => 'Kidney diseases & dialysis advice.',
            ],
            [
                'name' => 'Dr. Arif Chowdhury',
                'email' => 'arif.chowdhury@example.com',
                'designation' => 'MBBS, MS (Urology)',
                'speciality' => 'Urology',
                'phone' => '01711000012',
                'about' => 'Urinary & male reproductive health.',
            ],
            [
                'name' => 'Dr. Tahmina Sultana',
                'email' => 'tahmina.sultana@example.com',
                'designation' => 'MBBS, MD (Endocrinology)',
                'speciality' => 'Endocrinology',
                'phone' => '01711000013',
                'about' => 'Diabetes & hormone disorders.',
            ],
            [
                'name' => 'Dr. Jubayer Ahmed',
                'email' => 'jubayer.ahmed@example.com',
                'designation' => 'MBBS, MD (Oncology)',
                'speciality' => 'Oncology',
                'phone' => '01711000014',
                'about' => 'Cancer diagnosis & chemotherapy advice.',
            ],
            [
                'name' => 'Dr. Rukhsana Parvin',
                'email' => 'rukhsana.parvin@example.com',
                'designation' => 'MBBS, MD (Psychiatry)',
                'speciality' => 'Psychiatry',
                'phone' => '01711000015',
                'about' => 'Mental health & counseling.',
            ],
            [
                'name' => 'Dr. Morshed Alam',
                'email' => 'morshed.alam@example.com',
                'designation' => 'MBBS, MD (Pulmonology)',
                'speciality' => 'Pulmonology',
                'phone' => '01711000016',
                'about' => 'Asthma, COPD & lung care.',
            ],
            [
                'name' => 'Dr. Sumaiya Khan',
                'email' => 'sumaiya.khan@example.com',
                'designation' => 'MBBS, MD (Rheumatology)',
                'speciality' => 'Rheumatology',
                'phone' => '01711000017',
                'about' => 'Arthritis & autoimmune diseases.',
            ],
            [
                'name' => 'Dr. Nafis Rahim',
                'email' => 'nafis.rahim@example.com',
                'designation' => 'MBBS, MD (Hematology)',
                'speciality' => 'Hematology',
                'phone' => '01711000018',
                'about' => 'Blood disorders & anemia clinic.',
            ],
            [
                'name' => 'Dr. Tasnim Noor',
                'email' => 'tasnim.noor@example.com',
                'designation' => 'BDS, FCPS (Oral & Maxillofacial Surgery)',
                'speciality' => 'Dental Surgery',
                'phone' => '01711000019',
                'about' => 'Oral surgery & dental implants.',
            ],
            [
                'name' => 'Dr. Reaz Ahmed',
                'email' => 'reaz.ahmed@example.com',
                'designation' => 'MBBS, MD (Anesthesiology)',
                'speciality' => 'Anesthesiology',
                'phone' => '01711000020',
                'about' => 'Perioperative & pain management.',
            ],
            [
                'name' => 'Dr. Lamia Hossain',
                'email' => 'lamia.hossain@example.com',
                'designation' => 'MBBS, MD (PM&R)',
                'speciality' => 'Physical Medicine & Rehabilitation',
                'phone' => '01711000021',
                'about' => 'Rehab, physio & pain clinic.',
            ],
        ];

        
        foreach ($doctors as $doc) {
            $user = User::updateOrCreate(
                ['email' => $doc['email']],
                [
                    'name' => $doc['name'],
                    'email' => $doc['email'],
                    'password' => Hash::make('doctor123'),
                    'role' => 'doctor',
                    'email_verified_at' => now(),
                ]
            );

            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'designation' => $doc['designation'],
                    'speciality'  => $doc['speciality'],
                    'phone'       => $doc['phone'],
                    'about'       => $doc['about'],
                ]
            );
        }

		$this->command->info('Demo admin and doctor create successfully');
        $this->command->warn('Admin Login: admin@example.com | admin123');
        $this->command->warn('Doctor Login: doctor@example.com | doctor123');
    }
}
