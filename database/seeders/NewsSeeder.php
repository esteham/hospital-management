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
    }
}
