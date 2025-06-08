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
        Schema::table('staff', function (Blueprint $table) {
            $table->json('expertise')->nullable()->after('title');
            $table->string('experience')->nullable()->after('expertise');
            $table->string('education')->nullable()->after('experience');
            $table->text('achievements')->nullable()->after('education');
            $table->text('quote')->nullable()->after('achievements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'expertise',
                'experience',
                'education',
                'achievements',
                'quote'
            ]);
        });
    }
};
