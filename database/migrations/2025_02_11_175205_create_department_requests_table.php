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
        Schema::create('department_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('from_department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('to_department_id')->constrained('departments')->onDelete('cascade');
            $table->string('type'); // e.g., resource, collaboration, budget, approval
            $table->string('title');
            $table->text('description');
            $table->integer('status')->default(1); //1=PENDING  // ['pending', 'approved', 'rejected', 'in_progress'])->default('pending');
            $table->text('remarks')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_requests');
    }
};
