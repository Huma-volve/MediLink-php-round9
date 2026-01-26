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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->integer('experience_years')->nullable();
            $table->string('certification')->nullable();
            $table->text('bio')->nullable();
            $table->text('education')->nullable();
            $table->decimal('consultation_fee_online', 8, 2)->nullable();
            $table->decimal('consultation_fee_inperson', 8, 2)->nullable();
            $table->foreignId('spelization_id')->constrained('spelizations')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
