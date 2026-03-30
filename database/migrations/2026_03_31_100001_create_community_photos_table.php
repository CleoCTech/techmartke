<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_photos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('image_url');
            $table->text('caption')->nullable();
            $table->string('customer_name');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('likes_count')->default(0);
            $table->timestamps();

            $table->index('is_approved');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_photos');
    }
};
