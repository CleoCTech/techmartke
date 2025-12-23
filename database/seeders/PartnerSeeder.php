<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Partner Company 1',
                'logo' => null,
                'website' => 'https://partner1.com',
                'description' => 'A trusted partner in our network.',
                'status' => 2,
                'publish_time' => now(),
            ],
            [
                'name' => 'Partner Company 2',
                'logo' => null,
                'website' => 'https://partner2.com',
                'description' => 'Another valued partner working with us.',
                'status' => 2,
                'publish_time' => now(),
            ],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(
                ['name' => $partner['name']],
                array_merge($partner, [
                    'uuid' => Str::uuid(),
                ])
            );
        }
    }
}
