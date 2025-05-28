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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('role');
            $table->boolean('is_approver')->default(0);
            $table->integer('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        DB::table('admin_users')->insert(
            [
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => config('app.company.COMPANY_USER'),
                    'email' => config('app.company.COMPANY_EMAIL'),
                    'role' => 1,
                    'status' => 1,
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => config('app.developer.DEV_USER'),
                    'email' => config('app.developer.DEV_EMAIL'),
                    'role' => 1,
                    'status' => 1,
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
