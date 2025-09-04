<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('training_module_id')->constrained('training_modules')->onDelete('cascade');
            $table->foreignId('course_type_id')->constrained('course_types')->onDelete('cascade');
            $table->decimal('fee', 10, 2); // Course fee amount
            $table->string('duration'); // 3 months, 6 months, 4 months
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure unique combination of module and course type
            $table->unique(['training_module_id', 'course_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
