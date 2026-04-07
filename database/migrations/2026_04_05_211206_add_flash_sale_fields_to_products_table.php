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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_flash_sale')->default(false)->after('featured');
            $table->decimal('flash_sale_price', 10, 2)->nullable()->after('is_flash_sale');
            $table->timestamp('flash_sale_ends_at')->nullable()->after('flash_sale_price');
            $table->index('is_flash_sale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_flash_sale']);
            $table->dropColumn(['is_flash_sale', 'flash_sale_price', 'flash_sale_ends_at']);
        });
    }
};
