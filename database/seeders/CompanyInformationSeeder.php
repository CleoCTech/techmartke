<?php

namespace Database\Seeders;

use App\Models\System\CompanyInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanyInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyInformation::firstOrCreate(
            ['id' => 1],
            [
                'uuid' => Str::uuid(),
                'company_name' => 'Your Company Name',
                'short_name' => 'YCN',
                'emails' => 'info@example.co.ke',
                'phone_numbers' => '+254 700 000 000',
                'address' => 'Nairobi, Kenya',
                'location' => 'Nairobi, Kenya',
                'logo' => null,
                'about_short' => 'A leading company based in Kenya, providing quality services and solutions.',
                'about' => 'We are a reputable organization operating in Kenya, committed to delivering excellence in all our endeavors. Our team is dedicated to providing innovative solutions and exceptional service to our clients.',
                'mission' => 'To deliver exceptional value and service excellence to our clients across Kenya and beyond.',
                'vision' => 'To be the premier service provider in Kenya, recognized for innovation, integrity, and excellence.',
                'core_values' => 'Integrity, Excellence, Innovation, Customer Focus, Community Impact',
                'motto' => 'Excellence in Service',
                'total_members' => '1000+',
                'branches' => '5',
                'status' => 2,
            ]
        );
    }
}
