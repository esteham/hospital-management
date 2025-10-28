<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HealthCheck;

class HealthCheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthCheck::create([
            'name' => 'Basic Health Check',
            'price' => '৳2,500',
            'features' => [
                'Blood Tests',
                'Urine Analysis',
                'Basic Consultation',
                'BMI Calculation',
            ],
            'popular' => false,
        ]);

        HealthCheck::create([
            'name' => 'Executive Health Check',
            'price' => '৳5,000',
            'features' => [
                'Complete Blood Count',
                'Liver Function Test',
                'Cardiac Screening',
                'Diabetes Panel',
                'Doctor Consultation',
            ],
            'popular' => true,
        ]);

        HealthCheck::create([
            'name' => 'Comprehensive Health Check',
            'price' => '৳8,000',
            'features' => [
                'All Executive Features',
                'Cancer Markers',
                'Advanced Cardiac Tests',
                'Full Body Checkup',
                'Specialist Consultation',
            ],
            'popular' => false,
        ]);
    }
}
