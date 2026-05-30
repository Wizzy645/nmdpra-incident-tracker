<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id('incident_id');
            $table->foreignId('reporter_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('org_id')->constrained('organisations', 'org_id')->restrictOnDelete();
            $table->string('incident_ref')->unique();
            $table->enum('incident_type', ['vandalism', 'sabotage', 'mechanical_failure', 'fire', 'explosion', 'spill', 'theft', 'other']);
            $table->enum('severity', ['low', 'medium', 'high', 'critical', 'fatal']);
            $table->enum('status', ['reported', 'under_investigation', 'resolved', 'closed'])->default('reported');
            $table->text('description');
            $table->string('location_address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->dateTime('occurred_at');
            $table->timestamp('reported_at')->useCurrent();
            $table->dateTime('compliance_deadline_at');
            $table->dateTime('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->boolean('is_compliant')->default(true);
            $table->json('media_evidence')->nullable();
            $table->timestamps();
            $table->index('incident_type');
            $table->index('severity');
            $table->index('status');
            $table->index('occurred_at');
            $table->index('is_compliant');
            $table->index(['latitude', 'longitude']);
            $table->fullText('description');
        });
    }
    public function down(): void { Schema::dropIfExists('incidents'); }
};
