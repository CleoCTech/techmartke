<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->string('slug')->unique()->after('uuid')->nullable(); // Temporarily nullable
            $table->softDeletes(); // Adds deleted_at column for soft deletes
        });

        // Generate slugs for existing records
        DB::table('designations')->get()->each(function ($designation) {
            DB::table('designations')
                ->where('id', $designation->id)
                ->update(['slug' => Str::slug($designation->name, '_')]);
        });

        // Make slug non-nullable
        Schema::table('designations', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropSoftDeletes();
        });
    }
};
