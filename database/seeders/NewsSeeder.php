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
            'image' => '',
            'category' => 'Facility Update',
            'date' => '2024-12-15',
        ]);

        News::create([
            'title' => 'Breakthrough in Minimally Invasive Surgery',
            'excerpt' => 'Our surgeons successfully perform complex procedures with faster recovery times.',
            'image' => '',
            'category' => 'Medical Innovation',
            'date' => '2024-12-10',
        ]);

        News::create([
            'title' => 'Free Health Camp for Underprivileged Communities',
            'excerpt' => 'Xet Hospital organizes health screening camp serving 500+ patients.',
            'image' => '',
            'category' => 'Community Service',
            'date' => '2024-12-05',
        ]);
    }
}
