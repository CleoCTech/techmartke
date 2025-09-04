<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrainingModule;
use App\Models\CourseType;
use App\Models\FeeStructure;
use App\Models\PaymentOption;
use App\Models\RegistrationFee;
use Illuminate\Support\Str;

class FeeStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Training Modules
        $modules = [
            ['title' => 'MODULE I', 'slug' => 'module-i', 'sort_order' => 1],
            ['title' => 'MODULE II', 'slug' => 'module-ii', 'sort_order' => 2],
            ['title' => 'MODULE III', 'slug' => 'module-iii', 'sort_order' => 3],
        ];

        foreach ($modules as $module) {
            TrainingModule::create($module);
        }

        // Create Course Types
        $courseTypes = [
            [
                'name' => 'Refresher Course',
                'slug' => 'refresher-course',
                'color_class' => 'bg-green-400',
                'sort_order' => 1
            ],
            [
                'name' => 'Beginner Course',
                'slug' => 'beginner-course',
                'color_class' => 'bg-blue-400',
                'sort_order' => 2
            ],
            [
                'name' => 'Intermediate Course',
                'slug' => 'intermediate-course',
                'color_class' => 'bg-purple-400',
                'sort_order' => 3
            ],
        ];

        foreach ($courseTypes as $courseType) {
            CourseType::create($courseType);
        }

        // Create Fee Structures
        $feeStructures = [
            // MODULE I
            ['training_module_id' => 1, 'course_type_id' => 1, 'fee' => 12775, 'duration' => '3 months'],
            ['training_module_id' => 1, 'course_type_id' => 2, 'fee' => 17845, 'duration' => '6 months'],
            ['training_module_id' => 1, 'course_type_id' => 3, 'fee' => 9850, 'duration' => '4 months'],
            
            // MODULE II
            ['training_module_id' => 2, 'course_type_id' => 1, 'fee' => 17645, 'duration' => '3 months'],
            ['training_module_id' => 2, 'course_type_id' => 2, 'fee' => 22945, 'duration' => '6 months'],
            ['training_module_id' => 2, 'course_type_id' => 3, 'fee' => 13845, 'duration' => '4 months'],
            
            // MODULE III
            ['training_module_id' => 3, 'course_type_id' => 1, 'fee' => 27775, 'duration' => '3 months'],
            ['training_module_id' => 3, 'course_type_id' => 2, 'fee' => 36845, 'duration' => '6 months'],
            ['training_module_id' => 3, 'course_type_id' => 3, 'fee' => 23850, 'duration' => '4 months'],
        ];

        foreach ($feeStructures as $feeStructure) {
            FeeStructure::create($feeStructure);
        }

        // Create Payment Options
        $paymentOptions = [
            [
                'title' => 'Full Payment',
                'slug' => 'full-payment',
                'description' => 'Pay the complete fee upfront and save on processing',
                'benefit' => '5% Discount',
                'icon' => 'DollarSign',
                'sort_order' => 1
            ],
            [
                'title' => 'Installments',
                'slug' => 'installments',
                'description' => 'Split your payment into manageable monthly installments',
                'benefit' => 'Flexible Terms',
                'icon' => 'Calendar',
                'sort_order' => 2
            ],
            [
                'title' => 'Scholarship',
                'slug' => 'scholarship',
                'description' => 'Apply for merit-based scholarships and financial aid',
                'benefit' => 'Up to 30% Off',
                'icon' => 'Users',
                'sort_order' => 3
            ],
        ];

        foreach ($paymentOptions as $paymentOption) {
            PaymentOption::create($paymentOption);
        }

        // Create Registration Fee
        RegistrationFee::create([
            'title' => 'One-Time Registration Fee',
            'amount' => 3000,
            'currency' => 'Sh.',
            'description' => 'One-time registration fee for all students'
        ]);
    }
}
