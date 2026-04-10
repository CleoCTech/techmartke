<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('location')->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->text('review');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('is_approved');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_reviews');
    }
};
