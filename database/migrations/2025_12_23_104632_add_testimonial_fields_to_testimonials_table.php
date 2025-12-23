<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'testifier_name')) {
                $table->string('testifier_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('testimonials', 'testimony')) {
                $table->text('testimony')->nullable()->after('feedback');
            }
            if (!Schema::hasColumn('testimonials', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable()->after('testimony');
            }
            if (!Schema::hasColumn('testimonials', 'remarks')) {
                $table->text('remarks')->nullable()->after('service_id');
            }
            if (!Schema::hasColumn('testimonials', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('remarks');
            }
        });
        
        // Add foreign key for service_id if it doesn't exist
        if (Schema::hasTable('services')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'testimonials' 
                    AND COLUMN_NAME = 'service_id' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                if (empty($foreignKeys)) {
                    $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
                }
            });
        }
        
        // Migrate existing data from old columns to new columns
        if (Schema::hasColumn('testimonials', 'name')) {
            DB::table('testimonials')->whereNotNull('name')->whereNull('testifier_name')->update([
                'testifier_name' => DB::raw('name')
            ]);
        }
        
        if (Schema::hasColumn('testimonials', 'feedback')) {
            DB::table('testimonials')->whereNotNull('feedback')->whereNull('testimony')->update([
                'testimony' => DB::raw('feedback')
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Drop foreign key first if it exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'testimonials' 
                AND COLUMN_NAME = 'service_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            if (!empty($foreignKeys)) {
                $table->dropForeign(['service_id']);
            }
        });
        
        Schema::table('testimonials', function (Blueprint $table) {
            // Then drop columns if they exist
            $columnsToDrop = [];
            if (Schema::hasColumn('testimonials', 'testifier_name')) {
                $columnsToDrop[] = 'testifier_name';
            }
            if (Schema::hasColumn('testimonials', 'testimony')) {
                $columnsToDrop[] = 'testimony';
            }
            if (Schema::hasColumn('testimonials', 'service_id')) {
                $columnsToDrop[] = 'service_id';
            }
            if (Schema::hasColumn('testimonials', 'remarks')) {
                $columnsToDrop[] = 'remarks';
            }
            if (Schema::hasColumn('testimonials', 'user_id')) {
                $columnsToDrop[] = 'user_id';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
