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
        Schema::create('insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_id')
                ->constrained('insurances')
                ->onDelete('cascade');
            $table->string('name');
            $table->decimal('primary_care', 10, 2)->default(0);
            $table->decimal('specialist', 10, 2)->default(0);
            $table->decimal('emergency', 10, 2)->default(0);
            $table->decimal('annual_deductible', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_plans');
    }
};
