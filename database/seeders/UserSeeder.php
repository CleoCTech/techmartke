<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\System\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@techmartke.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Administrator',
                'email' => 'admin@techmartke.com',
                'password' => Hash::make('password'),
                'user_category' => 100,
                'status' => 2, // Active
                'email_verified_at' => now(),
            ]
        );

        // Add admin user to admin_users table
        AdminUser::firstOrCreate(
            ['email' => 'admin@techmartke.com'],
            [
                'uuid' => Str::uuid(),
                'user_id' => $adminUser->id,
                'name' => 'Administrator',
                'email' => 'admin@techmartke.com',
                'role' => 1, // Super Admin
                'is_approver' => 1,
                'status' => 1, // Active
                'created_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        // Add admin user to staff table
        DB::table('staff')->updateOrInsert(
            ['email' => 'admin@techmartke.com'],
            [
                'uuid' => Str::uuid(),
                'user_id' => $adminUser->id,
                'name' => 'Administrator',
                'email' => 'admin@techmartke.com',
                'phone_no' => '254700000000', // Default — update in admin
                'title' => 'Administrator',
                'status' => 2, // Active (assuming 2 is active based on other models)
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create a test user
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Test User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'user_category' => 2, // Regular user
                'status' => 2, // Active
                'email_verified_at' => now(),
            ]
        );
    }
}
