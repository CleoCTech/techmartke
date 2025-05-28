<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define designations with their types
        $designations = [
            // Spiritual Designations
            ['name' => 'Senior Pastor', 'type' => 'spiritual', 'description' => 'Leads the church spiritually and provides pastoral care.'],
            ['name' => 'Associate Pastor', 'type' => 'spiritual', 'description' => 'Assists the senior pastor in spiritual duties.'],
            ['name' => 'Evangelist', 'type' => 'spiritual', 'description' => 'Focuses on spreading the gospel and evangelism.'],
            ['name' => 'Prayer Coordinator', 'type' => 'spiritual', 'description' => 'Leads and organizes prayer sessions and intercessory activities.'],
            ['name' => 'Ministry Leader', 'type' => 'spiritual', 'description' => 'Oversees a specific ministry within the church.'],
            ['name' => 'Worship Leader', 'type' => 'spiritual', 'description' => 'Leads the congregation in worship and praise.'],

            // Administrative Designations
            ['name' => 'Church Administrator', 'type' => 'administrative', 'description' => 'Manages the administrative operations of the church.'],
            ['name' => 'Finance Officer', 'type' => 'administrative', 'description' => 'Handles financial records and budgeting for the church.'],
            ['name' => 'Department Head (e.g., Youth Ministry Head)', 'type' => 'administrative', 'description' => 'Leads a specific department within the church.'],
            ['name' => 'Media Director', 'type' => 'administrative', 'description' => 'Manages media and communication strategies for the church.'],
            ['name' => 'Branch Coordinator', 'type' => 'administrative', 'description' => 'Coordinates activities across church branches.'],

            // Operational Designations
            ['name' => 'Usher', 'type' => 'operational', 'description' => 'Assists with seating and order during church services.'],
            ['name' => 'Choir Member', 'type' => 'operational', 'description' => 'Participates in the church choir and musical performances.'],
            ['name' => 'Technical Team Lead', 'type' => 'operational', 'description' => 'Manages technical aspects of church services (sound, lighting, etc.).'],
            ['name' => 'Event Organizer', 'type' => 'operational', 'description' => 'Plans and executes church events and programs.'],

            // General Designations
            ['name' => 'Volunteer', 'type' => 'general', 'description' => 'General volunteer role for various church activities.'],
            ['name' => 'Member', 'type' => 'general', 'description' => 'General designation for church members.'],
        ];

        // Insert designations into the database
        foreach ($designations as $designation) {
            Designation::create($designation);
        }
    }
    
}
