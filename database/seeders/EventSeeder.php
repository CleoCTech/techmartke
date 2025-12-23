<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Welcome Event',
                'slug' => 'welcome-event',
                'description' => 'Join us for our annual welcome event where we introduce new members and celebrate our community.',
                'content' => '<p>This is a detailed description of the welcome event. We look forward to seeing you there!</p>',
                'cover_image' => null,
                'start_time' => now()->addDays(30)->setTime(10, 0),
                'end_time' => now()->addDays(30)->setTime(12, 0),
                'location' => 'Main Hall',
                'status' => 2,
            ],
            [
                'title' => 'Workshop Series',
                'slug' => 'workshop-series',
                'description' => 'A series of workshops designed to enhance your skills and knowledge.',
                'content' => '<p>Join us for an exciting series of workshops covering various topics and skills.</p>',
                'cover_image' => null,
                'start_time' => now()->addDays(45)->setTime(14, 0),
                'end_time' => now()->addDays(45)->setTime(16, 0),
                'location' => 'Conference Room',
                'status' => 2,
            ],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(
                ['slug' => $event['slug']],
                array_merge($event, [
                    'uuid' => Str::uuid(),
                ])
            );
        }
    }
}
