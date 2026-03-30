<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('success_stories', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('title');
            $table->text('content');
            $table->string('customer_name');
            $table->string('customer_avatar')->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->date('purchase_date')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('is_approved');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};
