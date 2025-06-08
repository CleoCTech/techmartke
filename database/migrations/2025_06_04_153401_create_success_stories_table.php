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
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('student_name');
            $table->string('course');
            $table->year('graduation_year');
            $table->string('cover_image')->nullable();
            $table->integer('sequence')->default(0);
            $table->tinyInteger('status')->default(1); // 1: Active, 2: Inactive, 3: Scheduled
            $table->timestamp('publish_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};
