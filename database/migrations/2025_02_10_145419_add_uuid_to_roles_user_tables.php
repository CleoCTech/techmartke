<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->before('role_id');
        });

        // Generate UUIDs for existing records
        $records = DB::table('role_user')->get();
        foreach ($records as $record) {
            DB::table('role_user')
                ->where('role_id', $record->role_id)
                ->where('user_id', $record->user_id)
                ->where('user_type', $record->user_type)
                ->update(['uuid' => Str::uuid()]);
        }

        // Make the UUID column unique and not nullable
        Schema::table('role_user', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
