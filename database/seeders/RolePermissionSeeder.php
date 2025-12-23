<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        $administrator = Role::firstOrCreate(
            ['name' => 'administrator'],
            [
                'uuid' => Str::uuid(),
                'display_name' => 'Administrator',
                'description' => 'Full system access',
            ]
        );

        $superadmin = Role::firstOrCreate(
            ['name' => 'superadmin'],
            [
                'uuid' => Str::uuid(),
                'display_name' => 'Super Admin',
                'description' => 'Super administrator with all privileges',
            ]
        );

        $editor = Role::firstOrCreate(
            ['name' => 'editor'],
            [
                'uuid' => Str::uuid(),
                'display_name' => 'Editor',
                'description' => 'Can create and edit content',
            ]
        );

        // Create default permissions
        $permissions = [
            ['name' => 'manageusers', 'display_name' => 'Manage Users', 'description' => 'Can manage users'],
            ['name' => 'manageroles', 'display_name' => 'Manage Roles', 'description' => 'Can manage roles and permissions'],
            ['name' => 'manageevents', 'display_name' => 'Manage Events', 'description' => 'Can manage events'],
            ['name' => 'managenews', 'display_name' => 'Manage News', 'description' => 'Can manage news'],
            ['name' => 'managegallery', 'display_name' => 'Manage Gallery', 'description' => 'Can manage gallery'],
            ['name' => 'managefaq', 'display_name' => 'Manage FAQ', 'description' => 'Can manage FAQ'],
            ['name' => 'managepartners', 'display_name' => 'Manage Partners', 'description' => 'Can manage partners'],
            ['name' => 'managetestimonials', 'display_name' => 'Manage Testimonials', 'description' => 'Can manage testimonials'],
            ['name' => 'manageinquiries', 'display_name' => 'Manage Inquiries', 'description' => 'Can manage inquiries'],
            ['name' => 'managefeedback', 'display_name' => 'Manage Feedback', 'description' => 'Can manage feedback'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'uuid' => Str::uuid(),
                    'display_name' => $permission['display_name'],
                    'description' => $permission['description'],
                ]
            );
        }

        // Assign all permissions to administrator and superadmin
        $allPermissions = Permission::all();
        $administrator->permissions()->sync($allPermissions->pluck('id'));
        $superadmin->permissions()->sync($allPermissions->pluck('id'));

        // Assign administrator role to admin user
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser && !$adminUser->hasRole('administrator')) {
            $adminUser->addRole($administrator);
        }
    }
}
