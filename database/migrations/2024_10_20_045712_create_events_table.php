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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('title');
            $table->string('slug')->unique();  // Add a slug field that must be unique
            $table->text('description');
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->string('location')->nullable();  // for physical events
            $table->string('online_link')->nullable();  // for online events
            $table->string('cover_image')->nullable();
            $table->integer("status")->default(1);
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
        Schema::dropIfExists('events');
    }
};
