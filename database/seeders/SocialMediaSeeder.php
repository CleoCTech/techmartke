<?php

namespace Database\Seeders;

use App\Models\System\SocialMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMedias = [
            [
                'name' => 'Facebook',
                'link' => 'https://facebook.com/yourpage',
                'status' => 2,
                'sequence' => 1,
            ],
            [
                'name' => 'Twitter',
                'link' => 'https://twitter.com/yourhandle',
                'status' => 2,
                'sequence' => 2,
            ],
            [
                'name' => 'Instagram',
                'link' => 'https://instagram.com/yourhandle',
                'status' => 2,
                'sequence' => 3,
            ],
            [
                'name' => 'LinkedIn',
                'link' => 'https://linkedin.com/company/yourcompany',
                'status' => 2,
                'sequence' => 4,
            ],
            [
                'name' => 'YouTube',
                'link' => 'https://youtube.com/@yourchannel',
                'status' => 2,
                'sequence' => 5,
            ],
        ];

        foreach ($socialMedias as $social) {
            SocialMedia::firstOrCreate(
                ['name' => $social['name']],
                array_merge($social, [
                    'uuid' => Str::uuid(),
                ])
            );
        }
    }
}
