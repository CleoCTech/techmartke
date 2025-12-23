<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first service for testimonials (or create a default one if none exist)
        $service = \App\Models\System\Service::first();
        if (!$service) {
            $service = \App\Models\System\Service::create([
                'uuid' => Str::uuid(),
                'title' => 'Default Service',
                'description' => 'Default service for testimonials',
                'status' => 2,
            ]);
        }

        $testimonials = [
            [
                'testifier_name' => 'John Doe',
                'testimony' => 'Excellent service and support. Highly recommended!',
                'service_id' => $service->id,
                'remarks' => 'Very satisfied customer',
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'testifier_name' => 'Jane Smith',
                'testimony' => 'Great experience working with this team. Professional and efficient.',
                'service_id' => $service->id,
                'remarks' => 'Professional service',
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'testifier_name' => 'Robert Johnson',
                'testimony' => 'Outstanding quality and attention to detail. Very satisfied!',
                'service_id' => $service->id,
                'remarks' => 'High quality service',
                'status' => 2,
                'publish_time' => now(),
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                [
                    'testifier_name' => $testimonial['testifier_name'],
                ],
                array_merge($testimonial, [
                    'uuid' => Str::uuid(),
                ])
            );
        }
    }
}
