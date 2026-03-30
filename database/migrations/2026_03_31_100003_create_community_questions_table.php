<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_questions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->text('question');
            $table->string('asked_by');
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('is_answered');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_questions');
    }
};
