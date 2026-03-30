<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_knowledge_base', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('topic');
            $table->text('content');
            $table->json('keywords')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();

            $table->fullText(['topic', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_knowledge_base');
    }
};
