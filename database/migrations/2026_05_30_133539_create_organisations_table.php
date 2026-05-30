<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id('org_id');
            $table->string('licence_number')->unique();
            $table->string('org_name');
            $table->enum('org_type', ['operator', 'transporter', 'retailer']);
            $table->text('address')->nullable();
            $table->string('lga', 100)->nullable();
            $table->string('state', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('licence_number');
            $table->index('state');
        });
    }
    public function down(): void { Schema::dropIfExists('organisations'); }
};
