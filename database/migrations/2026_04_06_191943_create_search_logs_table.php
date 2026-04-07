<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->enum('search_type', ['budget', 'smart', 'keyword'])->default('smart');
            $table->decimal('budget', 10, 2)->nullable();
            $table->integer('results_count')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('device_type', 20)->nullable(); // mobile, desktop, tablet
            $table->json('matched_product_ids')->nullable();
            $table->timestamps();

            $table->index('query');
            $table->index('search_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
