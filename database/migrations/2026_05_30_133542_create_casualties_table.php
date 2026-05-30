<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('casualties', function (Blueprint $table) {
            $table->id('casualty_id');
            $table->foreignId('incident_id')->constrained('incidents', 'incident_id')->cascadeOnDelete();
            $table->unsignedInteger('fatalities_count')->default(0);
            $table->unsignedInteger('injuries_count')->default(0);
            $table->unsignedInteger('evacuations_count')->default(0);
            $table->decimal('environmental_damage_est_usd', 15, 2)->default(0);
            $table->decimal('property_damage_est_usd', 15, 2)->default(0);
            $table->decimal('spill_volume_barrels', 10, 2)->nullable();
            $table->decimal('affected_area_sqkm', 8, 3)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('casualties'); }
};
