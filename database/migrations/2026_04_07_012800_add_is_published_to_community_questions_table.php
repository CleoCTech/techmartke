<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('community_questions', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('is_featured');
            $table->text('admin_answer')->nullable()->after('is_published');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('community_questions', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropColumn(['is_published', 'admin_answer']);
        });
    }
};
