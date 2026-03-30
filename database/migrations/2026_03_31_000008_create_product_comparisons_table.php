<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_comparisons', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('product_1_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_2_id')->constrained('products')->cascadeOnDelete();
            $table->json('comparison_data')->nullable();
            $table->decimal('similarity_score', 5, 2)->nullable();
            $table->decimal('price_difference', 10, 2)->nullable();
            $table->string('category_winner')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_comparisons');
    }
};
