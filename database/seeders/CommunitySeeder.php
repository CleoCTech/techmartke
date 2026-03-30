<?php

namespace Database\Seeders;

use App\Models\CommunityAnswer;
use App\Models\CommunityPhoto;
use App\Models\CommunityQuestion;
use App\Models\SuccessStory;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        // Community Photos
        $photos = [
            [
                'product_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600',
                'caption' => 'Just got my new Samsung Galaxy from TechMart! Best deal in town.',
                'customer_name' => 'James Mwangi',
                'is_approved' => true,
                'is_featured' => true,
                'likes_count' => 24,
            ],
            [
                'product_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600',
                'caption' => 'My new MacBook Pro setup for coding. Thanks TechMartKE!',
                'customer_name' => 'Wanjiku Kamau',
                'is_approved' => true,
                'is_featured' => true,
                'likes_count' => 42,
            ],
            [
                'product_id' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=600',
                'caption' => 'iPhone 14 Pro camera is insane! Great price at TechMart.',
                'customer_name' => 'Brian Ochieng',
                'is_approved' => true,
                'is_featured' => false,
                'likes_count' => 18,
            ],
            [
                'product_id' => 4,
                'image_url' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=600',
                'caption' => 'Picked up this HP laptop for my business. Runs like a dream.',
                'customer_name' => 'Mercy Wambui',
                'is_approved' => true,
                'is_featured' => false,
                'likes_count' => 11,
            ],
            [
                'product_id' => 5,
                'image_url' => 'https://images.unsplash.com/photo-1546054454-aa26e2b734c7?w=600',
                'caption' => 'Got matching phones for me and my wife. TechMart delivery was fast!',
                'customer_name' => 'Peter Njoroge',
                'is_approved' => true,
                'is_featured' => true,
                'likes_count' => 33,
            ],
            [
                'product_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=600',
                'caption' => 'Unboxing my new phone. The ex-UK condition is basically brand new!',
                'customer_name' => 'Aisha Mohamed',
                'is_approved' => true,
                'is_featured' => false,
                'likes_count' => 15,
            ],
        ];

        foreach ($photos as $photo) {
            CommunityPhoto::create($photo);
        }

        // Success Stories
        $stories = [
            [
                'product_id' => 1,
                'title' => 'Best Phone Deal in Nairobi',
                'content' => 'I had been looking for a quality smartphone for months, comparing prices across Nairobi. When I found TechMartKE, I was amazed at the prices. Got a Samsung Galaxy S23 at almost half the retail price, and the condition was pristine. The delivery to Westlands was same-day. I have recommended TechMart to all my friends and colleagues. Truly the best phone deal you can find in Kenya!',
                'customer_name' => 'David Kipchoge',
                'rating' => 5,
                'purchase_date' => '2026-02-15',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'product_id' => 2,
                'title' => 'My MacBook Pro Journey',
                'content' => 'As a software developer, I needed a reliable laptop but couldn\'t afford the brand new MacBook Pro prices. TechMartKE had an ex-UK MacBook Pro M2 at an incredible price. The laptop came with 6 months warranty and it has been working flawlessly for 3 months now. The performance is exactly what I needed for my web development work. Best investment I have made for my career.',
                'customer_name' => 'Grace Akinyi',
                'rating' => 5,
                'purchase_date' => '2026-01-20',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'product_id' => 3,
                'title' => 'Great Customer Service Experience',
                'content' => 'I was hesitant about buying electronics online, but TechMartKE changed my mind. The team was very responsive on WhatsApp, answered all my questions about the iPhone 14 I was interested in, and even sent me additional photos. The phone arrived well-packaged and exactly as described. Will definitely be a repeat customer.',
                'customer_name' => 'Samuel Otieno',
                'rating' => 4,
                'purchase_date' => '2026-03-01',
                'is_approved' => true,
                'is_featured' => false,
            ],
            [
                'product_id' => 5,
                'title' => 'Affordable Laptop for University',
                'content' => 'As a university student at UoN, budget is always tight. TechMartKE helped me find a great laptop under 40K that handles all my coursework, research, and even some light gaming. The staff were patient in helping me choose the right specs for my needs. The laptop has been reliable for my entire semester so far.',
                'customer_name' => 'Faith Nyambura',
                'rating' => 4,
                'purchase_date' => '2026-01-10',
                'is_approved' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($stories as $story) {
            SuccessStory::create($story);
        }

        // Community Questions
        $questions = [
            [
                'product_id' => 3,
                'question' => 'Is the iPhone 14 Pro worth upgrading from iPhone 12?',
                'asked_by' => 'Kevin Maina',
                'is_answered' => true,
                'is_featured' => true,
            ],
            [
                'product_id' => null,
                'question' => 'How long does Ex-UK warranty last at TechMartKE?',
                'asked_by' => 'Lucy Wanjiru',
                'is_answered' => true,
                'is_featured' => true,
            ],
            [
                'product_id' => null,
                'question' => 'Best laptop for programming under 100K?',
                'asked_by' => 'Dennis Omondi',
                'is_answered' => true,
                'is_featured' => false,
            ],
            [
                'product_id' => 1,
                'question' => 'Does the Samsung Galaxy S23 come with a charger in the box?',
                'asked_by' => 'Esther Njeri',
                'is_answered' => true,
                'is_featured' => false,
            ],
            [
                'product_id' => null,
                'question' => 'Do you offer trade-in for old phones when buying a new one?',
                'asked_by' => 'John Mutua',
                'is_answered' => false,
                'is_featured' => false,
            ],
        ];

        $createdQuestions = [];
        foreach ($questions as $question) {
            $createdQuestions[] = CommunityQuestion::create($question);
        }

        // Community Answers
        $answers = [
            [
                'question_id' => $createdQuestions[0]->id,
                'answer' => 'Absolutely! The iPhone 14 Pro has a much better camera system with 48MP main sensor, Dynamic Island, and always-on display. The A16 chip is also significantly faster. If you take a lot of photos or videos, it is a huge upgrade from the iPhone 12.',
                'answered_by' => 'TechMart Team',
                'is_staff' => true,
                'is_accepted' => true,
                'helpful_count' => 12,
            ],
            [
                'question_id' => $createdQuestions[0]->id,
                'answer' => 'I upgraded from iPhone 12 to 14 Pro and the difference is night and day, especially the camera and battery life. Totally worth it!',
                'answered_by' => 'Brian Ochieng',
                'is_staff' => false,
                'is_accepted' => false,
                'helpful_count' => 8,
            ],
            [
                'question_id' => $createdQuestions[1]->id,
                'answer' => 'All our ex-UK devices come with a minimum 3-month warranty. Some premium devices like MacBooks and iPhones come with up to 6 months warranty. The warranty covers hardware defects and malfunctions.',
                'answered_by' => 'TechMart Support',
                'is_staff' => true,
                'is_accepted' => true,
                'helpful_count' => 25,
            ],
            [
                'question_id' => $createdQuestions[2]->id,
                'answer' => 'For programming under 100K, I would recommend the ThinkPad T480 or T490. They have great keyboards, reliable build quality, and you can get one with 16GB RAM and SSD in that budget. We currently have several in stock!',
                'answered_by' => 'TechMart Team',
                'is_staff' => true,
                'is_accepted' => true,
                'helpful_count' => 15,
            ],
            [
                'question_id' => $createdQuestions[2]->id,
                'answer' => 'I bought an HP EliteBook from TechMart for 65K and it handles VS Code, Docker, and multiple Chrome tabs without any issues. Highly recommend for developers.',
                'answered_by' => 'Grace Akinyi',
                'is_staff' => false,
                'is_accepted' => false,
                'helpful_count' => 10,
            ],
            [
                'question_id' => $createdQuestions[3]->id,
                'answer' => 'The Samsung Galaxy S23 does not come with a charger in the box (Samsung stopped including them). However, we sell compatible fast chargers at very affordable prices. You can add one to your order!',
                'answered_by' => 'TechMart Support',
                'is_staff' => true,
                'is_accepted' => true,
                'helpful_count' => 7,
            ],
            [
                'question_id' => $createdQuestions[3]->id,
                'answer' => 'Any USB-C charger works fine. I use my old Samsung charger and it charges quickly.',
                'answered_by' => 'James Mwangi',
                'is_staff' => false,
                'is_accepted' => false,
                'helpful_count' => 4,
            ],
            [
                'question_id' => $createdQuestions[1]->id,
                'answer' => 'I can confirm the warranty is legit. My ex-UK MacBook had a battery issue after 2 months and they replaced it free of charge. Great after-sales support.',
                'answered_by' => 'Peter Njoroge',
                'is_staff' => false,
                'is_accepted' => false,
                'helpful_count' => 18,
            ],
        ];

        foreach ($answers as $answer) {
            CommunityAnswer::create($answer);
        }
    }
}
