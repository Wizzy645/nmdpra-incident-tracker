<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('incident_id')->nullable()->constrained('incidents', 'incident_id')->cascadeOnDelete();
            $table->enum('channel', ['email', 'sms', 'dashboard']);
            $table->string('subject')->nullable();
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'is_read']);
        });
    }
    public function down(): void { Schema::dropIfExists('notifications'); }
};
