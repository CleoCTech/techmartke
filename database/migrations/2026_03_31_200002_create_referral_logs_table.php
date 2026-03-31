<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('referrer_id')->constrained('vip_subscribers')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('vip_subscribers')->cascadeOnDelete();
            $table->string('promo_code_used');
            $table->enum('status', ['pending', 'completed', 'rewarded'])->default('pending');
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_logs');
    }
};
