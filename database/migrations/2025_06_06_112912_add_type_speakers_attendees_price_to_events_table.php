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
        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->nullable()->after('location');
            $table->json('speakers')->nullable()->after('type');
            $table->integer('attendees')->nullable()->after('speakers');
            $table->string('price')->nullable()->after('attendees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['type', 'speakers', 'attendees', 'price']);
        });
    }
};
