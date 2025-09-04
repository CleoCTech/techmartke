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
        Schema::create('payment_options', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title'); // Full Payment, Installments, Scholarship
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('benefit'); // 5% Discount, Flexible Terms, Up to 30% Off
            $table->string('icon'); // DollarSign, Calendar, Users
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_options');
    }
};
