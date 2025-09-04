<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_birth');
            
            // Program Selection
            $table->string('program');
            $table->string('start_date');
            $table->enum('study_format', ['in-person', 'online', 'hybrid']);
            $table->text('career_goals');
            
            // Final Details
            $table->string('hear_about_us')->nullable();
            $table->text('additional_info')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->boolean('marketing_consent')->default(false);
            
            // Status and tracking
            $table->integer('status')->default(1); // 1=New, 2=Pending Approval, 3=Archived, 4=Rejected
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
