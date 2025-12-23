<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What services do you offer?',
                'answer' => 'We offer a wide range of services to meet your needs. Please contact us for more details.',
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'question' => 'How can I contact you?',
                'answer' => 'You can contact us through our contact form, email, or phone. Our contact information is available on the contact page.',
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'question' => 'What are your business hours?',
                'answer' => 'Our business hours are Monday to Friday, 9:00 AM to 5:00 PM. We are closed on weekends and public holidays.',
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'question' => 'Do you offer support?',
                'answer' => 'Yes, we offer comprehensive support for all our services. Our support team is available during business hours.',
                'status' => 2,
                'publish_time' => now(),
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, [
                    'uuid' => Str::uuid(),
                ])
            );
        }
    }
}
