<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class AdminAndDoctorSeeder extends Seeder
{
    
    public function run(): void
    {
        $admin = User::UpdateOrCreate(
        	['email' => 'admin@clinic.test'],
        	[
        		'name' => 'Test Admin',
        		'email' => 'admin@clinic.test',
        		'password' => Hash::make('password'),
        		'role' => 'admin',
        		'email_verified_at' => now(),
        	]

        );

        $doctorUser = User::updateOrCreate(

        	['email' => 'doctor@clinic.test'],
        	[
        		'name' => 'Dr.Asad',
        		'email' => 'asad@clinic.test',
        		'password' => Hash::make('password'),
        		'role' => 'doctor',
        		'email_verified_at' => now(),
        	]
        );

        Doctor::updateOrCreate(

        	['user_id' => $doctorUser->id],
        	[
        		'designation' => 'MBBS, FCPS',
        		'speciality' => 'General Physician',
        		'phone' => '01711452147',
        		'about' => 'Demo Doctor Profile Testing',
        	]
        );

        $this->command->info('Demo admin and doctor create successfully');
        $this->command->warn('Admin Login: admin@clinic.test | password');
        $this->command->warn('Doctor Login: asad@clinic.test | password');

    }
}
