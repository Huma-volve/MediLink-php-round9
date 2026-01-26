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
        Schema::create('online_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->onDelete('cascade');
            //$table->string('video_call_url')->nullable();
            $table->enum('call_type', ['audio', 'video']); // Type of call
            $table->enum('call_status', ['pending', 'ongoing', 'completed', 'missed', 'cancelled'])->default('pending');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('missed_count')->default(0); // Count of missed attempts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_consultations');
    }
};
