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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('duration')->nullable();
            $table->string('level')->nullable();
            $table->text('description')->nullable();
            $table->integer('projects')->nullable();
            $table->integer('students')->nullable();
            $table->json('technologies')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
