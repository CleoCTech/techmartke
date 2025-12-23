<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            [
                'title' => 'Main Gallery',
                'slug' => 'main-gallery',
                'cover_image' => 'placeholder.jpg',
                'description' => 'Our main photo gallery showcasing our facilities and activities.',
                'category' => 'General',
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'title' => 'Events Gallery',
                'slug' => 'events-gallery',
                'cover_image' => 'placeholder.jpg',
                'description' => 'Photos from our various events and activities.',
                'category' => 'Events',
                'status' => 2,
                'publish_time' => now(),
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::firstOrCreate(
                ['slug' => $gallery['slug']],
                array_merge($gallery, [
                    'uuid' => Str::uuid(),
                ])
            );
        }
    }
}
