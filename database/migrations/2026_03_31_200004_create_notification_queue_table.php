<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_queue', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('subscriber_id')->constrained('vip_subscribers')->cascadeOnDelete();
            $table->foreignId('notification_id')->constrained('stock_notifications')->cascadeOnDelete();
            $table->enum('channel', ['email', 'sms', 'whatsapp'])->default('email');
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_queue');
    }
};
