<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default(Hash::make(12345678));
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->integer('user_category')->default(0);
            $table->integer("status")->default(1);
            $table->integer("sequence")->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert(
            [
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => config('app.company.COMPANY_USER'),
                    'email' => config('app.company.COMPANY_EMAIL'),
                    // 'password' => '$2y$10$ItQn5QHFFgMBDLSw.fKWrO57iAflsFTUo2PRNuNFdrMnQhb7jFiwy',
                    'password' =>  Hash::make(config('app.company.COMPANY_PASS')),
                    'user_category' => 100,
                    'status' => 2,
                    'created_by' => config('app.company.COMPANY_EMAIL'),
                    'updated_by' => config('app.company.COMPANY_EMAIL'),
                ], 
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => config('app.developer.DEV_USER'),
                    'email' => config('app.developer.DEV_EMAIL'),
                    // 'password' => '$2y$10$ItQn5QHFFgMBDLSw.fKWrO57iAflsFTUo2PRNuNFdrMnQhb7jFiwy',
                    'password' =>  Hash::make(config('app.developer.DEV_PASS')),
                    'user_category' => 100,
                    'status' => 2,
                    'created_by' => config('app.developer.DEV_EMAIL'),
                    'updated_by' => config('app.developer.DEV_EMAIL'),
                ], 
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
