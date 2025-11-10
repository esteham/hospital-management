<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'Xet Hospital Launches Advanced Cancer Center',
            'excerpt' => 'New state-of-the-art cancer treatment facility now open with latest technology.',
            'content' => 'Xet Hospital is proud to announce the opening of our new Advanced Cancer Center, equipped with the latest radiation therapy machines, chemotherapy suites, and supportive care facilities. This center represents a significant investment in our commitment to providing world-class cancer care to our patients.',
            'image' => '',
            'category' => 'Facility Update',
            'date' => '2024-12-15',
        ]);

        News::create([
            'title' => 'Breakthrough in Minimally Invasive Surgery',
            'excerpt' => 'Our surgeons successfully perform complex procedures with faster recovery times.',
            'content' => 'Our surgical team has achieved remarkable success in minimally invasive procedures, including laparoscopic surgeries and robotic-assisted operations. These techniques result in smaller incisions, reduced pain, and quicker recovery for patients.',
            'image' => '',
            'category' => 'Medical Innovation',
            'date' => '2024-12-10',
        ]);

        News::create([
            'title' => 'Free Health Camp for Underprivileged Communities',
            'excerpt' => 'Xet Hospital organizes health screening camp serving 500+ patients.',
            'content' => 'In our ongoing commitment to community health, Xet Hospital conducted a free health screening camp in partnership with local NGOs. Over 500 patients received comprehensive health check-ups, consultations, and necessary medications at no cost.',
            'image' => '',
            'category' => 'Community Service',
            'date' => '2024-12-05',
        ]);

        News::create([
            'title' => 'Xet Hospital Introduces Telemedicine Services',
            'excerpt' => 'New virtual consultation platform launched for remote patient care.',
            'content' => 'Xet Hospital has launched a comprehensive telemedicine platform, allowing patients to consult with doctors remotely via video calls and secure messaging. This initiative aims to improve access to healthcare, especially for those in remote areas or with mobility challenges.',
            'image' => '',
            'category' => 'Technology Update',
            'date' => '2024-11-30',
        ]);

        News::create([
            'title' => 'Hospital Receives Accreditation for Excellence in Patient Care',
            'excerpt' => 'Xet Hospital awarded top accreditation for quality and safety standards.',
            'content' => 'Xet Hospital has been awarded the prestigious Healthcare Excellence Accreditation for maintaining the highest standards in patient care, safety protocols, and medical outcomes. This recognition underscores our commitment to providing exceptional healthcare services.',
            'image' => '',
            'category' => 'Awards & Recognition',
            'date' => '2024-11-25',
        ]);

        News::create([
            'title' => 'New MRI Machine Enhances Diagnostic Capabilities',
            'excerpt' => 'State-of-the-art MRI equipment installed for advanced imaging services.',
            'content' => 'Xet Hospital has installed a cutting-edge 3T MRI machine, providing high-resolution imaging for accurate diagnosis of various medical conditions. This new equipment will significantly improve our diagnostic capabilities and patient outcomes.',
            'image' => '',
            'category' => 'Facility Update',
            'date' => '2024-11-20',
        ]);

        News::create([
            'title' => 'Annual Health Awareness Campaign Launched',
            'excerpt' => 'Xet Hospital kicks off campaign to promote preventive healthcare.',
            'content' => 'Xet Hospital has launched its annual Health Awareness Campaign, focusing on preventive care, healthy lifestyle choices, and early disease detection. The campaign includes free screenings, educational seminars, and community outreach programs.',
            'image' => '',
            'category' => 'Community Service',
            'date' => '2024-11-15',
        ]);
    }
}
