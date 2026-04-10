<?php

namespace Database\Seeders;

use App\Models\CustomerReview;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'customer_name' => 'Otieno J.',
                'location' => 'Nairobi',
                'rating' => 5,
                'review' => 'The best price for an Ex-UK phone. Process was seamless!',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Wanjiku M.',
                'location' => 'Kiambu',
                'rating' => 5,
                'review' => 'Got my Samsung S24 Ultra delivered same day. Battery health was exactly as described.',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Brian K.',
                'location' => 'Mombasa',
                'rating' => 4,
                'review' => 'Traded in my old iPhone 12 and got a fair deal. Used the credit towards a MacBook Pro.',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Aisha N.',
                'location' => 'Nairobi',
                'rating' => 5,
                'review' => 'WhatsApp customer service is top-notch. They helped me choose between iPhone 15 and Samsung S24.',
                'is_approved' => true,
                'is_featured' => false,
            ],
            [
                'customer_name' => 'David O.',
                'location' => 'Nakuru',
                'rating' => 5,
                'review' => 'M-Pesa payment was instant. Phone arrived in perfect condition with 3-month warranty.',
                'is_approved' => true,
                'is_featured' => false,
            ],
            [
                'customer_name' => 'Grace W.',
                'location' => 'Eldoret',
                'rating' => 4,
                'review' => 'Good prices compared to other shops. The trade-in feature saved me a lot of money.',
                'is_approved' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($reviews as $review) {
            CustomerReview::firstOrCreate(
                ['customer_name' => $review['customer_name'], 'review' => $review['review']],
                $review
            );
        }
    }
}
