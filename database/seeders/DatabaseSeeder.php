<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RolePermissionSeeder::class,
            CompanyInformationSeeder::class,
            SocialMediaSeeder::class,
            FaqSeeder::class,
            EventSeeder::class,
            NewsSeeder::class,
            GallerySeeder::class,
            PartnerSeeder::class,
            TestimonialSeeder::class,
            TechMartSeeder::class,
        ]);
    }
}
