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
        Schema::create('department_goals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fiscal_period_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->date('target_date');
            $table->integer("status")->default(1); // 1=PENDING, 2=COMPLETED, 3=IN PROGRESS
            $table->text('remarks')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_goals');
    }
};
