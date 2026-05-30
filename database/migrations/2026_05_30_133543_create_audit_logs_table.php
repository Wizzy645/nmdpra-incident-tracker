<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('incident_id')->nullable()->constrained('incidents', 'incident_id')->nullOnDelete();
            $table->enum('action_type', ['create', 'read', 'update', 'delete', 'login', 'logout', 'export', 'alert_sent']);
            $table->string('action_description');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->index('user_id');
            $table->index('incident_id');
            $table->index('timestamp');
        });
    }
    public function down(): void { Schema::dropIfExists('audit_logs'); }
};
