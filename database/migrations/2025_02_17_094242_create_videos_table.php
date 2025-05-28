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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();           
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path'); // Path where the video is stored
            $table->string('cover_image')->nullable(); // Optional thumbnail
            $table->integer("status")->default(1);
            // $table->enum('status', ['pending', 'processing', 'available', 'failed'])->default('pending');
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
        Schema::dropIfExists('videos');
    }
};
