<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vip_subscribers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('name')->nullable();
            $table->string('promo_code', 20)->unique();
            $table->enum('status', ['pending', 'active', 'expired'])->default('pending');
            $table->enum('activation_method', ['payment', 'referral', 'admin'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->foreignId('referred_by_id')->nullable()->constrained('vip_subscribers')->nullOnDelete();
            $table->integer('referral_count')->default(0);
            $table->decimal('total_referral_earnings', 10, 2)->default(0);
            $table->boolean('notify_new_stock')->default(true);
            $table->boolean('notify_deals')->default(true);
            $table->boolean('notify_price_drops')->default(true);
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('promo_code');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vip_subscribers');
    }
};
