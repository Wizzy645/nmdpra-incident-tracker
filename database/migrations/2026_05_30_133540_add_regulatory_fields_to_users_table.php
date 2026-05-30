<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('org_id')->nullable()->after('id')->constrained('organisations', 'org_id')->nullOnDelete();
            $table->string('phone', 20)->nullable()->after('email');
            $table->enum('role', ['nmdpra_admin', 'nmdpra_inspector', 'operator_manager', 'field_agent', 'system_auditor'])->default('field_agent')->after('phone');
            $table->enum('status', ['active', 'suspended', 'pending'])->default('pending')->after('role');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->string('mfa_secret', 32)->nullable();
            $table->index('org_id');
            $table->index('role');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['org_id']);
            $table->dropColumn(['org_id', 'phone', 'role', 'status', 'last_login_at', 'last_login_ip', 'mfa_secret']);
        });
    }
};
