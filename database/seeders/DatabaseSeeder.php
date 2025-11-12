<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
       $this->call(AdminAndDoctorSeeder::class);
       $this->call(StaffSeeder::class);
       $this->call(DiagnosticUserSeeder::class);
       $this->call(HealthCheckSeeder::class);
       $this->call(NewsSeeder::class);
       $this->call(DiagnosticSeeder::class);
    }
}
