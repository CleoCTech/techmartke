<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Generate UUIDs for existing products
        DB::table('products')->whereNull('uuid')->orderBy('id')->each(function ($product) {
            DB::table('products')->where('id', $product->id)->update(['uuid' => (string) Str::uuid()]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
