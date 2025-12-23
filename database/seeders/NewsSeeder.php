<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = [
            [
                'title' => 'Welcome to Our New Website',
                'slug' => 'welcome-to-our-new-website',
                'content' => '<p>We are thrilled to announce the launch of our new website. This new platform offers enhanced functionality, improved navigation, and a better overall user experience.</p><p>Stay tuned for more updates and features coming soon!</p>',
                'cover_image' => null,
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'title' => 'Annual Report Released',
                'slug' => 'annual-report-released',
                'content' => '<p>We are pleased to announce that our annual report is now available. The report highlights our achievements, milestones, and future plans.</p>',
                'cover_image' => null,
                'status' => 2,
                'publish_time' => now(),
            ],
        ];

        foreach ($news as $item) {
            News::firstOrCreate(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'uuid' => Str::uuid(),
                ])
            );
        }
    }
}
