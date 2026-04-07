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
        // Essential seeders for production
        $this->call([
            UserSeeder::class,            // Admin user
            RolePermissionSeeder::class,  // Roles & permissions
            CompanyInformationSeeder::class, // TechMart KE info
            TechMartSeeder::class,        // Brands & categories
            VipSeeder::class,             // VIP tiers
        ]);

        // Demo / sample data — uncomment for staging only
        // $this->call([
        //     SocialMediaSeeder::class,
        //     FaqSeeder::class,
        //     EventSeeder::class,
        //     NewsSeeder::class,
        //     GallerySeeder::class,
        //     PartnerSeeder::class,
        //     TestimonialSeeder::class,
        //     CommunitySeeder::class,
        //     PhoneProductSeeder::class,
        // ]);
    }
}
