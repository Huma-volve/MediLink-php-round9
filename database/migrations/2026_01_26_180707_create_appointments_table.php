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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->onDelete('cascade');
            //$table->dateTime('appointment_date_time');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['pending', 'upcoming', 'completed', 'cancelled'])->default('pending');
            $table->text('reason_for_visit')->nullable();
            $table->enum('consultation_type', ['in_person', 'online'])->default('in_person');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
