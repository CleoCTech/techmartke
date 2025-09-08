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
        Schema::create('album_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_collection_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('original_filename')->nullable();
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('image_metadata')->nullable(); // For storing EXIF data, dimensions, etc.
            $table->timestamps();
            
            $table->index(['album_collection_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_images');
    }
};
