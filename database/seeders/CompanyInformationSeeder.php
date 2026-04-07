<?php

namespace Database\Seeders;

use App\Models\System\CompanyInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanyInformationSeeder extends Seeder
{
    public function run(): void
    {
        CompanyInformation::updateOrCreate(
            ['id' => 1],
            [
                'uuid' => Str::uuid(),
                'company_name' => 'TechMart Kenya',
                'short_name' => 'TechMartKE',
                'emails' => 'info@techmart.ke',
                'phone_numbers' => '254700000000',
                'address' => 'Moi Avenue, Nairobi CBD',
                'location' => 'Nairobi, Kenya',
                'logo' => null,
                'about_short' => 'Kenya\'s trusted marketplace for phones, laptops & accessories.',
                'about' => 'TechMart Kenya is a leading electronics retailer in Nairobi, specializing in new and quality-tested Ex-UK phones, laptops, tablets, and accessories. We use AI-powered tools to help customers find the perfect device within their budget. With M-Pesa payment, fast delivery across Kenya, and a 3-month warranty on all devices, we make tech shopping smart and simple.',
                'mission' => 'To make quality technology accessible and affordable for every Kenyan through honest pricing, genuine products, and exceptional customer service.',
                'vision' => 'To be East Africa\'s most trusted tech marketplace — where every customer finds the right device at the right price.',
                'core_values' => 'Trust, Quality, Affordability, Customer Care, Innovation',
                'motto' => 'Smart Shopping, Better Decisions',
                'status' => 2,
            ]
        );
    }
}
