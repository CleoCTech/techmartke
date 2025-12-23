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
        Schema::create('web_traffic', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->date('date')->unique();
            $table->integer('visitors')->default(0);
            $table->integer('page_views')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->integer('bounce_rate')->nullable(); // percentage
            $table->decimal('avg_session_duration', 8, 2)->nullable(); // in seconds
            $table->json('top_pages')->nullable(); // Store top visited pages
            $table->json('referrers')->nullable(); // Store traffic sources
            $table->timestamps();
            
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_traffic');
    }
};
