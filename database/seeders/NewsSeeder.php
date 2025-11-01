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
            'image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
            'category' => 'Facility Update',
            'date' => '2024-12-15',
        ]);

        News::create([
            'title' => 'Breakthrough in Minimally Invasive Surgery',
            'excerpt' => 'Our surgeons successfully perform complex procedures with faster recovery times.',
            'image' => 'https://images.unsplash.com/photo-1584467735871-8db9ac8d55b8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
            'category' => 'Medical Innovation',
            'date' => '2024-12-10',
        ]);

        News::create([
            'title' => 'Free Health Camp for Underprivileged Communities',
            'excerpt' => 'Xet Hospital organizes health screening camp serving 500+ patients.',
            'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
            'category' => 'Community Service',
            'date' => '2024-12-05',
        ]);
    }
}
