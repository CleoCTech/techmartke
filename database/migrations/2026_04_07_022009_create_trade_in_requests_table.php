<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_in_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('custom_device_name')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('storage_capacity')->nullable();
            $table->enum('condition', ['flawless', 'good', 'fair', 'broken'])->default('good');
            $table->integer('battery_health')->nullable();
            $table->boolean('has_cracks')->default(false);
            $table->boolean('has_repairs')->default(false);
            $table->text('problems_description')->nullable();
            $table->decimal('estimated_value', 10, 2)->nullable();
            $table->decimal('offered_value', 10, 2)->nullable();
            $table->enum('status', ['pending', 'quoted', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_in_requests');
    }
};
