<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DiagnosticService;
use Illuminate\Support\Facades\Hash;

class DiagnosticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample diagnostic services
        DiagnosticService::create([
            'name' => 'Blood Test',
            'image' => null,
            'price' => 50.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Comprehensive blood test for various health parameters.',
            'duration' => 30,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'X-Ray Chest',
            'image' => null,
            'price' => 100.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'Chest X-ray to examine lungs and heart.',
            'duration' => 15,
            'home_collection_available' => false,
            'report_time' => 2,
        ]);

        DiagnosticService::create([
            'name' => 'Urine Test',
            'image' => null,
            'price' => 30.00,
            'category' => 'Laboratory',
            'sample_type' => 'Urine',
            'description' => 'Routine urine analysis for kidney function and infections.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 12,
        ]);

        DiagnosticService::create([
            'name' => 'Stool Test',
            'image' => null,
            'price' => 40.00,
            'category' => 'Laboratory',
            'sample_type' => 'Stool',
            'description' => 'Stool analysis for digestive health and infections.',
            'duration' => 15,
            'home_collection_available' => true,
            'report_time' => 18,
        ]);

        DiagnosticService::create([
            'name' => 'MRI Brain',
            'image' => null,
            'price' => 500.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'Magnetic Resonance Imaging of the brain for detailed scans.',
            'duration' => 45,
            'home_collection_available' => false,
            'report_time' => 48,
        ]);

        DiagnosticService::create([
            'name' => 'ECG',
            'image' => null,
            'price' => 80.00,
            'category' => 'Cardiology',
            'sample_type' => 'N/A',
            'description' => 'Electrocardiogram to monitor heart activity.',
            'duration' => 20,
            'home_collection_available' => false,
            'report_time' => 6,
        ]);

        DiagnosticService::create([
            'name' => 'Ultrasound Abdomen',
            'image' => null,
            'price' => 150.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'Abdominal ultrasound for liver, kidneys, and other organs.',
            'duration' => 30,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Thyroid Function Test',
            'image' => null,
            'price' => 60.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test to assess thyroid hormone levels.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'CT Scan Chest',
            'image' => null,
            'price' => 300.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'Computed Tomography scan of the chest.',
            'duration' => 20,
            'home_collection_available' => false,
            'report_time' => 12,
        ]);

        DiagnosticService::create([
            'name' => 'Lipid Profile',
            'image' => null,
            'price' => 70.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test for cholesterol and lipid levels.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Mammography',
            'image' => null,
            'price' => 120.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'X-ray examination of the breasts.',
            'duration' => 25,
            'home_collection_available' => false,
            'report_time' => 48,
        ]);

        DiagnosticService::create([
            'name' => 'Liver Function Test',
            'image' => null,
            'price' => 55.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test to evaluate liver health.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Bone Density Scan',
            'image' => null,
            'price' => 200.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'DEXA scan to measure bone density.',
            'duration' => 15,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Hemoglobin Test',
            'image' => null,
            'price' => 25.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test for hemoglobin levels.',
            'duration' => 5,
            'home_collection_available' => true,
            'report_time' => 12,
        ]);

        DiagnosticService::create([
            'name' => 'Echo Cardiogram',
            'image' => null,
            'price' => 250.00,
            'category' => 'Cardiology',
            'sample_type' => 'N/A',
            'description' => 'Ultrasound of the heart to assess function.',
            'duration' => 40,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Glucose Tolerance Test',
            'image' => null,
            'price' => 90.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test for diabetes screening.',
            'duration' => 120,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'PET Scan',
            'image' => null,
            'price' => 800.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'Positron Emission Tomography for metabolic imaging.',
            'duration' => 60,
            'home_collection_available' => false,
            'report_time' => 72,
        ]);

        DiagnosticService::create([
            'name' => 'Vitamin D Test',
            'image' => null,
            'price' => 45.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test for vitamin D levels.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Colonoscopy Preparation',
            'image' => null,
            'price' => 100.00,
            'category' => 'Pathology',
            'sample_type' => 'N/A',
            'description' => 'Preparation and procedure for colon examination.',
            'duration' => 180,
            'home_collection_available' => false,
            'report_time' => 48,
        ]);

        DiagnosticService::create([
            'name' => 'Allergy Test',
            'image' => null,
            'price' => 150.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test to identify allergies.',
            'duration' => 20,
            'home_collection_available' => true,
            'report_time' => 48,
        ]);


    }
}
