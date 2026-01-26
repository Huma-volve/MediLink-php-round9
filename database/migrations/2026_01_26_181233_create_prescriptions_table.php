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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->onDelete('cascade');
            $table->string('prescription_number')->unique();
            $table->json('medications')->nullable();
            $table->string('frequency');
            $table->integer('duration_days');
            $table->text('additional_notes')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('patient_conditions')->nullable();
            $table->date('prescription_date');
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
