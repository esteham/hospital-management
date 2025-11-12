<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DiagnosticService;

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

        // Additional Laboratory Services
        DiagnosticService::create([
            'name' => 'Complete Blood Count (CBC)',
            'image' => null,
            'price' => 35.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Comprehensive blood count for red cells, white cells, and platelets.',
            'duration' => 15,
            'home_collection_available' => true,
            'report_time' => 12,
        ]);

        DiagnosticService::create([
            'name' => 'Kidney Function Test',
            'image' => null,
            'price' => 60.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test to assess kidney function and creatinine levels.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'HbA1c Test',
            'image' => null,
            'price' => 50.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test for average blood sugar levels over past months.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'PSA Test',
            'image' => null,
            'price' => 75.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Prostate Specific Antigen test for prostate health.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Pap Smear',
            'image' => null,
            'price' => 80.00,
            'category' => 'Laboratory',
            'sample_type' => 'Cervical',
            'description' => 'Screening test for cervical cancer.',
            'duration' => 20,
            'home_collection_available' => false,
            'report_time' => 72,
        ]);

        DiagnosticService::create([
            'name' => 'ESR Test',
            'image' => null,
            'price' => 20.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Erythrocyte Sedimentation Rate test for inflammation.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 12,
        ]);

        DiagnosticService::create([
            'name' => 'Vitamin B12 Test',
            'image' => null,
            'price' => 40.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test for vitamin B12 levels.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Iron Test',
            'image' => null,
            'price' => 30.00,
            'category' => 'Laboratory',
            'sample_type' => 'Blood',
            'description' => 'Blood test for iron levels and anemia.',
            'duration' => 10,
            'home_collection_available' => true,
            'report_time' => 24,
        ]);

        // Additional Radiology Services
        DiagnosticService::create([
            'name' => 'Bone Marrow Biopsy',
            'image' => null,
            'price' => 400.00,
            'category' => 'Radiology',
            'sample_type' => 'Bone Marrow',
            'description' => 'Biopsy of bone marrow for diagnostic purposes.',
            'duration' => 60,
            'home_collection_available' => false,
            'report_time' => 96,
        ]);

        DiagnosticService::create([
            'name' => 'Angiography',
            'image' => null,
            'price' => 600.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'Imaging of blood vessels using contrast dye.',
            'duration' => 90,
            'home_collection_available' => false,
            'report_time' => 48,
        ]);

        DiagnosticService::create([
            'name' => 'DEXA Scan',
            'image' => null,
            'price' => 200.00,
            'category' => 'Radiology',
            'sample_type' => 'N/A',
            'description' => 'Dual-energy X-ray absorptiometry for bone density.',
            'duration' => 15,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        // Additional Cardiology Services
        DiagnosticService::create([
            'name' => 'Holter Monitor',
            'image' => null,
            'price' => 150.00,
            'category' => 'Cardiology',
            'sample_type' => 'N/A',
            'description' => 'Portable device to monitor heart rhythm over 24-48 hours.',
            'duration' => 1440, // 24 hours
            'home_collection_available' => false,
            'report_time' => 72,
        ]);

        DiagnosticService::create([
            'name' => 'Stress Test',
            'image' => null,
            'price' => 200.00,
            'category' => 'Cardiology',
            'sample_type' => 'N/A',
            'description' => 'Exercise stress test to evaluate heart function.',
            'duration' => 60,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Cardiac Catheterization',
            'image' => null,
            'price' => 1000.00,
            'category' => 'Cardiology',
            'sample_type' => 'N/A',
            'description' => 'Procedure to examine heart and blood vessels.',
            'duration' => 120,
            'home_collection_available' => false,
            'report_time' => 48,
        ]);

        // Additional Pathology Services
        DiagnosticService::create([
            'name' => 'Biopsy',
            'image' => null,
            'price' => 250.00,
            'category' => 'Pathology',
            'sample_type' => 'Tissue',
            'description' => 'Removal of tissue sample for examination.',
            'duration' => 30,
            'home_collection_available' => false,
            'report_time' => 120,
        ]);

        DiagnosticService::create([
            'name' => 'Fine Needle Aspiration',
            'image' => null,
            'price' => 180.00,
            'category' => 'Pathology',
            'sample_type' => 'Tissue',
            'description' => 'Aspiration of cells from a lump for diagnosis.',
            'duration' => 20,
            'home_collection_available' => false,
            'report_time' => 72,
        ]);

        // Neurology Services
        DiagnosticService::create([
            'name' => 'EEG',
            'image' => null,
            'price' => 300.00,
            'category' => 'Neurology',
            'sample_type' => 'N/A',
            'description' => 'Electroencephalogram to record brain activity.',
            'duration' => 60,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'EMG',
            'image' => null,
            'price' => 250.00,
            'category' => 'Neurology',
            'sample_type' => 'N/A',
            'description' => 'Electromyography to assess muscle and nerve function.',
            'duration' => 45,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        // Gastroenterology Services
        DiagnosticService::create([
            'name' => 'Endoscopy',
            'image' => null,
            'price' => 350.00,
            'category' => 'Gastroenterology',
            'sample_type' => 'N/A',
            'description' => 'Procedure to examine the digestive tract.',
            'duration' => 30,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Bronchoscopy',
            'image' => null,
            'price' => 400.00,
            'category' => 'Pulmonology',
            'sample_type' => 'N/A',
            'description' => 'Examination of the airways and lungs.',
            'duration' => 45,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Cystoscopy',
            'image' => null,
            'price' => 300.00,
            'category' => 'Urology',
            'sample_type' => 'N/A',
            'description' => 'Examination of the bladder and urethra.',
            'duration' => 30,
            'home_collection_available' => false,
            'report_time' => 24,
        ]);

        DiagnosticService::create([
            'name' => 'Spirometry',
            'image' => null,
            'price' => 50.00,
            'category' => 'Pulmonology',
            'sample_type' => 'N/A',
            'description' => 'Test to measure lung function.',
            'duration' => 20,
            'home_collection_available' => false,
            'report_time' => 6,
        ]);

        DiagnosticService::create([
            'name' => 'Audiometry',
            'image' => null,
            'price' => 100.00,
            'category' => 'Otolaryngology',
            'sample_type' => 'N/A',
            'description' => 'Hearing test to assess auditory function.',
            'duration' => 30,
            'home_collection_available' => false,
            'report_time' => 12,
        ]);


    }
}
